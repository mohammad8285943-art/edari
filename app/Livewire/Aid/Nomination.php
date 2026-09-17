<?php

namespace App\Livewire\Aid;

use App\Models\Aid;
use App\Models\AidBeneficiary;
use App\Models\Department;
use App\Models\Mosque;
use App\Models\Orphan;
use App\Models\widow;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layout.app', ['title' => 'ترشيح المستفيدين للمساعدة'])]
class Nomination extends Component
{
    use WithPagination;

    public Aid $aid;

    // فلاتر البحث العامة
    public $search = '';
    public $filterDepartment = '';
    public $filterMosque = '';

    // فلاتر خاصة بالأيتام
    public $filterMinAge = '';
    public $filterMaxAge = '';

    // فلاتر خاصة بالأرامل
    public $filterMinOrphans = '';
    public $filterMaxOrphans = '';

    // فترة الاستفادة وقواعد المنع
    public $dateFrom = '';
    public $dateTo = '';
    public $excludeBeneficiaries = true; // استبعاد الذين استفادوا خلال الفترة
    public $checkMode = 'family'; // 'family' (على مستوى الأسرة) أو 'individual' (فردي فقط)

    // العناصر المحددة
    public $selected = [];
    public $selectAll = false;

    public function mount(Aid $aid): void
    {
        $this->aid = $aid;

        // الفترة الافتراضية: من أول السنة الحالية حتى تاريخ اليوم
        $this->dateFrom = Carbon::now()->startOfYear()->format('Y-m-d');
        $this->dateTo = Carbon::now()->format('Y-m-d');

        $user = Auth::user();
        $this->filterDepartment = $user->view == 1 ? '' : $user->department_id;
        $this->filterMosque = $user->view == 3 ? $user->mosque_id : '';
    }

    public function updatedSelectAll($value): void
    {
        if ($value) {
            $this->selected = $this->getNomineesQuery()->pluck('id')->map(fn($id) => (string)$id)->toArray();
        } else {
            $this->selected = [];
        }
    }

