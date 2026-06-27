<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layout.app', ['title' => 'إدارة المستخدمين'])]
class UserIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $showDeleted = false;

    // متغيرات التحكم بالبيانات
    public $isEditMode = false;
    public $userId = null;

    // حقول المدخلات
    public $name, $ssn, $username, $email, $password, $password_confirmation, $phone, $whatsapp, $address, $role, $active = 1;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',

            // ssn: مطلوب، أرقام فقط، وبحد أقصى 20 خانة
            'ssn' => 'required|numeric|digits:9',

            // username & email: مع التحقق من عدم التكرار باستثناء المستخدم الحالي عند التعديل
            'username' =>'required|string|max:255|',
            'email' =>'required|email|unique:users,id',

            // password: مطلوب عند الإضافة، واختياري عند التعديل، مع التثبيت من التأكيد (confirmed)
            'password' =>'nullable|confirmed|min:8' ,

            // phone & whatsapp: أرقام فقط واختيارية
            'phone'    => 'nullable|regex:/^0[0-9]{9}$/',
            'whatsapp' => 'nullable|regex:/^0[0-9]{13}$/',

            'address' => 'nullable|string|max:500',
            'role' => 'required|string',

            // active: يجب أن تكون قيمتها إما 0 أو 1 حصراً
            'active' => 'required|in:0,1',
        ];
    }

    protected function messages()
    {
        return [
            'phone.regex' => 'يجب أن يتكون رقم الهاتف من 10 خانات ويبدأ بـ 0.',
            'whatsapp.regex' => 'يجب أن يتكون رقم الواتساب من 14 خانات ويبدأ بـ 0097.',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function toggleDeleted()
    {
        $this->showDeleted = !$this->showDeleted;
        $this->resetPage();
    }

    // تجهيز الفورم للإضافة (يتم استدعاؤها عبر جافا سكريبت)
    public function initCreate()
    {
        $this->resetInputFields();
        $this->isEditMode = false;
    }

    // جلب بيانات المستخدم للتعديل وتنبيه الجافا سكريبت لفتح المودال
    public function initEdit(User $user)
    {
        $this->resetInputFields();
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->ssn = $user->ssn;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->whatsapp = $user->whatsapp;
        $this->address = $user->address;
        $this->role = $user->role;
        $this->active = $user->active;

        $this->isEditMode = true;

        // إرسال إشارة (Browser Event) للجافا سكريبت لفتح المودال فوراً بعد اكتمال جلب البيانات
        $this->dispatch('open-user-modal');
    }

    public function resetInputFields()
    {
        $this->userId = null;
        $this->name = '';
        $this->ssn = '';
        $this->username = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->phone = '';
        $this->whatsapp = '';
        $this->address = '';
        $this->role = '';
        $this->active = 1;
        $this->resetErrorBag();
    }

    public function save()
    {
        $validatedData = $this->validate();

        if ($this->isEditMode) {
            $user = User::findOrFail($this->userId);
            if (empty($this->password)) {
                unset($validatedData['password']);
            } else {
                $validatedData['password'] = bcrypt($this->password);
            }
            $user->update($validatedData);
            session()->flash('message', 'تم تحديث بيانات المستخدم بنجاح.');
        } else {
            $validatedData['password'] = bcrypt($this->password);
            User::create($validatedData);
            session()->flash('message', 'تم إضافة المستخدم بنجاح.');
        }

        // إرسال إشارة للجافا سكريبت لإغلاق المودال تلقائياً بعد الحفظ الناجح
        $this->dispatch('close-user-modal');
        $this->resetInputFields();
    }

    // العمليات الأخرى الثابتة...
    public function toggleStatus(User $user) { $user->update(['active' => $user->active == 1 ? 0 : 1]); }
    public function deleteUser(User $user) { $user->delete(); session()->flash('message', 'تم نقل المستخدم للمحذوفات.'); }
    public function restoreUser($id) { User::onlyTrashed()->findOrFail($id)->restore(); }

    public function render()
    {
        $query = User::query();
        if ($this->showDeleted) { $query->onlyTrashed(); }
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                  ->orWhere('username', 'like', '%'.$this->search.'%')
                  ->orWhere('ssn', 'like', '%'.$this->search.'%');
            });
        }

        return view('livewire.user-index', [
            'users' => $query->latest()->paginate(10),
            'totalCount' => User::count(),
            'deletedCount' => User::onlyTrashed()->count()
        ]); // تأكيد القالب الرئيسي هنا
    }
}
