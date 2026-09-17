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
        $orphanDepartmentRole = Role::firstOrCreate(
            ['name' => 'Orphan.department', 'guard_name' => 'web'],
            ['display_name' => 'قسم الأيتام']
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
            ['name' => 'orphan.guarantees', 'display_name' => 'كفالات الأيتام'],
            ['name' => 'orphan.guarantees.edit', 'display_name' => 'تعديل كفالات الأيتام'],
            ['name' => 'orphan.widows', 'display_name' => 'أرامل'],
            ['name' => 'orphan.widows.edit', 'display_name' => 'تعديل أرامل'],
            ['name' => 'orphan.aids.view', 'display_name' => 'عرض المساعدات'],
            ['name' => 'orphan.aids.create', 'display_name' => 'إنشاء المساعدات'],
            ['name' => 'orphan.aids.update', 'display_name' => 'تعديل المساعدات'],
            ['name' => 'orphan.aids.delete', 'display_name' => 'حذف المساعدات'],
            ['name' => 'orphan.aids.export', 'display_name' => 'تصدير المساعدات'],
            ['name' => 'orphan.aids.nominate.view', 'display_name' => 'عرض ترشيح المساعدات'],
            ['name' => 'orphan.aids.nominate', 'display_name' => 'ترشيح المساعدات'],
            ['name' => 'orphan.aids.nominate.delete', 'display_name' => 'حذف ترشيح المساعدات'],
            ['name' => 'orphan.aids.nominate.update', 'display_name' => 'تعديل ترشيح المساعدات'],
            ['name' => 'orphan.reports', 'display_name' => 'تقارير الأيتام'],
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

        $orphanDepartmentRole->syncPermissions([
            'orphan.view',
            'orphan.create',
            'orphan.update',
            'orphan.export',
            'orphan.guarantees',
            'orphan.widows',
            'orphan.widows.edit',
            'orphan.aids.view',
            'orphan.aids.export',
            'orphan.aids.nominate',
            'orphan.aids.nominate.view',
            'orphan.reports'

        ]);


        $orphanRole->syncPermissions([
            'orphan.view',
            'orphan.create',
            'orphan.update',
            'orphan.delete',
            'orphan.export',
            'orphan.guarantees',
            'orphan.guarantees.edit',
            'orphan.widows',
            'orphan.widows.edit',
            'orphan.aids.view',
            'orphan.aids.create',
            'orphan.aids.update',
            'orphan.aids.delete',
            'orphan.aids.export',
            'orphan.aids.nominate',
            'orphan.aids.nominate.delete',
            'orphan.aids.nominate.update',
            'orphan.aids.nominate.view',
            'orphan.reports'

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