    /*
    |--------------------------------------------------------------------------
    | بناء استعلام ترشيح الأيتام
    |--------------------------------------------------------------------------
    */
    protected function buildOrphansQuery()
    {
        $query = Orphan::query()->with(['department', 'mosque']);

        // 1. استبعاد المضافين بالفعل في نفس هذه المساعدة (منع التكرار)
        $alreadyInAid = AidBeneficiary::where('aid_id', $this->aid->id)
            ->where('beneficiary_type', 'orphan')
            ->pluck('beneficiary_id');
        $query->whereNotIn('orphan.id', $alreadyInAid);

        // 2. استبعاد الأيتام الموجودين في جدول الكفالات
$query->whereNotExists(function ($q) {
    $q->select(DB::raw(1))
        ->from('guarantees')
        ->whereColumn('guarantees.ssn', 'orphan.SSN');
});

        // 2. تطبيق قاعدة منع الاستفادة خلال الفترة المحددة للمساعدات "المنفذة فقط" (executed)
        if ($this->excludeBeneficiaries) {
            if ($this->checkMode === 'individual') {
                // الفحص الفردي: هل استفاد اليتيم نفسه في مساعدة منفذة؟
                $query->whereNotExists(function ($q) {
                    $q->select(DB::raw(1))
                        ->from('aid_beneficiaries')
                        ->join('aids', 'aids.id', '=', 'aid_beneficiaries.aid_id')
                        ->where('aids.status', 'executed')
                        ->whereBetween('aids.date_execution', [$this->dateFrom, $this->dateTo])
                        ->whereRaw('aid_beneficiaries.ssn = orphan.SSN');
                });
            } else {
                // الفحص العائلي: فحص (اليتيم نفسه + الأم m_ssn + الإخوة من نفس الأم)
                // أ) اليتيم نفسه استفاد
                $query->whereNotExists(function ($q) {
                    $q->select(DB::raw(1))
                        ->from('aid_beneficiaries')
                        ->join('aids', 'aids.id', '=', 'aid_beneficiaries.aid_id')
                        ->where('aids.status', 'executed')
                        ->whereBetween('aids.date_execution', [$this->dateFrom, $this->dateTo])
                        ->whereRaw('aid_beneficiaries.ssn = orphan.SSN');
                });

                // ب) الأم استفادت (مباشرة بهويتها)
                $query->where(function ($motherQuery) {
                    $motherQuery->whereNull('orphan.m_ssn')
                        ->orWhereNotExists(function ($q) {
                            $q->select(DB::raw(1))
                                ->from('aid_beneficiaries')
                                ->join('aids', 'aids.id', '=', 'aid_beneficiaries.aid_id')
                                ->where('aids.status', 'executed')
                                ->whereBetween('aids.date_execution', [$this->dateFrom, $this->dateTo])
                                ->whereRaw('aid_beneficiaries.ssn = orphan.m_ssn');
                        });
                });

                // ج) أحد الإخوة الذين يحملون نفس هوية الأم (m_ssn) قد استفاد
                $query->where(function ($siblingsQuery) {
                    $siblingsQuery->whereNull('orphan.m_ssn')
                        ->orWhereNotExists(function ($q) {
                            $q->select(DB::raw(1))
                                ->from('aid_beneficiaries')
                                ->join('aids', 'aids.id', '=', 'aid_beneficiaries.aid_id')
                                ->join('orphan as siblings', 'siblings.SSN', '=', 'aid_beneficiaries.ssn')
                                ->where('aids.status', 'executed')
                                ->whereBetween('aids.date_execution', [$this->dateFrom, $this->dateTo])
                                ->whereRaw('siblings.m_ssn = orphan.m_ssn')
                                ->whereRaw('siblings.id != orphan.id');
                        });
                });
            }
        }

        // فلاتر البحث
        if (!empty($this->search)) {
            $term = $this->search;
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', '%' . $term . '%')
                  ->orWhere('SSN', 'like', '%' . $term . '%')
                  ->orWhere('m_name', 'like', '%' . $term . '%')
                  ->orWhere('m_ssn', 'like', '%' . $term . '%');
            });
        }

        if (!empty($this->filterDepartment)) {
            $query->where('department_id', $this->filterDepartment);
        }

        if (!empty($this->filterMosque)) {
            $query->where('mosque_id', $this->filterMosque);
        }

        if (! empty($this->filterMinAge)) {
            $query->whereDate('barth', '<=', Carbon::today()->subYears($this->filterMinAge));
        }

        if (! empty($this->filterMaxAge)) {
            // العمر أصغر من أو يساوي العمر الأعلى
            $query->whereDate('barth', '>=', Carbon::today()->subYears($this->filterMaxAge + 1)->addDay());
        }

        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | بناء استعلام ترشيح الأرامل
    |--------------------------------------------------------------------------
    */
    protected function buildWidowsQuery()
    {
        $query = widow::query()->with(['department', 'mosque']);

        // 1. استبعاد المضافين مسبقًا في نفس المساعدة
        $alreadyInAid = AidBeneficiary::where('aid_id', $this->aid->id)
            ->where('beneficiary_type', 'widow')
            ->pluck('beneficiary_id');
        $query->whereNotIn('widows.id', $alreadyInAid);

        // 2. تطبيق منع الاستفادة خلال الفترة للمساعدات المنفذة
        if ($this->excludeBeneficiaries) {
            if ($this->checkMode === 'individual') {
                $query->whereNotExists(function ($q) {
                    $q->select(DB::raw(1))
                        ->from('aid_beneficiaries')
                        ->join('aids', 'aids.id', '=', 'aid_beneficiaries.aid_id')
                        ->where('aids.status', 'executed')
                        ->whereBetween('aids.date_execution', [$this->dateFrom, $this->dateTo])
                        ->whereRaw('aid_beneficiaries.ssn = widows.ssn');
                });
            } else {
                // الفحص العائلي: الأرملة نفسها + أبناؤها الأيتام المسجلين بهويتها
                $query->whereNotExists(function ($q) {
                    $q->select(DB::raw(1))
                        ->from('aid_beneficiaries')
                        ->join('aids', 'aids.id', '=', 'aid_beneficiaries.aid_id')
                        ->where('aids.status', 'executed')
                        ->whereBetween('aids.date_execution', [$this->dateFrom, $this->dateTo])
                        ->whereRaw('aid_beneficiaries.ssn = widows.ssn');
                });

                $query->whereNotExists(function ($q) {
                    $q->select(DB::raw(1))
                        ->from('aid_beneficiaries')
                        ->join('aids', 'aids.id', '=', 'aid_beneficiaries.aid_id')
                        ->join('orphan', 'orphan.SSN', '=', 'aid_beneficiaries.ssn')
                        ->where('aids.status', 'executed')
                        ->whereBetween('aids.date_execution', [$this->dateFrom, $this->dateTo])
                        ->whereRaw('orphan.m_ssn = widows.ssn');
                });
            }
        }

        // فلاتر البحث
        if (!empty($this->search)) {
            $term = $this->search;
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', '%' . $term . '%')
                  ->orWhere('ssn', 'like', '%' . $term . '%')
                  ->orWhere('husband', 'like', '%' . $term . '%');
            });
        }

        if (!empty($this->filterDepartment)) {
            $query->where('department_id', $this->filterDepartment);
        }

        if (!empty($this->filterMosque)) {
            $query->where('mosque_id', $this->filterMosque);
        }

        if ($this->filterMinOrphans !== '') {
            $query->where('orphan_count', '>=', $this->filterMinOrphans);
        }

        if ($this->filterMaxOrphans !== '') {
            $query->where('orphan_count', '<=', $this->filterMaxOrphans);
        }

        return $query;
    }

    protected function getNomineesQuery()
    {
        return $this->aid->beneficiary_type === 'orphan'
            ? $this->buildOrphansQuery()
            : $this->buildWidowsQuery();
    }

    /*
    |--------------------------------------------------------------------------
    | إضافة المرشحين المحددين للمساعدة دفعة واحدة
    |--------------------------------------------------------------------------
    */
    public function addSelectedToAid(): void
    {
        if (empty($this->selected)) {
            session()->flash('error', 'الرجاء تحديد مستفيد واحد على الأقل.');
            return;
        }

        $now = now();
        $insertData = [];

        if ($this->aid->beneficiary_type === 'orphan') {
            $orphans = Orphan::whereIn('id', $this->selected)->get();
            foreach ($orphans as $orphan) {
                $insertData[] = [
                    'aid_id' => $this->aid->id,
                    'beneficiary_type' => 'orphan',
                    'beneficiary_id' => $orphan->id,
                    'ssn' => $orphan->SSN,
                    'name' => $orphan->name,
                    'mobile' => $orphan->mobile ?? $orphan->mobile2,
                    'mosque' => $orphan->mosque->name ?? null,
                    'department' => $orphan->department->name ?? null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        } else {
            $widows = widow::whereIn('id', $this->selected)->get();
            foreach ($widows as $widow) {
                $insertData[] = [
                    'aid_id' => $this->aid->id,
                    'beneficiary_type' => 'widow',
                    'beneficiary_id' => $widow->id,
                    'ssn' => $widow->ssn,
                    'name' => $widow->name,
                    'mobile' => $widow->mobile,
                    'wallet_number' => $widow->gaz_mobile,
                    'mosque' => $widow->mosque->name ?? null,
                    'department' => $widow->department->name ?? null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        if (!empty($insertData)) {
            // استخدام insertOrIgnore لضمان منع التكرار البرمجي وقاعدة البيانات
            AidBeneficiary::insertOrIgnore($insertData);
            session()->flash('message', 'تمت إضافة ' . count($insertData) . ' مستفيد إلى المساعدة بنجاح.');
        }

        $this->selected = [];
        $this->selectAll = false;
        $this->resetPage();
    }

    public function render()
    {
        $nominees = $this->getNomineesQuery()->paginate(25);

        return view('livewire.aid.nomination', [
            'nominees' => $nominees,
            'departments' => Department::all(),
            'mosques' => $this->filterDepartment ? Mosque::where('department_id', $this->filterDepartment)->get() : Mosque::all(),
        ]);
    }
}
