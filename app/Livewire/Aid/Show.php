<?php

namespace App\Livewire\Aid;

use App\Exports\BeneficiariesTemplateExport;
use App\Imports\AidBeneficiariesImport;
use App\Models\Aid;
use App\Models\AidBeneficiary;
use App\Models\Department;
use App\Models\Mosque;
use App\Models\Orphan;
use App\Models\widow;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

#[Layout('layout.app', ['title' => 'تفاصيل المساعدة والمستفيدين'])]
class Show extends Component
{
    use WithFileUploads;
    use WithPagination;

    public Aid $aid;

    // بحث وفلاتر
    public $search = '';

    public $filterDepartment = '';

    public $filterMosque = '';

    // مودال الإضافة اليدوية / التعديل
    public $showBeneficiaryModal = false;

    public $editingBeneficiaryId = null;

    // مودال استيراد الإكسل
    public $showImportModal = false;

    public $excelFile;

    // حقول نموذج المستفيد اليدوي
    public $b_type = 'orphan';

    public $b_id = null;

    public $b_ssn = '';

    public $b_name = '';

    public $b_mobile = '';

    public $b_wallet_number = '';

    public $b_mosque = '';

    public $b_department = '';

    // معاينة وتأكيد الحذف
    public $previewName = null;

    public $confirmingDeleteId = null;

