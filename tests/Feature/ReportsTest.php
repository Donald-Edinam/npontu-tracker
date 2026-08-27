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

class ReportsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'support_agent']);
    }

    public function test_guests_cannot_access_reports(): void
    {
        $response = $this->get('/reports');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_reports(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/reports');
        $response->assertStatus(200);
    }

    public function test_reports_displays_logs_and_completions_within_date_range(): void
    {
        $agent = User::factory()->create(['name' => 'Kofi Agent']);
        $agent->assignRole('support_agent');

        $activity = Activity::factory()->create(['name' => 'Database Backup Verification']);
        $entry = DailyActivityEntry::create([
            'activity_id' => $activity->id,
            'date' => today(),
            'status' => 'done',
        ]);

        ActivityUpdateLog::create([
            'daily_activity_entry_id' => $entry->id,
            'updated_by' => $agent->id,
            'old_status' => 'pending',
            'new_status' => 'done',
            'remark' => 'Completed backup check',
        ]);

        $this->actingAs($agent);

        $component = Volt::test('reports')
            ->set('from', today()->subDays(2)->toDateString())
            ->set('to', today()->toDateString());

        $component->assertSee('Database Backup Verification')
            ->assertSee('Kofi Agent')
            ->assertSee('Completed backup check');
    }
}
