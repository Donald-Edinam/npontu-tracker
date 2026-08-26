<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Activity;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure required roles exist
        if (class_exists(Role::class)) {
            Role::firstOrCreate(['name' => 'admin']);
            Role::firstOrCreate(['name' => 'support_agent']);
        }

        $admin = User::firstOrCreate(
            ['email' => 'admin@npontu.test'],
            ['name' => 'Ama Admin', 'password' => Hash::make('password')]
        );
        if (method_exists($admin, 'assignRole')) {
            $admin->assignRole('admin');
        }

        $agent = User::firstOrCreate(
            ['email' => 'agent@npontu.test'],
            ['name' => 'Kwesi Agent', 'password' => Hash::make('password')]
        );
        if (method_exists($agent, 'assignRole')) {
            $agent->assignRole('support_agent');
        }

        Activity::create([
            'name' => 'Daily SMS count in comparison to SMS count from logs',
            'type' => 'metric',
            'category' => 'Messaging',
            'created_by' => $admin->id,
        ]);

        Activity::create([
            'name' => 'Reconcile failed payment notifications',
            'type' => 'checklist',
            'category' => 'Payments',
            'created_by' => $admin->id,
        ]);

        Activity::create([
            'name' => 'Verify daily backup status',
            'type' => 'checklist',
            'category' => 'Infrastructure',
            'created_by' => $admin->id,
        ]);

        Activity::create([
            'name' => 'Daily active users metric',
            'type' => 'metric',
            'category' => 'Analytics',
            'created_by' => $agent->id,
        ]);

        Activity::create([
            'name' => 'Confirm todays scheduled jobs ran',
            'type' => 'checklist',
            'category' => 'Jobs',
            'created_by' => $agent->id,
        ]);
    }
}
