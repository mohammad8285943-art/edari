<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        /*
        |----------------------------------
        | Roles
        |----------------------------------
        */
            Permission::firstOrCreate(
                ['name' =>'usersAdmin' , 'guard_name' => 'web'],
                ['display_name' =>'إدارة المستخدمين' ]
            );
        $userAdminRole = Role::firstOrCreate(
            ['name' => 'User.Administration', 'guard_name' => 'web'],
            ['display_name' => 'إدارة المستخدمين']
        );

        $userAdminRole->syncPermissions(['usersAdmin']);

        $orphanRole = Role::firstOrCreate(
            ['name' => 'Orphan.Administration', 'guard_name' => 'web'],
            ['display_name' => 'إدارة الأيتام']
        );

        /*
        |----------------------------------
        | Permissions
        |----------------------------------
        */

        $permissions = [
            ['name' => 'orphan.view',   'display_name' => 'عرض الأيتام'],
            ['name' => 'orphan.create', 'display_name' => 'إنشاء الأيتام'],
            ['name' => 'orphan.update', 'display_name' => 'تعديل الأيتام'],
            ['name' => 'orphan.delete', 'display_name' => 'حذف الأيتام'],
            ['name' => 'orphan.export', 'display_name' => 'تصدير الأيتام'],
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(
                ['name' => $perm['name'], 'guard_name' => 'web'],
                ['display_name' => $perm['display_name']]
            );
        }

        /*
        |----------------------------------
        | Assign permissions to role
        |----------------------------------
        */

        $orphanRole->syncPermissions([
            'orphan.view',
            'orphan.create',
            'orphan.update',
            'orphan.delete',
            'orphan.export',
        ]);

        /*
        |----------------------------------
        | Assign role to user
        |----------------------------------
        */

        $user = User::where('username', 'mm')->first();

        if ($user) {
            $user->assignRole([
                'User.Administration',
                'Orphan.Administration',
            ]);
        }
    }
}
