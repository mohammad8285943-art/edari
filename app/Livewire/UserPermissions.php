<?php

namespace App\Livewire;

use App\Models\department;
use App\Models\mosque;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

#[Layout('layout.app', ['title' => 'إدارة صلاحيات المستخدم'])]
class UserPermissions extends Component
{
    // معرف المستخدم القادم من الرابط (URL)
    public $userId;

    // مصفوفات تخزين الخيارات المحددة في الواجهة (تتزامن مع wire:model)
    public $selectedRoles = [];

    public $selectedPermissions = [];

    /**
     * دالة التجهيز (Mount): تُستدعى تلقائياً عند تحميل الصفحة وتستقبل الـ id من الـ Route
     */
    public function mount($id)
    {
        $this->userId = $id;

        // جلب المستخدم مع الأدوار والصلاحيات الخاصة به
        $user = User::findOrFail($this->userId);

        // تعبئة المصفوفات بالأدوار والصلاحيات الحالية للمستخدم لتبدو "محددة Checkmarked" في الفيو
        $this->selectedRoles = $user->roles->pluck('name')->toArray();
        $this->selectedPermissions = $user->permissions->pluck('name')->toArray();
    }

    /**
     * [تأثيرها في الفيو]: يتم استدعاؤها عند الضغط على زر "حفظ التعديلات".
     * تقوم بتحديث أدوار وصلاحيات المستخدم في قاعدة البيانات مباشرة وإظهار رسالة نجاح.
     */
    public function save()
    {
        $user = User::findOrFail($this->userId);

        // تنظيف الكاش الخاص بحزمة Spatie لضمان تطبيق التعديلات فوراً
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // مزامنة الأدوار (تضيف الجديد وتحذف غير المحدد)
        $user->syncRoles($this->selectedRoles);

        // مزامنة الصلاحيات المباشرة (تضيف الجديد وتحذف غير المحدد)
        $user->syncPermissions($this->selectedPermissions);

        // إرسال رسالة نجاح خضراء للـ الفيو
        session()->flash('message', 'تم تحديث الأدوار والصلاحيات للمستخدم بنجاح.');
    }

    public function render()
    {
        // جلب المستخدم الحالي لعرض اسمه وبياناته
        $user = User::findOrFail($this->userId);

        return view('livewire.user-permissions', [
            'user' => $user,
            'allRoles' => Role::with('permissions')->get(), // التعديل هنا: جلب الأدوار مع صلاحياتها فوراً
            'allPermissions' => Permission::all(),
            
        ]);
    }
}