    public function mount(Aid $aid): void
    {
        $this->aid = $aid;
        $this->b_type = $aid->beneficiary_type;
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterDepartment(): void
    {
        $this->filterMosque = '';
        $this->resetPage();
    }

    public function updatedFilterMosque(): void
    {
        $this->resetPage();
    }

    public function updatedBSsn($value): void
    {
        $this->findBeneficiaryInfo($value);
    }

    public function updatedBType(): void
    {
        $this->findBeneficiaryInfo($this->b_ssn);
    }

    protected function findBeneficiaryInfo($ssn): void
    {
        if (empty($ssn) || $this->b_type === 'external') {
            return;
        }

        if ($this->b_type === 'orphan') {
            $orphan = Orphan::where('SSN', $ssn)->first();
            if ($orphan) {
                $this->b_id = $orphan->id;
                $this->b_name = $orphan->name;
                $this->b_mobile = $orphan->mobile ?? $orphan->mobile2;
                $this->b_mosque = $orphan->mosque;
                $this->b_department = $orphan->department;
                $this->previewName = $orphan->name;
            } else {
                $this->previewName = null;
            }
        } elseif ($this->b_type === 'widow') {
            $widow = widow::where('ssn', $ssn)->first();
            if ($widow) {
                $this->b_id = $widow->id;
                $this->b_name = $widow->name;
                $this->b_mobile = $widow->mobile;
                $this->b_mosque = $widow->mosque;
                $this->b_department = $widow->department;
                $this->previewName = $widow->name;
            } else {
                $this->previewName = null;
            }
        }
    }

    public function openCreateBeneficiaryModal(): void
    {
        $this->resetBeneficiaryForm();
        $this->b_type = $this->aid->beneficiary_type;
        $this->showBeneficiaryModal = true;
    }

    public function openEditBeneficiaryModal($id): void
    {
        $b = AidBeneficiary::findOrFail($id);
        $this->editingBeneficiaryId = $b->id;
        $this->b_type = $b->beneficiary_type;
        $this->b_id = $b->beneficiary_id;
        $this->b_ssn = $b->ssn;
        $this->b_name = $b->name;
        $this->b_mobile = $b->mobile;
        $this->b_wallet_number = $b->wallet_number;
        $this->b_mosque = $b->mosque;
        $this->b_department = $b->department;
        $this->showBeneficiaryModal = true;
    }

    public function closeBeneficiaryModal(): void
    {
        $this->showBeneficiaryModal = false;
        $this->resetBeneficiaryForm();
    }

    protected function resetBeneficiaryForm(): void
    {
        $this->editingBeneficiaryId = null;
        $this->b_type = $this->aid->beneficiary_type;
        $this->b_id = null;
        $this->b_ssn = '';
        $this->b_name = '';
        $this->b_mobile = '';
        $this->b_wallet_number = '';
        $this->b_mosque = '';
        $this->b_department = '';
        $this->previewName = null;

        $this->resetErrorBag();
        $this->resetValidation();
    }

    // إدارة نافذة استيراد الإكسل
    public function openImportModal(): void
    {
        $this->excelFile = null;
        $this->resetErrorBag();
        $this->resetValidation();
        $this->showImportModal = true;
    }

    public function closeImportModal(): void
    {
        $this->showImportModal = false;
        $this->excelFile = null;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function downloadTemplate()
    {
        return Excel::download(new BeneficiariesTemplateExport, 'beneficiaries_template.xlsx');
    }

    public function importExcel(): void
    {
        $this->validate([
            'excelFile' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:20480'],
        ], [
            'excelFile.required' => 'يرجى اختيار ملف الإكسل.',
            'excelFile.mimes' => 'يجب أن يكون الملف بصيغة xlsx أو xls أو csv.',
            'excelFile.max' => 'حجم الملف لا يجب أن يتجاوز 20 ميجابايت.',
        ]);

        try {
            Excel::import(new AidBeneficiariesImport($this->aid->id, $this->aid->beneficiary_type), $this->excelFile);

            session()->flash('message', 'تم استيراد كشف المستفيدين بنجاح.');
            $this->closeImportModal();
        } catch (\Exception $e) {
            $this->addError('excelFile', 'حدث خطأ أثناء معالجة الملف: '.$e->getMessage());
        }
    }

    protected function rules(): array
    {
        $rules = [
            'b_type' => ['required', 'in:orphan,widow,external'],
            'b_name' => ['required', 'string', 'max:255'],
            'b_mobile' => ['nullable', 'string', 'max:15'],
            'b_wallet_number' => ['nullable', 'string', 'max:50'],
            'b_department' => ['nullable', 'string', 'max:255'],
            'b_mosque' => ['nullable', 'string', 'max:255'],
        ];

        if ($this->editingBeneficiaryId) {
            $rules['b_ssn'] = ['required', 'numeric', 'unique:aid_beneficiaries,ssn,'.$this->editingBeneficiaryId.',id,aid_id,'.$this->aid->id];
        } else {
            $rules['b_ssn'] = ['required', 'numeric', 'unique:aid_beneficiaries,ssn,NULL,id,aid_id,'.$this->aid->id];
        }

        return $rules;
    }

    protected function messages(): array
    {
        return [
            'b_ssn.required' => 'رقم الهوية مطلوب.',
            'b_ssn.numeric' => 'رقم الهوية يجب أن يكون أرقامًا فقط.',
            'b_ssn.unique' => 'هذا المستفيد مضاف مسبقًا في هذه المساعدة.',
            'b_name.required' => 'اسم المستفيد مطلوب.',
        ];
    }

    public function saveBeneficiary(): void
    {
        $this->validate();

        $data = [
            'aid_id' => $this->aid->id,
            'beneficiary_type' => $this->b_type,
            'beneficiary_id' => $this->b_id,
            'ssn' => $this->b_ssn,
            'name' => $this->b_name,
            'mobile' => $this->b_mobile,
            'wallet_number' => $this->b_wallet_number,
            'mosque' => $this->b_mosque ?: null,
            'department' => $this->b_department ?: null,
        ];

        if ($this->editingBeneficiaryId) {
            $b = AidBeneficiary::findOrFail($this->editingBeneficiaryId);
            $b->update($data);
            session()->flash('message', 'تم تحديث بيانات المستفيد بنجاح.');
        } else {
            AidBeneficiary::create($data);
            session()->flash('message', 'تم إضافة المستفيد بنجاح.');
        }

        $this->closeBeneficiaryModal();
    }

    public function confirmDelete($id): void
    {
        $this->confirmingDeleteId = $id;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDeleteId = null;
    }

    public function deleteBeneficiary(): void
    {
        if ($this->confirmingDeleteId) {
            AidBeneficiary::where('id', $this->confirmingDeleteId)->delete();
            session()->flash('message', 'تم إزالة المستفيد من المساعدة بنجاح.');
            $this->confirmingDeleteId = null;
        }
    }

    protected function getDepartmentKeywords(): array
    {
        return [
            1 => 'بكر',
            2 => 'عمر',
            3 => 'عثمان',
            4 => 'خالد',
        ];
    }

    protected function getAvailableDepartments()
    {
        $user = Auth::user();
        if ($user->view == 1) {
            return Department::all();
        }

        return collect();
    }

    protected function getAvailableMosques()
    {
        $user = Auth::user();

        if ($user->view == 3) {
            return collect();
        }

        if ($user->view == 2) {
            return Mosque::where('department_id', $user->department_id)->get();
        }

        if (! empty($this->filterDepartment)) {
            return Mosque::where('department_id', $this->filterDepartment)->get();
        }

        return Mosque::all();
    }

    public function render()
    {
        $user = Auth::user();
        $keywords = $this->getDepartmentKeywords();
        $query = $this->aid->beneficiaries();

        // 1. فلترة الصلاحيات
        if ($user->view == 3) {
            $userMosque = Mosque::find($user->mosque_id);
            if ($userMosque) {
                $query->where('mosque', 'like', '%'.$userMosque->name.'%');
            }
        } elseif ($user->view == 2) {
            $deptKeyword = $keywords[$user->department_id] ?? null;
            if ($deptKeyword) {
                $query->where('department', 'like', '%'.$deptKeyword.'%');
            }
            if (! empty($this->filterMosque)) {
                $mosqueObj = Mosque::find($this->filterMosque);
                if ($mosqueObj) {
                    $query->where('mosque', 'like', '%'.$mosqueObj->name.'%');
                }
            }
        } elseif ($user->view == 1) {
            if (! empty($this->filterDepartment)) {
                $deptKeyword = $keywords[$this->filterDepartment] ?? null;
                if ($deptKeyword) {
                    $query->where('department', 'like', '%'.$deptKeyword.'%');
                }
            }
            if (! empty($this->filterMosque)) {
                $mosqueObj = Mosque::find($this->filterMosque);
                if ($mosqueObj) {
                    $query->where('mosque', 'like', '%'.$mosqueObj->name.'%');
                }
            }
        }

        // 2. البحث النصي
        if (! empty($this->search)) {
            $term = $this->search;
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', '%'.$term.'%')
                    ->orWhere('ssn', 'like', '%'.$term.'%')
                    ->orWhere('mobile', 'like', '%'.$term.'%')
                    ->orWhere('wallet_number', 'like', '%'.$term.'%');
            });
        }

        $beneficiaries = $query->latest()->paginate(25);

        return view('livewire.aid.show', [
            'beneficiaries' => $beneficiaries,
            'departments' => Department::all(),
            'mosques' => Mosque::all(),
            'filterDepartments' => $this->getAvailableDepartments(),
            'filterMosques' => $this->getAvailableMosques(),
        ]);
    }
}
