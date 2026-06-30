<?php

namespace App\Livewire;

use App\Models\department;
use App\Models\mosque;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layout.app', ['title' => 'إدارة المستخدمين'])]
class UserIndex extends Component
{
    use WithPagination;

    // متغيرات البحث والفلترة في الجدول
    public $search = '';

    public $showDeleted = false;

    // متغيرات التحكم بوضعية المودال (إضافة أم تعديل) ومعرف المستخدم المستهدف
    public $isEditMode = false;

    public $userId = null;

    // حقول المودال المرتبطة مباشرة بـ wire:model في الواجهة
    public $name;

    public $ssn;

    public $username;

    public $password;

    public $password_confirmation;

    public $phone;

    public $whatsapp;

    public $address;

    public $role;

    public $mosque_id;

    public $department_id;

    public $view;

    public $active = 1;

    /**
     * القواعد العامة المشتركة بين الإضافة والتعديل.
     */
    protected function commonRules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'ssn' => 'required|numeric|digits:9',
            'phone' => 'nullable|regex:/^0[0-9]{9}$/',
            'whatsapp' => 'nullable|regex:/^0[0-9]{13}$/',
            'address' => 'nullable|string|max:500',
            'role' => 'required|string',
            'active' => 'required|in:0,1',
            'view' => 'required|in:1,2,3',
            'mosque_id' =>'required',
            'department_id'=>'required',
        ];
    }

    /**
     * رسائل الخطأ المخصصة لعمليات التحقق.
     */
    protected function messages(): array
    {
        return [
            'phone.regex' => 'يجب أن يتكون رقم الهاتف من 10 خانات ويبدأ بـ 0.',
            'whatsapp.regex' => 'يجب أن يتكون رقم الواتساب من 14 خانة ويبدأ بـ 0097.',
        ];
    }

    /**
     * [تأثيرها في الفيو]: تعيد الصفحة إلى رقم 1 تلقائياً عند كتابة أي شيء في مربع البحث wire:model.live="search"
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * [تأثيرها في الفيو]: يتم استدعاؤها عند الضغط على زر "عرض المحذوفين مؤخراً"، لتبديل حالة الجدول وعرض البيانات المؤرشفة.
     */
    public function toggleDeleted(): void
    {
        $this->showDeleted = ! $this->showDeleted;
        $this->resetPage();
    }

    /**
     * [تأثيرها في الفيو]: تُستدعى عند الضغط على زر "إضافة مستخدم جديد".
     * تقوم بتفريغ الحقول وضبط وضعية النموذج إلى "إضافة" (isEditMode = false) ليظهر عنوان المودال المناسب وزر الحفظ الصحيح.
     */
    public function initCreate(): void
    {
        $this->resetInputFields();
        $this->isEditMode = false;
    }

    /**
     * [تأثيرها في الفيو]: تُستدعى عند الضغط على زر التعديل (أيقونة القلم) في سطر المستخدم داخل الجدول.
     * تقوم بجلب بيانات الصف المختار وتعبئتها في حقول المودال، وتحول الوضعية إلى (isEditMode = true)، ثم تطلق حدثاً للمودال ليفتح عبر AlpineJS.
     */
    public function initEdit(User $user): void
    {
        $this->resetInputFields();
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->ssn = $user->ssn;
        $this->username = $user->username;
        $this->phone = $user->phone;
        $this->whatsapp = $user->whatsapp;
        $this->address = $user->address;
        $this->role = $user->role;
        $this->active = $user->active;
        $this->department_id= $user->department_id;
        $this->mosque_id= $user->mosque_id;
        $this->view= $user->view;

        $this->isEditMode = true;

        // إرسال إشارة للـ View (AlpineJS) لفتح المودال تلقائياً بعد اكتمال تعبئة الحقول
        $this->dispatch('open-user-modal');
    }

    /**
     * [تأثيرها في الفيو]: تقوم بمسح كافة النصوص المدخلة في الفورم وإزالة رسائل الأخطاء الحمراء وتحضير المودال لعملية جديدة.
     */
    public function resetInputFields(): void
    {
        $this->userId = null;
        $this->name = '';
        $this->ssn = '';
        $this->username = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->phone = '';
        $this->whatsapp = '';
        $this->address = '';
        $this->role = '';
        $this->active = 1;
        $this->department_id= 1;
        $this->mosque_id= 1;
        $this->view= 3;

        $this->resetErrorBag();
    }

    /**
     * [تأثيرها في الفيو]: يتم استدعاؤها عبر `wire:submit.prevent="save"` عند إرسال الفورم.
     * تعمل كموجّه (Router) داخلي، فإذا كانت الوضعية تعديل تتوجه لدالة update، وإلا تتوجه لدالة store.
     */
    public function save(): void
    {
        if ($this->isEditMode) {
            $this->update();
        } else {
            $this->store();
        }
    }

    /**
     * [دالة الإضافة المنفصلة]
     * [تأثيرها في الفيو]: تنفذ تحققاً صارماً (كلمة المرور مطلوبة، واسم المستخدم يجب أن يكون فريداً).
     * عند النجاح: تخزن المستخدم، تطلق رسالة الفلاش الخضراء، وتغلق المودال تلقائياً عبر حدث AlpineJS.
     */
    public function store(): void
    {
        // دمج القواعد العامة مع القواعد الخاصة بالإضافة الفردية
        $rules = array_merge($this->commonRules(), [
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|confirmed|min:8', // مطلوب إجبارياً عند الإضافة
        ]);

        $validatedData = $this->validate($rules);

        // تشفير كلمة المرور قبل الحفظ
        $validatedData['password'] = bcrypt($this->password);

        User::create($validatedData);

        session()->flash('message', 'تم إضافة المستخدم بنجاح.');

        // إغلاق المودال وتصفير المدخلات في الواجهة
        $this->dispatch('close-user-modal');
        $this->resetInputFields();
    }

    /**
     * [دالة التعديل المنفصلة]
     * [تأثيرها في الفيو]: تنفذ تحققاً مرناً (كلمة المرور اختيارية، واسم المستخدم يستثني الحساب الحالي من التكرار).
     * عند النجاح: تقوم بتحديث الصف في الجدول وإظهار رسالة النجاح الخضراء وإغلاق المودال.
     */
    public function update(): void
    {
        $user = User::findOrFail($this->userId);

        // دمج القواعد العامة مع القواعد المرنة الخاصة بالتعديل
        $rules = array_merge($this->commonRules(), [
            // التحقق من عدم تكرار اسم المستخدم مع استثناء المستخدم الحالي لمنع حدوث خطأ أثناء التعديل
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($user->id)],
            // كلمة المرور اختيارية عند التعديل وإذا كُتبت يجب تأكيدها
            'password' => 'nullable|confirmed|min:8',
        ]);

        $validatedData = $this->validate($rules);

        // إذا ترك المستخدم حقل كلمة المرور فارغاً، نحذفه من المصفوفة لكي لا يتم تعديله أو تصفيره
        if (empty($this->password)) {
            unset($validatedData['password']);
        } else {
            $validatedData['password'] = bcrypt($this->password);
        }

        $user->update($validatedData);

        session()->flash('message', 'تم تحديث بيانات المستخدم بنجاح.');

        // إغلاق المودال وتصفير المدخلات في الواجهة
        $this->dispatch('close-user-modal');
        $this->resetInputFields();
    }

    /**
     * [تأثيرها في الفيو]: تُستدعى عند الضغط على زر الحالة (نشط / غير نشط) لتغير لون وشكل الزر فوراً في الجدول بدون تنشيط الصفحة.
     */
    public function toggleStatus(User $user): void
    {
        $user->update(['active' => $user->active == 1 ? 0 : 1]);
    }

    /**
     * [تأثيرها في الفيو]: تنقل المستخدم إلى سلة المحذوفات وتحدث الأرقام العدادات (الإجمالي / المؤرشفين) في كروت الإحصائيات بأعلى الشاشة.
     */
    public function deleteUser(User $user): void
    {
        $user->delete();
        session()->flash('message', 'تم نقل المستخدم للمحذوفات.');
    }

    /**
     * [تأثيرها في الفيو]: تُستدعى من جدول المحذوفات لإعادة المستخدم النشط إلى الجدول الرئيسي واختفائه من قائمة الأرشفة.
     */
    public function restoreUser($id): void
    {
        User::onlyTrashed()->findOrFail($id)->restore();
    }

    public function render()
    {
        $query = User::query();
        if ($this->showDeleted) {
            $query->onlyTrashed();
        }

        if (! empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('username', 'like', '%'.$this->search.'%')
                    ->orWhere('ssn', 'like', '%'.$this->search.'%');
            });
        }

        return view('livewire.user-index', [
            'users' => $query->latest()->paginate(10),
            'totalCount' => User::count(),
            'deletedCount' => User::onlyTrashed()->count(),
            'departments'=>department::all(),
            'mosques'=>mosque::all(),
        ]);
    }
}
