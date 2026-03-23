<?php

namespace Database\Seeders;

use App\Models\Ngo;
use App\Models\User;
use App\Services\Permission\PermissionService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed all permissions & SuperAdmin role
        app(PermissionService::class)->initPermissions(true);

        // 2. Create a default NGO
        $ngo = Ngo::firstOrCreate(
            ['name' => 'African Leadership University'],
            [
                'uuid'        => Str::uuid(),
                'description' => 'Default NGO for LiftED MVP.',
            ]
        );

        // 3. Create SuperAdmin
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@lifted.alu.edu'],
            [
                'uuid'        => Str::uuid(),
                'firstname'   => 'Super',
                'lastname'    => 'Admin',
                'phone_number'=> '+250 700 000 000',
                'role'        => 'superadmin',
                'password'    => Hash::make('password'),
                'ngo_id'      => $ngo->id,
                'is_approved' => true,
            ]
        );
        $superAdmin->syncRoles(['SuperAdmin']);

        // 4. Create sample NGO Staff
        $staff = User::firstOrCreate(
            ['email' => 'staff@lifted.alu.edu'],
            [
                'uuid'        => Str::uuid(),
                'firstname'   => 'NGO',
                'lastname'    => 'Staff',
                'role'        => 'ngo_staff',
                'password'    => Hash::make('password'),
                'ngo_id'      => $ngo->id,
                'is_approved' => true,
            ]
        );
        $staffRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'NGO Staff', 'guard_name' => 'web']);
        $staffPerms = \Spatie\Permission\Models\Permission::where('name', 'like', '% programs.%')
            ->orWhere('name', 'like', '% learners.%')
            ->get();
        $staffRole->syncPermissions($staffPerms);
        $staff->syncRoles(['NGO Staff']);

        // 5. Create sample Mentor
        $mentor = User::firstOrCreate(
            ['email' => 'mentor@lifted.alu.edu'],
            [
                'uuid'        => Str::uuid(),
                'firstname'   => 'Jane',
                'lastname'    => 'Mentor',
                'role'        => 'mentor',
                'password'    => Hash::make('password'),
                'ngo_id'      => $ngo->id,
                'is_approved' => true,
            ]
        );
        $mentorRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Mentor', 'guard_name' => 'web']);
        $mentorPerms = \Spatie\Permission\Models\Permission::whereIn('name', [
            'list learners.progress', 'read learners.progress', 'update learners.progress',
            'read programs.material', 'list programs.material',
        ])->get();
        $mentorRole->syncPermissions($mentorPerms);
        $mentor->syncRoles(['Mentor']);

        // 6. Create sample Learner
        $learner = User::firstOrCreate(
            ['email' => 'learner@lifted.alu.edu'],
            [
                'uuid'        => Str::uuid(),
                'firstname'   => 'Alex',
                'lastname'    => 'Learner',
                'role'        => 'learner',
                'password'    => Hash::make('password'),
                'ngo_id'      => $ngo->id,
                'is_approved' => true,
            ]
        );
        $learnerRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Learner', 'guard_name' => 'web']);
        $learnerPerms = \Spatie\Permission\Models\Permission::whereIn('name', [
            'read programs.material', 'list programs.material',
            'list programs.program', 'read programs.program',
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
