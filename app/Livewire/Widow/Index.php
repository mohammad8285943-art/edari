<?php

namespace App\Livewire\Widow;

use App\Models\Department;
use App\Models\Mosque;
use App\Models\Orphan;
use App\Models\widow as Widow;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use App\Exports\WidowsExport;
use Maatwebsite\Excel\Facades\Excel;

#[Layout('layout.app', ['title' => 'إدارة الأرامل'])]
class Index extends Component
{
    use WithPagination;

    // ==== حقول الفلترة والبحث (قبل التطبيق) ====
    public $searchName = '';

    public $searchSsn = '';

    public $searchHusband = '';

    public $searchHusbandSsn = '';

    public $filterDepartment = '';

    public $filterMosque = '';

    public $filterOrphanStatus = ''; // 'with', 'without', ''

    // ==== الحقول المطبَّقة فعليًا على الاستعلام ====
    public $appliedSearchName = '';

    public $appliedSearchSsn = '';

    public $appliedSearchHusband = '';

    public $appliedSearchHusbandSsn = '';

    public $appliedDepartment = '';

    public $appliedMosque = '';

    public $appliedOrphanStatus = '';

    // ==== حالة مودال الأرملة (إضافة / تعديل) ====
    public $showModal = false;

    public $editingId = null;

    // ==== حقول نموذج الأرملة ====
    public $form_name = '';

    public $form_ssn = '';

    public $form_mobile = '';

    public $form_job = '';

    public $form_husband = '';

    public $form_h_ssn = '';

    public $form_date_death = '';

    public $form_address = '';

    public $form_h_job = '';

    public $form_orphan_count = 0;

    public $form_mosque_id = '';

    public $form_department_id = '';

    public $form_gaz_mobile = '';

    // ==== حالة مودال عرض الأيتام ====
    public $showOrphansModal = false;

    public $selectedWidow = null;

    public $widowOrphans = [];

    // ==== تأكيد الحذف ====
    public $confirmingDeleteId = null;

    public function mount(): void
    {
        $user = Auth::user();

        $this->filterDepartment = $user->view == 1 ? '' : $user->department_id;
        $this->filterMosque = $user->view == 3 ? $user->mosque_id : '';

        $this->applyFilter();
    }

    /*
    |--------------------------------------------------------------------------
    | الفلترة والبحث
    |--------------------------------------------------------------------------
    */

    public function applyFilter(): void
    {
        $user = Auth::user();

        $this->appliedSearchName = $this->searchName;
        $this->appliedSearchSsn = $this->searchSsn;
        $this->appliedSearchHusband = $this->searchHusband;
        $this->appliedSearchHusbandSsn = $this->searchHusbandSsn;
        $this->appliedOrphanStatus = $this->filterOrphanStatus;

        $this->appliedDepartment = $user->view == 1 ? $this->filterDepartment : $user->department_id;
        $this->appliedMosque = $user->view == 3 ? $user->mosque_id : $this->filterMosque;

        $this->resetPage();
    }

    public function resetFilter(): void
    {
        $this->searchName = '';
        $this->searchSsn = '';
        $this->searchHusband = '';
        $this->searchHusbandSsn = '';
        $this->filterOrphanStatus = '';
        $this->filterDepartment = Auth::user()->view == 1 ? '' : Auth::user()->department_id;
        $this->filterMosque = Auth::user()->view == 3 ? Auth::user()->mosque_id : '';

        $this->applyFilter();
    }

