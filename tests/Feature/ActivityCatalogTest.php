<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ActivityCatalogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'support_agent']);
    }

    public function test_guests_cannot_access_activity_catalog(): void
    {
        $response = $this->get('/activities');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_admin_can_view_activity_catalog(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get('/activities');
        $response->assertStatus(200);
    }

    public function test_admin_can_create_new_activity(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin);

        $component = Volt::test('activities.index')
            ->set('name', 'Database Replication Check')
            ->set('type', 'checklist')
            ->set('category', 'Database')
            ->set('description', 'Verify replication lag');

        $component->call('save');

        $this->assertDatabaseHas('activities', [
            'name' => 'Database Replication Check',
            'type' => 'checklist',
            'category' => 'Database',
            'is_active' => true,
        ]);
    }
}
