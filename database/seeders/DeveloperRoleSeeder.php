<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User; 

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

       
        $role = Role::firstOrCreate([
            'name' => 'developer',
            'guard_name' => 'user-api'
        ]);

        $role->syncPermissions($permissions);
    }
}