    /**
     * بناء استعلام الأرامل الأساسي مع استبعاد المحذوفات تلقائياً (SoftDeletes).
     */
    protected function buildWidowsQuery()
    {
        $query = Widow::query()
            ->with(['mosque', 'department']);

        if (! empty($this->appliedSearchName)) {
            $query->where('name', 'like', '%'.$this->appliedSearchName.'%');
        }

        if (! empty($this->appliedSearchSsn)) {
            $query->where('ssn', 'like', '%'.$this->appliedSearchSsn.'%');
        }

        if (! empty($this->appliedSearchHusband)) {
            $query->where('husband', 'like', '%'.$this->appliedSearchHusband.'%');
        }

        if (! empty($this->appliedSearchHusbandSsn)) {
            $query->where('h_ssn', 'like', '%'.$this->appliedSearchHusbandSsn.'%');
        }

        if ($this->appliedOrphanStatus === 'with') {
            $query->where('orphan_count', '>', 0);
        } elseif ($this->appliedOrphanStatus === 'without') {
            $query->where('orphan_count', '=', 0);
        }

        if (! empty($this->appliedMosque)) {
            $query->where('mosque_id', $this->appliedMosque);
        } elseif (! empty($this->appliedDepartment)) {
            $query->where('department_id', $this->appliedDepartment);
        }

        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | قوائم الأقسام والمساجد
    |--------------------------------------------------------------------------
    */

    protected function getDepartments()
    {
        $user = Auth::user();

        if ($user->view == 1) {
            return Department::all();
        }

        return collect();
    }

    protected function getMosques()
    {
        $user = Auth::user();

        if ($user->view == 3) {
            return collect();
        } elseif ($user->view == 2) {
            return Mosque::where('department_id', $user->department_id)->get();
        }

        return Mosque::all();
    }

    /*
    |--------------------------------------------------------------------------
    | مودال الأيتام التابعين للأرملة (بدون N+1)
    |--------------------------------------------------------------------------
    */

    public function showOrphans($widowId): void
    {
        $widow = Widow::findOrFail($widowId);
        $this->selectedWidow = $widow;

        // استعلام مباشر بالاعتماد على m_ssn = widow.ssn
        $this->widowOrphans = Orphan::where('m_ssn', $widow->ssn)->get();

        $this->showOrphansModal = true;
    }

    public function closeOrphansModal(): void
    {
        $this->showOrphansModal = false;
        $this->selectedWidow = null;
        $this->widowOrphans = [];
    }

    /*
    |--------------------------------------------------------------------------
    | المودال: إضافة / تعديل أرملة
    |--------------------------------------------------------------------------
    */

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->editingId = null;
        $this->showModal = true;
    }

