<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\DailyActivityEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RoleBasedUiVisibilityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'support_agent']);
    }

    private function userWithRole(string $role): User
    {
        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }

    public function test_support_agent_does_not_see_admin_only_catalog_controls(): void
    {
        Activity::factory()->create(['name' => 'Daily SMS Reconciliation']);

        $response = $this->actingAs($this->userWithRole('support_agent'))->get('/activities');

        $response->assertOk();
        $response->assertDontSee('wire:click="create"', false);
        $response->assertDontSee('wire:click="edit(', false);
        $response->assertDontSee('wire:click="toggleActive(', false);
        $response->assertDontSee('wire:click="save"', false);
    }

    public function test_support_agent_sees_read_only_fallbacks_on_catalog(): void
    {
        Activity::factory()->create(['name' => 'Daily SMS Reconciliation', 'is_active' => true]);

        $response = $this->actingAs($this->userWithRole('support_agent'))->get('/activities');

        $response->assertSee('Daily SMS Reconciliation');
        $response->assertSee('<span class="text-gray-400">&mdash;</span>', false);
        $response->assertSee('Active');
    }

    public function test_admin_still_sees_every_catalog_control(): void
    {
        Activity::factory()->create(['name' => 'Daily SMS Reconciliation']);

        $response = $this->actingAs($this->userWithRole('admin'))->get('/activities');

        $response->assertOk();
        $response->assertSee('wire:click="create"', false);
        $response->assertSee('wire:click="edit(', false);
        $response->assertSee('wire:click="toggleActive(', false);
        $response->assertSee('New Activity');
        $response->assertSee('Edit');
    }

    public function test_admin_can_still_create_and_edit_activities(): void
    {
        $this->actingAs($this->userWithRole('admin'));

        Volt::test('activities.index')
            ->call('create')
            ->set('name', 'Replication Lag Check')
            ->set('type', 'checklist')
            ->set('category', 'Database')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('activities', ['name' => 'Replication Lag Check']);

        $activity = Activity::where('name', 'Replication Lag Check')->firstOrFail();

        Volt::test('activities.index')
            ->call('edit', $activity->id)
            ->set('name', 'Replication Lag Check v2')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('activities', ['id' => $activity->id, 'name' => 'Replication Lag Check v2']);
    }

    public function test_support_agent_is_still_blocked_by_policy_on_save(): void
    {
        $this->actingAs($this->userWithRole('support_agent'));

        Volt::test('activities.index')
            ->set('name', 'Sneaky Activity')
            ->set('type', 'checklist')
            ->call('save')
            ->assertForbidden();

        $this->assertDatabaseMissing('activities', ['name' => 'Sneaky Activity']);
    }

    public function test_support_agent_is_still_blocked_by_policy_on_toggle(): void
    {
        $activity = Activity::factory()->create(['is_active' => true]);

        $this->actingAs($this->userWithRole('support_agent'));

        Volt::test('activities.index')
            ->call('toggleActive', $activity->id)
            ->assertForbidden();

        $this->assertDatabaseHas('activities', ['id' => $activity->id, 'is_active' => true]);
    }

    public function test_support_agent_keeps_full_access_to_today_page(): void
    {
        DailyActivityEntry::factory()->create(['date' => today(), 'status' => 'pending']);

        $response = $this->actingAs($this->userWithRole('support_agent'))->get('/today');

        $response->assertOk();
        $response->assertSee('wire:click="selectEntry(', false);
    }

    public function test_support_agent_sees_all_navigation_links(): void
    {
        $response = $this->actingAs($this->userWithRole('support_agent'))->get('/today');

        $response->assertOk();
        $response->assertSee('Dashboard');
        $response->assertSee("Today's Activity", false);
        $response->assertSee('Activity Catalog');
        $response->assertSee('Reports');
    }
}
