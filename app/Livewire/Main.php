<?php

namespace App\Livewire;

use App\Models\AidBeneficiary;
use App\Models\Orphan;
use App\Models\Widow;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layout.app', ['title' => 'لوحة التحكم'])]
class Main extends Component
{
    protected function scopeByUserView($query, string $table = '')
    {
        $user = Auth::user();
        $prefix = $table ? "{$table}." : '';

        if ($user->view == 3) {
            $query->where($prefix . 'mosque_id', $user->mosque_id);
        } elseif ($user->view == 2) {
            $query->where($prefix . 'department_id', $user->department_id);
        }

        return $query;
    }

    public function render()
    {
        $sixteenYearsAgo = Carbon::now()->subYears(16)->toDateString();

        // 1. ملخص الأيتام
        $orphanQuery = Orphan::query();
        $this->scopeByUserView($orphanQuery);

        $orphanStats = (clone $orphanQuery)
            ->selectRaw('COUNT(*) as total_orphans')
            ->selectRaw('COUNT(CASE WHEN barth IS NOT NULL AND barth > ? THEN 1 END) as under_16', [$sixteenYearsAgo])
            ->selectRaw('COUNT(CASE WHEN barth IS NOT NULL AND barth <= ? THEN 1 END) as equal_over_16', [$sixteenYearsAgo])
            ->selectRaw('COUNT(CASE WHEN EXISTS (
                SELECT 1 FROM guarantees WHERE guarantees.ssn = orphan.SSN
            ) THEN 1 END) as guaranteed_count')
            ->selectRaw('COUNT(CASE WHEN NOT EXISTS (
                SELECT 1 FROM guarantees WHERE guarantees.ssn = orphan.SSN
            ) THEN 1 END) as not_guaranteed_count')
            ->selectRaw("COUNT(CASE WHEN health IS NOT NULL AND TRIM(health) != 'جيدة' AND TRIM(health) != '' THEN 1 END) as injured_count")
            ->selectRaw("COUNT(CASE WHEN `حالة_اليتيم_الناجي_الوحيد_يتيم_الأبوين` = 'ناجي وحيد' THEN 1 END) as lone_survivor_count")
            ->selectRaw("COUNT(CASE WHEN `حالة_اليتيم_الناجي_الوحيد_يتيم_الأبوين` = 'يتيم الأبوين' THEN 1 END) as both_parents_dead_count")
            ->first();

        // إجمالي الكفالات الفعلي (بما في ذلك التكرار) مقيد بنطاق العرض
        $guaranteesCountQuery = DB::table('guarantees')
            ->join('orphan', 'orphan.SSN', '=', 'guarantees.ssn');
        $this->scopeByUserView($guaranteesCountQuery, 'orphan');
        $totalGuarantees = $guaranteesCountQuery->count();

        // 2. ملخص الأرامل
        $widowQuery = Widow::query();
        $this->scopeByUserView($widowQuery);

        $widowStats = (clone $widowQuery)
            ->selectRaw('COUNT(*) as total_widows')
            ->selectRaw('COUNT(CASE WHEN orphan_count > 0 THEN 1 END) as with_orphans')
            ->selectRaw('COUNT(CASE WHEN orphan_count = 0 OR orphan_count IS NULL THEN 1 END) as without_orphans')
            ->first();

        // 3. ملخص المساعدات (للشهر الحالي كافتراضي)
        $aidQuery = AidBeneficiary::query()
            ->join('aids', 'aids.id', '=', 'aid_beneficiaries.aid_id');

        $user = Auth::user();
        if ($user->view == 2 && $user->department?->name) {
            $aidQuery->where('aid_beneficiaries.department', 'like', '%' . $user->department->name . '%');
        }

        $aidStats = (clone $aidQuery)
            ->selectRaw("COUNT(CASE WHEN aid_beneficiaries.beneficiary_type = 'orphan' THEN 1 END) as orphan_aids")
            ->selectRaw("COUNT(CASE WHEN aid_beneficiaries.beneficiary_type = 'widow' THEN 1 END) as widow_aids")
            ->selectRaw('COUNT(*) as total_aids')
            ->first();

        return view('livewire.main', [
            'orphans'         => $orphanStats,
            'totalGuarantees' => $totalGuarantees,
            'widows'          => $widowStats,
            'aids'            => $aidStats,
        ]);
    }
}
