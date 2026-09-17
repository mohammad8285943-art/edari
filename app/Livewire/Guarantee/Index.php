<?php

namespace App\Livewire\Guarantee;

use App\Models\Department;
use App\Models\guarantee as Guarantee;
use App\Models\Mosque;
use App\Models\Orphan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GuaranteesExport;

#[Layout('layout.app', ['title' => 'إدارة الكفالات'])]
class Index extends Component
{
    use WithPagination;

    // ==== حقول الفلترة (قبل التطبيق) ====
    public $search = '';

    public $filterGuarantor = '';

    public $filterStatus = '';

    public $filterDepartment = '';

    public $filterMosque = '';

    // ==== الحقول المطبَّقة فعليًا على الاستعلام ====
    public $appliedSearch = '';

    public $appliedGuarantor = '';

    public $appliedStatus = '';

    public $appliedDepartment = '';

    public $appliedMosque = '';

    public $filterOrphanStatus = '';

    public $appliedOrphanStatus = '';

    // ==== حالة المودال (إضافة / تعديل) ====
    public $showModal = false;

    public $editingId = null;

    // ==== حقول نموذج الكفالة ====
    public $form_ssn = '';

    public $form_name = '';

    public $form_mobile = '';

    public $form_mosque_id = '';

    public $form_department_id = '';

    public $form_home = '';

    public $form_guarantor = '';

    public $form_guarantor_start = '';

    public $form_guarantor_end = '';

    public $form_amount = '';

    public $form_status = '';

    // ==== معاينة اليتيم أثناء إدخال SSN ====
    public $orphanPreviewName = null;

    public $orphanNotFound = false;

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

        $this->appliedSearch = $this->search;
        $this->appliedGuarantor = $this->filterGuarantor;
        $this->appliedStatus = $this->filterStatus;
        $this->appliedOrphanStatus = $this->filterOrphanStatus;

        $this->appliedDepartment = $user->view == 1
            ? $this->filterDepartment
            : $user->department_id;

        $this->appliedMosque = $user->view == 3
            ? $user->mosque_id
            : $this->filterMosque;

