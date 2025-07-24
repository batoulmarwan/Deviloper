<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DeveloperRoleSeeder extends Seeder
{
    public function run(): void
    {
      
        $permissions = [
            'create-project',
            'view-projects',
            'create-cv',
            'view-cv'
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate([
                'name' => $perm,
                'guard_name' => 'user-api'
            ]);
        }

       
        $roleEngineer = Role::firstOrCreate([
            'name' => 'Employee
            ',
            'guard_name' => 'user-api'
        ]);
        $roleEngineer->syncPermissions($permissions);

      
        Role::firstOrCreate([
            'name' => 'Admin',
            'guard_name' => 'user-api'
        ]);

       
        Role::firstOrCreate([
            'name' => 'HR',
            'guard_name' => 'user-api'
        ]);
    }
}
