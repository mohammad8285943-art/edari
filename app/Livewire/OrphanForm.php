<?php

namespace App\Livewire;

use App\Livewire\Forms\OrphanFormRequest;
use App\Models\Department;
use App\Models\Mosque;
use App\Models\Orphan;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layout.app', ['title' => 'الأيتام | إدارة بيانات يتيم'])]
class OrphanForm extends Component
{
    public OrphanFormRequest $form;
    public bool $isEdit = false;

    // جلب الـ ID تلقائياً من الرابط للمشابهة بين التعديل والإضافة
    public function mount(?int $id = null): void
    {
        $user = Auth::user();

        if ($id) {
            $orphan = Orphan::findOrFail($id);
            $this->form->setOrphan($orphan);
            $this->isEdit = true;
        } else {
            // قيم افتراضية عند الإضافة بناء على صلاحيات المستخدم
            if ($user->view == 2 || $user->view == 3) {
                $this->form->department_id = $user->department_id;
            }
            if ($user->view == 3) {
                $this->form->mosque_id = $user->mosque_id;
            }
        }
    }

    public function getDepartmentsProperty()
    {
        return Auth::user()->view == 1 ? Department::all() : Department::where('id', Auth::user()->department_id)->get();
    }

    public function getMosquesProperty()
    {
        $user = Auth::user();
        if ($user->view == 3) {
            return Mosque::where('id', $user->mosque_id)->get();
        } elseif ($user->view == 2) {
            return Mosque::where('department_id', $user->department_id)->get();
        }
        return Mosque::all();
    }

    public function save()
    {
        $this->form->save();

        session()->flash('message', $this->isEdit ? 'تم تحديث بيانات اليتيم بنجاح.' : 'تم إضافة اليتيم بنجاح.');
        
        return $this->redirect(route('orphans.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.orphan-form', [
            'departments' => $this->departments,
            'mosques' => $this->mosques
        ]);
    }
}