        $this->resetPage();
    }

    public function resetFilter(): void
    {
        $this->search = '';
        $this->filterGuarantor = '';
        $this->filterStatus = '';
        $this->filterOrphanStatus = '';

        $this->filterDepartment = Auth::user()->view == 1
            ? ''
            : Auth::user()->department_id;

        $this->filterMosque = Auth::user()->view == 3
            ? Auth::user()->mosque_id
            : '';

        $this->applyFilter();
    }

    /**
     * بناء استعلام الكفالات الأساسي.
     * مهم جدًا: لا نستخدم whereHas('orphan') كشرط رئيسي، حتى لا تختفي
     * الكفالات التي لا يوجد لها يتيم مسجل.
     */
    protected function buildGuaranteesQuery()
    {
        $query = Guarantee::query()
            ->leftJoin('orphan', 'guarantees.ssn', '=', 'orphan.ssn')
            ->with(['mosque', 'department'])
            ->select([
                'guarantees.*',
                'orphan.name as orphan_name',
            ]);

        if (! empty($this->appliedSearch)) {
            $term = $this->appliedSearch;

            $query->where(function ($q) use ($term) {
                $q->where('guarantees.ssn', 'like', '%'.$term.'%')
                    ->orWhere('guarantees.guarantor', 'like', '%'.$term.'%')
                    ->orWhere('orphan.name', 'like', '%'.$term.'%');
            });
        }

        if (! empty($this->appliedGuarantor)) {
            $query->where('guarantees.guarantor', $this->appliedGuarantor);
        }

        if (! empty($this->appliedStatus)) {
            $query->where('guarantees.status', $this->appliedStatus);
        }
        if ($this->appliedOrphanStatus === 'registered') {
            $query->whereNotNull('orphan.id');
        }

        if ($this->appliedOrphanStatus === 'not_registered') {
            $query->whereNull('orphan.id');
        }

        if (! empty($this->appliedMosque)) {
            $query->where('guarantees.mosque_id', $this->appliedMosque);
        } elseif (! empty($this->appliedDepartment)) {
            $query->where('guarantees.department_id', $this->appliedDepartment);
        }

        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | قوائم الفلاتر (كافلين / حالات / أقسام / مساجد)
    |--------------------------------------------------------------------------
    */

    protected function getGuarantors()
    {
        return Guarantee::whereNotNull('guarantor')
            ->where('guarantor', '!=', '')
            ->distinct()
            ->orderBy('guarantor')
            ->pluck('guarantor');
    }

    protected function getStatuses()
    {
        return Guarantee::whereNotNull('status')
            ->where('status', '!=', '')
            ->distinct()
            ->orderBy('status')
            ->pluck('status');
    }

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
    | معاينة اليتيم عند إدخال SSN داخل المودال
    |--------------------------------------------------------------------------
    */

    public function updatedFormSsn($value): void
    {
        $this->checkOrphan($value);
    }

    protected function checkOrphan($ssn): void
    {
        if (empty($ssn)) {
            $this->orphanPreviewName = null;
            $this->orphanNotFound = false;

            return;
        }

        $orphan = Orphan::where('SSN', $ssn)->first();

        if ($orphan) {
            $this->orphanPreviewName = $orphan->name;
            $this->orphanNotFound = false;
        } else {
            $this->orphanPreviewName = null;
            $this->orphanNotFound = true;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | المودال: إضافة / تعديل
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
        $guarantee = Guarantee::findOrFail($id);

        $this->editingId = $guarantee->id;
        $this->form_ssn = $guarantee->ssn;
        $this->form_name = $guarantee->name;
        $this->form_mobile = $guarantee->mobile;
        $this->form_mosque_id = $guarantee->mosque_id;
        $this->form_department_id = $guarantee->department_id;
        $this->form_home = $guarantee->home;
        $this->form_guarantor = $guarantee->guarantor;
        $this->form_guarantor_start = $guarantee->guarantor_start
            ? Carbon::parse($guarantee->guarantor_start)->format('Y-m-d')
            : '';
        $this->form_guarantor_end = $guarantee->guarantor_end
            ? Carbon::parse($guarantee->guarantor_end)->format('Y-m-d')
            : '';
        $this->form_amount = $guarantee->amount;
        $this->form_status = $guarantee->status;

        $this->checkOrphan($this->form_ssn);

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
        $this->form_ssn = '';
        $this->form_name = '';
        $this->form_mobile = '';
        $this->form_mosque_id = '';
        $this->form_department_id = '';
        $this->form_home = '';
        $this->form_guarantor = '';
        $this->form_guarantor_start = '';
        $this->form_guarantor_end = '';
        $this->form_amount = '';
        $this->form_status = '';
        $this->orphanPreviewName = null;
        $this->orphanNotFound = false;

        $this->resetErrorBag();
        $this->resetValidation();
    }

    protected function rules(): array
    {
        return [
            'form_ssn' => ['required', 'numeric'],
            'form_name' => ['nullable', 'string', 'max:255'],
            'form_mobile' => ['nullable', 'string', 'max:10'],
            'form_department_id' => ['required', 'exists:department,id'],
            'form_mosque_id' => ['required', 'exists:mosque,id'],
            'form_home' => ['nullable', 'string', 'max:255'],
            'form_guarantor' => ['required', 'string', 'max:255'],
            'form_guarantor_start' => ['date'],
            'form_guarantor_end' => ['nullable', 'date', 'after_or_equal:form_guarantor_start'],
            'form_amount' => ['numeric', 'min:0'],
        ];
    }
    public function exportExcel()
{
    return Excel::download(
        new GuaranteesExport(
            $this->appliedSearch,
            $this->appliedGuarantor,
            $this->appliedStatus,
            $this->appliedDepartment,
            $this->appliedMosque,
            $this->appliedOrphanStatus
        ),
        'الكفالات-' . now()->format('Y-m-d-H-i') . '.xlsx'
    );
}

    protected function messages(): array
    {
        return [
            'form_ssn.required' => 'رقم هوية اليتيم (SSN) مطلوب.',
            'form_ssn.numeric' => 'رقم الهوية يجب أن يكون أرقامًا فقط.',
            'form_name.nullable' => 'اسم اليتيم يجب أن يكون نصًا.',
            'form_department_id.required' => 'الرجاء اختيار القسم.',
            'form_department_id.exists' => 'القسم المختار غير صحيح.',
            'form_mosque_id.required' => 'الرجاء اختيار المسجد.',
            'form_mosque_id.exists' => 'المسجد المختار غير صحيح.',
            'form_guarantor.required' => 'اسم الكافل مطلوب.',
            'form_guarantor_start.required' => 'تاريخ بداية الكفالة مطلوب.',
            'form_guarantor_start.date' => 'تاريخ بداية الكفالة غير صحيح.',
            'form_guarantor_end.date' => 'تاريخ نهاية الكفالة غير صحيح.',
            'form_guarantor_end.after_or_equal' => 'تاريخ النهاية يجب أن يكون بعد أو يساوي تاريخ البداية.',
            'form_amount.required' => 'قيمة الكفالة مطلوبة.',
            'form_amount.numeric' => 'قيمة الكفالة يجب أن تكون رقمًا.',
            'form_amount.min' => 'قيمة الكفالة لا يمكن أن تكون سالبة.',
            'form_status.required' => 'الرجاء تحديد حالة الكفالة.',
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'ssn' => $this->form_ssn,
            'name' => $this->form_name,
            'mobile' => $this->form_mobile,
            'mosque_id' => $this->form_mosque_id,
            'department_id' => $this->form_department_id,
            'home' => $this->form_home,
            'guarantor' => $this->form_guarantor,
            'guarantor_start' => $this->form_guarantor_start,
            'guarantor_end' => $this->form_guarantor_end ?: null,
            'amount' => $this->form_amount,
            'status' => $this->form_status,
        ];

        if ($this->editingId) {
            $guarantee = Guarantee::findOrFail($this->editingId);
            $guarantee->update($data);
            session()->flash('message', 'تم تحديث بيانات الكفالة بنجاح.');
        } else {
            Guarantee::create($data);
            session()->flash('message', 'تم إضافة الكفالة بنجاح.');
        }

        $this->closeModal();
        $this->resetPage();
    }

    /*
    |--------------------------------------------------------------------------
    | الحذف
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

    public function deleteGuarantee(): void
    {
        if ($this->confirmingDeleteId) {
            Guarantee::where('id', $this->confirmingDeleteId)->delete();
            session()->flash('message', 'تم حذف الكفالة بنجاح.');
            $this->confirmingDeleteId = null;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | العرض
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        $baseQuery = $this->buildGuaranteesQuery();

        $totalCount = (clone $baseQuery)
            ->count('guarantees.id');

        // عدد المستفيدين (الهويات) بدون تكرار
        $uniqueBeneficiariesCount = (clone $baseQuery)
            ->distinct('guarantees.ssn')
            ->count('guarantees.ssn');

        $sumAmount = (clone $baseQuery)
            ->sum('guarantees.amount');

        $noOrphanCount = (clone $baseQuery)
            ->whereNull('orphan.id')
            ->count('guarantees.id');

        $guaranteesPaginated = (clone $baseQuery)
            ->latest('guarantees.id')
            ->paginate(15);

        return view('livewire.guarantee.index', [
            'guarantees' => $guaranteesPaginated,
            'totalCount' => $totalCount,
            'uniqueBeneficiariesCount' => $uniqueBeneficiariesCount, // تمرير المتغير للفيو
            'sumAmount' => $sumAmount,
            'noOrphanCount' => $noOrphanCount,
            'departments' => $this->getDepartments(),
            'mosques' => $this->getMosques(),
            'guarantorsList' => $this->getGuarantors(),
        ]);
    }
}
