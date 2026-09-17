<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layout.app', ['title' => 'الملف الشخصي'])]
class Profile extends Component
{
    // بيانات المستخدم الحالي
    public $user;

    // البيانات القابلة للتعديل
    public $username;
    public $password;
    public $password_confirmation;
    public $phone;
    public $whatsapp;
    public $address;

    /**
     * [تأثيرها في الفيو]&#58;      * يتم استدعاؤها عند فتح صفحة الملف الشخصي.
     * تقوم بجلب المستخدم الحالي وتعبئة البيانات القابلة للتعديل.
     */
    public function mount(): void
    {
        $this->user = Auth::user();

        $this->username = $this->user->username;
        $this->phone = $this->user->phone;
        $this->whatsapp = $this->user->whatsapp;
        $this->address = $this->user->address;
    }

    /**
     * [قواعد التحقق]&#58;      * كلمة المرور اختيارية عند التعديل.
     * إذا تم إدخالها يجب ألا تقل عن 8 أحرف ويجب تأكيدها.
     */
    protected function rules(): array
    {
        return [
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore($this->user->id),
            ],

            'password' => 'nullable|confirmed|min:8',

            'phone' => 'nullable|regex:/^0[0-9]{9}$/',

            'whatsapp' => 'nullable|regex:/^0[0-9]{13}$/',

            'address' => 'nullable|string|max:500',
        ];
    }

    /**
     * [رسائل الخطأ]&#58;      * رسائل مخصصة للحقول التي تحتاج توضيحاً للمستخدم.
     */
    protected function messages(): array
    {
        return [
            'phone.regex' => 'يجب أن يتكون رقم الهاتف من 10 خانات ويبدأ بـ 0.',
            'whatsapp.regex' => 'يجب أن يتكون رقم الواتساب من 14 خانة ويبدأ بـ 0.',
            'password.min' => 'يجب ألا تقل كلمة المرور عن 8 أحرف.',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
            'username.unique' => 'اسم المستخدم مستخدم بالفعل.',
        ];
    }

    /**
     * [تأثيرها في الفيو]&#58;      * يتم استدعاؤها عند الضغط على زر حفظ التعديلات.
     * تقوم بتحديث البيانات التي يسمح للمستخدم بتعديلها فقط.
     */
    public function updateProfile(): void
    {
        $validatedData = $this->validate();

        // البيانات الأساسية القابلة للتعديل
        $data = [
            'username' => $validatedData['username'],
            'phone' => $validatedData['phone'] ?? null,
            'whatsapp' => $validatedData['whatsapp'] ?? null,
            'address' => $validatedData['address'] ?? null,
        ];

        // كلمة المرور لا يتم تعديلها إذا ترك المستخدم الحقل فارغاً
        if (!empty($this->password)) {
            $data['password'] = bcrypt($this->password);
        }

        $this->user->update($data);

        // تحديث بيانات المستخدم بعد الحفظ
        $this->user->refresh();

        // تفريغ حقول كلمة المرور
        $this->password = '';
        $this->password_confirmation = '';

        session()->flash('message', 'تم تحديث بيانات الملف الشخصي بنجاح.');
    }

    public function render()
    {
        return view('livewire.profile');
    }
}
