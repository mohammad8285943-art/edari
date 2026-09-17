<?php

namespace App\Livewire\Aid;

use App\Models\Aid;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layout.app', ['title' => 'إدارة المساعدات'])]
class Index extends Component
{
    use WithPagination;

    // فلاتر البحث
    public $search = '';
    public $filterType = '';
    public $filterBeneficiaryType = '';
    public $filterStatus = '';

    public $appliedSearch = '';
    public $appliedType = '';
    public $appliedBeneficiaryType = '';
    public $appliedStatus = '';

    // حالة المودال
    public $showModal = false;
    public $editingId = null;

    // حقول نموذج المساعدة
    public $form_name = '';
    public $form_type = '';
    public $form_beneficiary_type = 'orphan';
    public $form_amount = '';

    public $form_count = 0;
    public $form_donor = '';
    public $form_execution_place = '';
    public $form_date_execution = '';
    public $form_date_nomination = '';
    public $form_status = 'nominated';

    public $confirmingDeleteId = null;

    public function mount(): void
    {
        $this->form_date_execution = null;
        $this->form_date_nomination = null;
        $this->applyFilter();
    }

    public function applyFilter(): void
    {
        $this->appliedSearch = $this->search;
        $this->appliedType = $this->filterType;
        $this->appliedBeneficiaryType = $this->filterBeneficiaryType;
        $this->appliedStatus = $this->filterStatus;

        $this->resetPage();
    }

    public function resetFilter(): void
    {
        $this->search = '';
        $this->filterType = '';
        $this->filterBeneficiaryType = '';
        $this->filterStatus = '';

        $this->applyFilter();
    }

    protected function buildAidsQuery()
    {
        $query = Aid::query()
            ->withCount('beneficiaries');

        if (!empty($this->appliedSearch)) {
            $term = $this->appliedSearch;
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', '%' . $term . '%')
                  ->orWhere('donor', 'like', '%' . $term . '%')
                  ->orWhere('execution_place', 'like', '%' . $term . '%');
            });
        }

        if (!empty($this->appliedType)) {
            $query->where('type', $this->appliedType);
        }

        if (!empty($this->appliedBeneficiaryType)) {
            $query->where('beneficiary_type', $this->appliedBeneficiaryType);
        }

        if (!empty($this->appliedStatus)) {
            $query->where('status', $this->appliedStatus);
        }

        return $query;
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->editingId = null;
        $this->showModal = true;
    }

    public function openEditModal($id): void
    {
        $aid = Aid::findOrFail($id);

        $this->editingId = $aid->id;
        $this->form_name = $aid->name;
        $this->form_type = $aid->type;
        $this->form_beneficiary_type = $aid->beneficiary_type;
        $this->form_amount = $aid->amount;
        $this->form_count = $aid->count;
        $this->form_donor = $aid->donor;
        $this->form_execution_place = $aid->execution_place;
        $this->form_date_execution = $aid->date_execution ? Carbon::parse($aid->date_execution)->format('Y-m-d') : '';
        $this->form_date_nomination = $aid->date_nomination ? Carbon::parse($aid->date_nomination)->format('Y-m-d') : '';
        $this->form_status = $aid->status;
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
        $this->form_type = '';
        $this->form_beneficiary_type = 'orphan';
        $this->form_amount = '';
        $this->form_count = 0;
        $this->form_donor = '';
        $this->form_execution_place = '';
        $this->form_date_execution = null;
        $this->form_date_nomination = null;
        $this->form_status = 'nominated';

        $this->resetErrorBag();
        $this->resetValidation();
    }

    protected function rules(): array
    {
        return [
            'form_name' => ['required', 'string', 'max:255'],
            'form_type' => ['required', 'string', 'max:255'],
            'form_beneficiary_type' => ['required', 'in:orphan,widow'],
            'form_amount' => ['required', 'numeric', 'min:0'],
            'form_count' => ['required', 'integer', 'min:0'],
            'form_donor' => ['nullable', 'string', 'max:255'],
            'form_execution_place' => ['nullable', 'string', 'max:255'],
            'form_date_execution' => ['nullable', 'date'],
            'form_date_nomination' => ['nullable', 'date'],
            'form_status' => ['required', 'in:nominated,executed'],
        ];
    }

    protected function messages(): array
    {
        return [
            'form_name.required' => 'اسم المساعدة مطلوب.',
            'form_type.required' => 'نوع المساعدة مطلوب.',
            'form_beneficiary_type.required' => 'فئة المستفيدين مطلوبة.',
            'form_amount.required' => 'قيمة الفرد مطلوبة.',
            'form_amount.numeric' => 'قيمة الفرد يجب أن تكون رقمًا.',
            'form_count.required' => 'عدد المستفيدين مطلوب.',
            'form_count.integer' => 'عدد المستفيدين يجب أن يكون عددًا صحيحًا.',
            'form_date_execution.required' => 'تاريخ التنفيذ مطلوب.',
            'form_date_nomination.required' => 'تاريخ الترشيح مطلوب.',
            'form_status.required' => 'حالة المساعدة مطلوبة.',
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name' => $this->form_name,
            'type' => $this->form_type,
            'beneficiary_type' => $this->form_beneficiary_type,
            'amount' => $this->form_amount,
            'count' => $this->form_count,
            'donor' => $this->form_donor,
            'execution_place' => $this->form_execution_place,
            'date_execution' => $this->form_date_execution?: null,
            'date_nomination' => $this->form_date_nomination?: null,
            'status' => $this->form_status,
        ];

        if ($this->editingId) {
            $aid = Aid::findOrFail($this->editingId);
            $aid->update($data);
            session()->flash('message', 'تم تحديث بيانات المساعدة بنجاح.');
        } else {
            Aid::create($data);
            session()->flash('message', 'تم إضافة المساعدة بنجاح.');
        }

        $this->closeModal();
        $this->resetPage();
    }

    public function confirmDelete($id): void
    {
        $this->confirmingDeleteId = $id;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDeleteId = null;
    }

    public function deleteAid(): void
    {
        if ($this->confirmingDeleteId) {
            Aid::where('id', $this->confirmingDeleteId)->delete();
            session()->flash('message', 'تم حذف المساعدة بنجاح.');
            $this->confirmingDeleteId = null;
        }
    }

    public function render()
    {
        $baseQuery = $this->buildAidsQuery();

        $totalCount = (clone $baseQuery)->count('aids.id');
        $executedCount = (clone $baseQuery)->where('status', 'executed')->count('aids.id');
        $nominatedCount = (clone $baseQuery)->where('status', 'nominated')->count('aids.id');

        $types = Aid::distinct()->whereNotNull('type')->where('type', '!=', '')->pluck('type');

        $aids = (clone $baseQuery)
            ->latest('id')
            ->paginate(15);

        return view('livewire.aid.index', [
            'aids' => $aids,
            'totalCount' => $totalCount,
            'executedCount' => $executedCount,
            'nominatedCount' => $nominatedCount,
            'types' => $types,
        ]);
    }
}
