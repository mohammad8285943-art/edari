<?php

namespace App\Livewire\Reports;

use App\Models\AidBeneficiary;
use App\Models\Department;
use App\Models\Orphan;
use App\Models\Widow;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layout.app', ['title' => 'التقارير والإحصائيات'])]
class OrphanReport extends Component
{
    public $fromDate = '';

    public $toDate = '';

    public function mount(): void
    {
        $this->fromDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->toDate = Carbon::now()->format('Y-m-d');
    }

    public function resetDateFilter(): void
    {
        $this->fromDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->toDate = Carbon::now()->format('Y-m-d');
        $this->resetErrorBag();
    }

    public function getOrphansReportProperty(): array
    {
        $departments = Department::orderBy('id')->get();

        // حساب التاريخ الذي يعادل 16 سنة بدقة من اليوم الحالي
        $sixteenYearsAgo = Carbon::now()->subYears(16)->toDateString();

        $stats = Orphan::query()
            ->select('department_id')
            ->selectRaw('COUNT(*) as total_orphans')
            // تحت 16 سنة (تاريخ الميلاد بعد تاريخ 16 سنة مضت)
            ->selectRaw('COUNT(CASE WHEN barth IS NOT NULL AND barth > ? THEN 1 END) as under_16', [$sixteenYearsAgo])
            // 16 سنة فأكثر
            ->selectRaw('COUNT(CASE WHEN barth IS NOT NULL AND barth <= ? THEN 1 END) as equal_over_16', [$sixteenYearsAgo])
            // عدد المكفولين: وجود سجل كفالة واحد على الأقل بنفس SSN
            ->selectRaw('COUNT(CASE WHEN EXISTS (
                SELECT 1 FROM guarantees WHERE guarantees.ssn = orphan.SSN
            ) THEN 1 END) as guaranteed_count')
            // عدد الكفالات مع احتساب التكرارات
            ->selectRaw('(
                SELECT COUNT(*)
                FROM guarantees g
                left outer JOIN orphan o2 ON o2.SSN = g.ssn
                WHERE o2.department_id = orphan.department_id
            ) as guaranteed_unique_ssn')
            // غير المكفولين عبر NOT EXISTS
            ->selectRaw('COUNT(CASE WHEN NOT EXISTS (
                SELECT 1 FROM guarantees WHERE guarantees.ssn = orphan.SSN
            ) THEN 1 END) as not_guaranteed_count')
            // المصابون: الحالة ليست جيدة
            ->selectRaw("COUNT(CASE WHEN health IS NOT NULL AND TRIM(health) != 'جيدة' AND TRIM(health) != '' THEN 1 END) as injured_count")
            // الناجي الوحيد
            ->selectRaw("COUNT(CASE WHEN `حالة_اليتيم_الناجي_الوحيد_يتيم_الأبوين` = 'ناجي وحيد' THEN 1 END) as lone_survivor_count")
            // يتيم الأبوين
            ->selectRaw("COUNT(CASE WHEN `حالة_اليتيم_الناجي_الوحيد_يتيم_الأبوين` = 'يتيم الأبوين' THEN 1 END) as both_parents_dead_count")
            ->whereNotNull('department_id')
            ->groupBy('department_id')
            ->get()
            ->keyBy('department_id');

        $rows = [
            'total_orphans' => 'عدد الأيتام',
            'under_16' => 'الأيتام تحت 16 سنة',
            'equal_over_16' => 'الأيتام 16 سنة فأكثر',
            'guaranteed_count' => 'عدد المكفولين',
            'guaranteed_unique_ssn' => 'عدد الكفالات',
            'not_guaranteed_count' => 'غير المكفولين',
            'injured_count' => 'الأيتام المصابون',
            'lone_survivor_count' => 'الناجي الوحيد',
            'both_parents_dead_count' => 'يتيم الأبوين',
        ];

        $matrix = [];
        $totals = array_fill_keys(array_keys($rows), 0);

        foreach ($rows as $key => $label) {
            $matrix[$key] = [
                'label' => $label,
                'values' => [],
                'total' => 0,
            ];

            foreach ($departments as $dept) {
                $val = isset($stats[$dept->id]) ? (int) $stats[$dept->id]->{$key} : 0;
                $matrix[$key]['values'][$dept->id] = $val;
                $matrix[$key]['total'] += $val;
            }

            $totals[$key] = $matrix[$key]['total'];
        }

        return [
            'departments' => $departments,
            'matrix' => $matrix,
            'totals' => $totals,
        ];
    }

    public function getWidowsReportProperty(): array
    {
        $departments = Department::orderBy('id')->get();

        $stats = Widow::query()
            ->select('department_id')
            ->selectRaw('COUNT(*) as total_widows')
            ->selectRaw('COUNT(CASE WHEN orphan_count > 0 THEN 1 END) as with_orphans')
            ->selectRaw('COUNT(CASE WHEN orphan_count = 0 OR orphan_count IS NULL THEN 1 END) as without_orphans')
            ->whereNotNull('department_id')
            ->groupBy('department_id')
            ->get()
            ->keyBy('department_id');

        $rows = [
            'total_widows' => 'عدد الأرامل',
            'with_orphans' => 'عدد الأرامل بأيتام',
            'without_orphans' => 'عدد الأرامل بدون أيتام',
        ];

        $matrix = [];
        $totals = array_fill_keys(array_keys($rows), 0);

        foreach ($rows as $key => $label) {
            $matrix[$key] = [
                'label' => $label,
                'values' => [],
                'total' => 0,
            ];

            foreach ($departments as $dept) {
                $val = isset($stats[$dept->id]) ? (int) $stats[$dept->id]->{$key} : 0;
                $matrix[$key]['values'][$dept->id] = $val;
                $matrix[$key]['total'] += $val;
            }

            $totals[$key] = $matrix[$key]['total'];
        }

        return [
            'departments' => $departments,
            'matrix' => $matrix,
            'totals' => $totals,
        ];
    }

    public function getAidReportProperty(): array
    {
        $columns = [
            'بكر' => 'بكر',
            'عمر' => 'عمر',
            'عثمان' => 'عثمان',
            'خالد' => 'خالد',
        ];

        $rows = [
            'orphan_aids' => 'مساعدات الأيتام',
            'widow_aids' => 'مساعدات الأرامل',
            'total_aids' => 'إجمالي المساعدات',
        ];

        // التحقق من التاريخ
        if (
            ! empty($this->fromDate) &&
            ! empty($this->toDate) &&
            $this->fromDate > $this->toDate
        ) {
            $this->addError(
                'date_range',
                'تاريخ البداية لا يمكن أن يكون بعد تاريخ النهاية.'
            );

            return [
                'columns' => $columns,
                'matrix' => [],
                'totals' => [],
                'grand_total' => 0,
            ];
        }

        $matrix = [];
        $totals = array_fill_keys(array_keys($rows), 0);

        foreach ($rows as $key => $label) {
            $matrix[$key] = [
                'label' => $label,
                'values' => [],
                'total' => 0,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | حساب المساعدات لكل قسم
        |--------------------------------------------------------------------------
        */

        foreach ($columns as $category => $categoryLabel) {

            $query = AidBeneficiary::query()
                ->join(
                    'aids',
                    'aids.id',
                    '=',
                    'aid_beneficiaries.aid_id'
                )
                ->where('aid_beneficiaries.department', 'like', '%'.$category.'%');

            /*
            |--------------------------------------------------------------------------
            | فلترة تاريخ التنفيذ
            |--------------------------------------------------------------------------
            */

            if (! empty($this->fromDate)) {
                $query->whereDate(
                    'aids.date_execution',
                    '>=',
                    $this->fromDate
                );
            }

            if (! empty($this->toDate)) {
                $query->whereDate(
                    'aids.date_execution',
                    '<=',
                    $this->toDate
                );
            }

            /*
            |--------------------------------------------------------------------------
            | عدد مساعدات الأيتام
            |--------------------------------------------------------------------------
            |
            | beneficiary_type في قاعدة البيانات:
            | orphan
            |
            */

            $orphanCount = (clone $query)
                ->where('aid_beneficiaries.beneficiary_type', 'orphan')
                ->count();

            /*
            |--------------------------------------------------------------------------
            | عدد مساعدات الأرامل
            |--------------------------------------------------------------------------
            |
            | beneficiary_type في قاعدة البيانات:
            | widow
            |
            */

            $widowCount = (clone $query)
                ->where('aid_beneficiaries.beneficiary_type', 'widow')
                ->count();

            /*
            |--------------------------------------------------------------------------
            | إجمالي المساعدات
            |--------------------------------------------------------------------------
            */

            $totalCount = (clone $query)->count();

            $matrix['orphan_aids']['values'][$category] = $orphanCount;
            $matrix['widow_aids']['values'][$category] = $widowCount;
            $matrix['total_aids']['values'][$category] = $totalCount;

            $matrix['orphan_aids']['total'] += $orphanCount;
            $matrix['widow_aids']['total'] += $widowCount;
            $matrix['total_aids']['total'] += $totalCount;

            $totals['orphan_aids'] += $orphanCount;
            $totals['widow_aids'] += $widowCount;
            $totals['total_aids'] += $totalCount;
        }

        return [
            'columns' => $columns,
            'matrix' => $matrix,
            'totals' => $totals,
            'grand_total' => $totals['total_aids'],
        ];
    }

    public function render()
    {
        return view('livewire.reports.OrphanReport', [
            'orphansData' => $this->orphansReport,
            'widowsData' => $this->widowsReport,
            'aidData' => $this->aidReport,
        ]);
    }
}
