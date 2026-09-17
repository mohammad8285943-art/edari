<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layout.app', ['title' => 'المستخدمين المتواجدين حالياً'])]
class ActiveSessionsManager extends Component
{
    use WithPagination;

    // متغيرة البحث
    public $search = '';

    /**
     * إعادة تعيين الترقيم عند البحث
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * دالة طرد المستخدم: إلغاء الجلسة الفعالة وتصفير remember_token
     */
    public function logoutUser($userId): void
    {
        // 1. مسح كافة الجلسات الخاصة بالمستخدم من جدول sessions
        DB::table('sessions')
            ->where('user_id', $userId)
            ->delete();

        // 2. تصفير الـ remember_token لمنع تسجيل الدخول التلقائي مرة أخرى
        $user = User::find($userId);
        if ($user) {
            $user->update([
                'remember_token' => null,
            ]);
        }

        session()->flash('message', 'تم طرد المستخدم وإنهاء جلسته وتصفير رمز التذكر بنجاح.');
    }

    public function render()
    {
        // جلب الجلسات الفعالة من جدول sessions ومطابقتها مع المستخدمين
        $query = DB::table('sessions')
            ->join('users', 'sessions.user_id', '=', 'users.id')
            ->select(
                'users.id as user_id',
                'users.name',
                'users.username',
                'users.role',
                'users.phone',
                'sessions.id as session_id',
                'sessions.ip_address',
                'sessions.user_agent',
                'sessions.last_activity'
            )
            ->whereNotNull('sessions.user_id');

        // فلترة بالبحث
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('users.name', 'like', '%' . $this->search . '%')
                    ->orWhere('users.username', 'like', '%' . $this->search . '%')
                    ->orWhere('sessions.ip_address', 'like', '%' . $this->search . '%');
            });
        }

        return view('livewire.active-sessions-manager', [
            'activeSessions' => $query->orderBy('sessions.last_activity', 'desc')->paginate(10),
            'onlineUsersCount' => DB::table('sessions')->whereNotNull('user_id')->count('user_id'),
        ]);
    }
}
