<?php

namespace Database\Seeders;

use App\Models\department;
use App\Models\mosque;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentAndMosque extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            ['name' => 'أبو بكر الصديق'],
            ['name' => 'عمر بن الخطاب'],
            ['name' => 'عثمان بن عفان'],
            ['name' => 'خالد بن الوليد'],
        ];

        foreach ($departments as $department) {
            department::create($department);
        }

        $mosques = [
            ['name' => 'صلاح الدين', 'department_id' => 1],
            ['name' => 'أم المؤمنين (عائشة)', 'department_id' => 1],
            ['name' => 'الابرار', 'department_id' => 2],
            ['name' => 'الرضا', 'department_id' => 2],
            ['name' => 'الرحمن', 'department_id' => 2],
            ['name' => 'سعد', 'department_id' => 3],
            ['name' => 'مصعب بن عمير', 'department_id' => 3],
            ['name' => 'السعيد', 'department_id' => 3],
            ['name' => 'الخليل', 'department_id' => 3],
            ['name' => 'البر و التقوى', 'department_id' => 3],
            ['name' => 'الصديقين', 'department_id' => 3],
            ['name' => 'حسن البنا', 'department_id' => 4],
            ['name' => 'جعفر بن أبي طالب', 'department_id' => 4],
            ['name' => 'ساق الله', 'department_id' => 4],
            ['name' => 'عبدالله بن مسعود', 'department_id' => 4],
            ['name' => 'عليين', 'department_id' => 4],
        ];

        foreach ($mosques as $mosque) {
            mosque::create($mosque);
        }
    }
}