    public function openEditModal($id): void
    {
        $widow = Widow::findOrFail($id);

        $this->editingId = $widow->id;
        $this->form_name = $widow->name;
        $this->form_ssn = $widow->ssn;
        $this->form_mobile = $widow->mobile;
        $this->form_job = $widow->job;
        $this->form_husband = $widow->husband;
        $this->form_h_ssn = $widow->h_ssn;
        $this->form_date_death = $widow->date_death;
        $this->form_address = $widow->address;
        $this->form_h_job = $widow->h_job;
        $this->form_orphan_count = $widow->orphan_count;
        $this->form_mosque_id = $widow->mosque_id;
        $this->form_department_id = $widow->department_id;
        $this->form_gaz_mobile = $widow->gaz_mobile;

        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    protected function resetForm(): void
    {
        $this->editingId = null;
        $this->form_name = '';
        $this->form_ssn = '';
        $this->form_mobile = '';
        $this->form_job = '';
        $this->form_husband = '';
        $this->form_h_ssn = '';
        $this->form_date_death = '';
        $this->form_address = '';
        $this->form_h_job = '';
        $this->form_orphan_count = 0;
        $this->form_mosque_id = '';
        $this->form_department_id = '';
        $this->form_gaz_mobile = '';

        $this->resetErrorBag();
        $this->resetValidation();
    }

    protected function rules(): array
    {
        $uniqueSsnRule = 'unique:widows,ssn';
        if ($this->editingId) {
            $uniqueSsnRule .= ',' . $this->editingId;
        }

        return [
            'form_name' => ['required', 'string', 'max:255'],
            'form_ssn' => ['required', 'numeric', $uniqueSsnRule],
            'form_mobile' => ['nullable', 'string', 'max:15'],
            'form_job' => ['nullable', 'string', 'max:255'],
            'form_husband' => ['required', 'string', 'max:255'],
            'form_h_ssn' => ['nullable', 'numeric'],
            'form_date_death' => ['nullable', 'string', 'max:255'],
            'form_address' => ['nullable', 'string', 'max:255'],
            'form_h_job' => ['nullable', 'string', 'max:255'],
            'form_orphan_count' => ['required', 'integer', 'min:0'],
            'form_department_id' => ['required', 'exists:department,id'],
            'form_mosque_id' => ['required', 'exists:mosque,id'],
            'form_gaz_mobile' => ['nullable', 'string', 'max:15'],
        ];
    }

    protected function messages(): array
    {
        return [
            'form_name.required' => 'اسم الأرملة مطلوب.',
            'form_ssn.required' => 'رقم هوية الأرملة مطلوب.',
            'form_ssn.numeric' => 'رقم هوية الأرملة يجب أن يكون أرقاماً فقط.',
            'form_ssn.unique' => 'رقم الهوية مسجل مسبقاً لأرملة أخرى.',
            'form_husband.required' => 'اسم الزوج مطلوب.',
            'form_h_ssn.numeric' => 'رقم هوية الزوج يجب أن يكون أرقاماً فقط.',
            'form_orphan_count.required' => 'عدد الأيتام مطلوب.',
            'form_orphan_count.integer' => 'عدد الأيتام يجب أن يكون عدداً صحيحاً.',
            'form_orphan_count.min' => 'عدد الأيتام لا يمكن أن يكون سالباً.',
            'form_department_id.required' => 'الرجاء اختيار القسم.',
            'form_department_id.exists' => 'القسم المختار غير صحيح.',
            'form_mosque_id.required' => 'الرجاء اختيار المسجد.',
            'form_mosque_id.exists' => 'المسجد المختار غير صحيح.',
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name' => $this->form_name,
            'ssn' => $this->form_ssn,
            'mobile' => $this->form_mobile,
            'job' => $this->form_job,
            'husband' => $this->form_husband,
            'h_ssn' => $this->form_h_ssn,
            'date_death' => $this->form_date_death,
            'address' => $this->form_address,
            'h_job' => $this->form_h_job,
            'orphan_count' => $this->form_orphan_count,
            'mosque_id' => $this->form_mosque_id,
            'department_id' => $this->form_department_id,
            'gaz_mobile' => $this->form_gaz_mobile,
        ];

        if ($this->editingId) {
            $widow = Widow::findOrFail($this->editingId);
            $widow->update($data);
            session()->flash('message', 'تم تحديث بيانات الأرملة بنجاح.');
        } else {
            Widow::create($data);
            session()->flash('message', 'تم إضافة الأرملة بنجاح.');
        }

        $this->closeModal();
        $this->resetPage();
    }

    /*
    |--------------------------------------------------------------------------
    | الحذف (Soft Delete)
    |--------------------------------------------------------------------------
    */

    public function confirmDelete($id): void
    {
        $this->confirmingDeleteId = $id;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDeleteId = null;
    }

    public function deleteWidow(): void
    {
        if ($this->confirmingDeleteId) {
            $widow = Widow::findOrFail($this->confirmingDeleteId);
            $widow->delete(); // Soft Delete
            session()->flash('message', 'تم حذف الأرملة بنجاح.');
            $this->confirmingDeleteId = null;
        }
    }
/*
    |--------------------------------------------------------------------------
    | تصدير البيانات إلى Excel
    |--------------------------------------------------------------------------
*/
    public function exportExcel()
{
    $filters = [
        'searchName' => $this->appliedSearchName,
        'searchSsn' => $this->appliedSearchSsn,
        'searchHusband' => $this->appliedSearchHusband,
        'searchHusbandSsn' => $this->appliedSearchHusbandSsn,
        'orphanStatus' => $this->appliedOrphanStatus,
        'department_id' => $this->appliedDepartment,
        'mosque_id' => $this->appliedMosque,
    ];

    return Excel::download(
        new WidowsExport($filters),
        'كشف الارامل_' . now()->format('Y-m-d_H-i') . '.xlsx'
    );
}

    /*
    |--------------------------------------------------------------------------
    | العرض
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        $baseQuery = $this->buildWidowsQuery();

        // حساب الإحصائيات بناءً على النتائج المفلترة
        $totalWidowsCount = (clone $baseQuery)->count();
        $withOrphansCount = (clone $baseQuery)->where('orphan_count', '>', 0)->count();
        $withoutOrphansCount = (clone $baseQuery)->where('orphan_count', '=', 0)->count();
        $totalOrphansCount = (clone $baseQuery)->sum('orphan_count');

        $widowsPaginated = (clone $baseQuery)
            ->latest('id')
            ->paginate(15);

        return view('livewire.widow.index', [
            'widows' => $widowsPaginated,
            'totalWidowsCount' => $totalWidowsCount,
            'withOrphansCount' => $withOrphansCount,
            'withoutOrphansCount' => $withoutOrphansCount,
            'totalOrphansCount' => $totalOrphansCount,
            'departments' => $this->getDepartments(),
            'mosques' => $this->getMosques(),
        ]);
    }
}
