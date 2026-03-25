<?php

namespace Database\Seeders;

use App\Models\Ngo;
use App\Models\User;
use App\Services\Permission\PermissionService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionService::class)->initPermissions(true);

        $ngo = Ngo::firstOrCreate(
            ['name' => 'African Leadership University'],
            [
                'uuid' => Str::uuid(),
                'description' => 'Default NGO for LiftED MVP.',
            ]
        );

        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@lifted.alu.edu'],
            [
                'uuid' => Str::uuid(),
                'firstname' => 'Super',
                'lastname' => 'Admin',
                'phone_number' => '+250 700 000 000',
                'role' => 'superadmin',
                'password' => Hash::make('password'),
                'ngo_id' => $ngo->id,
                'is_approved' => true,
            ]
        );
        $superAdmin->syncRoles(['SuperAdmin']);

        $staff = User::firstOrCreate(
            ['email' => 'staff@lifted.alu.edu'],
            [
                'uuid' => Str::uuid(),
                'firstname' => 'NGO',
                'lastname' => 'Staff',
                'role' => 'ngo_staff',
                'password' => Hash::make('password'),
                'ngo_id' => $ngo->id,
                'is_approved' => true,
            ]
        );
        $staffRole = Role::firstOrCreate(['name' => 'NGO Staff', 'guard_name' => 'web']);
        $staffPerms = Permission::query()
            ->where(function ($q) {
                $q->where('name', 'like', '% programs.%')
                    ->orWhere('name', 'like', '% learners.%');
            })
            ->whereNotIn('name', ['update learners.progress', 'read learners.own_progress'])
            ->get();
        $staffRole->syncPermissions($staffPerms);
        $staff->syncRoles(['NGO Staff']);

        $mentor = User::firstOrCreate(
            ['email' => 'mentor@lifted.alu.edu'],
            [
                'uuid' => Str::uuid(),
                'firstname' => 'Jane',
                'lastname' => 'Mentor',
                'role' => 'mentor',
                'password' => Hash::make('password'),
                'ngo_id' => $ngo->id,
                'is_approved' => true,
            ]
        );
        $mentorRole = Role::firstOrCreate(['name' => 'Mentor', 'guard_name' => 'web']);
        $mentorPerms = Permission::whereIn('name', [
            'list learners.progress', 'read learners.progress', 'update learners.progress',
            'read programs.program',
            'read programs.material', 'list programs.material',
        ])->get();
        $mentorRole->syncPermissions($mentorPerms);
        $mentor->syncRoles(['Mentor']);

        $learner = User::firstOrCreate(
            ['email' => 'learner@lifted.alu.edu'],
            [
                'uuid' => Str::uuid(),
                'firstname' => 'Alex',
                'lastname' => 'Learner',
                'role' => 'learner',
                'password' => Hash::make('password'),
                'ngo_id' => $ngo->id,
                'is_approved' => true,
            ]
        );
        $learnerRole = Role::firstOrCreate(['name' => 'Learner', 'guard_name' => 'web']);
        $learnerPerms = Permission::whereIn('name', [
            'read programs.material', 'list programs.material',
            'read programs.program',
            'read learners.own_progress',
        ])->get();
        $learnerRole->syncPermissions($learnerPerms);
        $learner->syncRoles(['Learner']);

        $this->command->info('✅ LiftED seeded successfully.');
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['SuperAdmin', 'admin@lifted.alu.edu',   'password'],
                ['NGO Staff',  'staff@lifted.alu.edu',   'password'],
                ['Mentor',     'mentor@lifted.alu.edu',  'password'],
                ['Learner',    'learner@lifted.alu.edu', 'password'],
            ]
        );
    }
}
