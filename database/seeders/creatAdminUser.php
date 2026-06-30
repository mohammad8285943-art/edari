<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class creatAdminUser extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'محمد',
            'username' => 'mm',
            'password' => bcrypt('12345678'),
            'ssn' => '123456789',
            'role' => 'مدير نظام',
            'active' => 1,
            'mosque_id'=> 1,
            'department_id' => 1,
        ]);
    }
}
