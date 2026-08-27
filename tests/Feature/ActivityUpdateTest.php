<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\ActivityUpdateLog;
use App\Models\DailyActivityEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ActivityUpdateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'support_agent']);
    }

    public function test_creates_exactly_one_log_row_and_updates_the_entry_status_when_saved(): void
    {
        $agent = User::factory()->create();
        $agent->assignRole('support_agent');

        $activity = Activity::factory()->create();
        $entry = DailyActivityEntry::create([
            'activity_id' => $activity->id,
            'date' => today(),
            'status' => 'pending',
        ]);

        $this->actingAs($agent);

        Volt::test('today')
            ->call('selectEntry', $entry->id)
            ->set('remark', 'done for today')
            ->call('save', 'done');

        $this->assertEquals('done', $entry->fresh()->status);
        $this->assertEquals(1, ActivityUpdateLog::where('daily_activity_entry_id', $entry->id)->count());

        $log = ActivityUpdateLog::where('daily_activity_entry_id', $entry->id)->first();
        $this->assertEquals($agent->id, $log->updated_by);
        $this->assertEquals('pending', $log->old_status);
        $this->assertEquals('done', $log->new_status);
        $this->assertEquals('done for today', $log->remark);
    }
}
