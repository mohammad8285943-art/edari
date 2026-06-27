<main x-data="{ modalOpen: false }"
    x-on:open-user-modal.window="modalOpen = true"
    x-on:close-user-modal.window="modalOpen = false"
    class="flex-1 overflow-y-auto p-6 space-y-6 relative">

    @if (session()->has('message'))
        <div class="p-4 mb-4 text-sm text-green-800 bg-green-50 rounded-xl border border-green-200 flex items-center justify-between shadow-sm">
            <span class="font-medium">{{ session('message') }}</span>
        </div>
    @endif

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center gap-4">
            <div class="bg-indigo-50/60 border border-indigo-100 px-4 py-2.5 rounded-xl flex items-center gap-3">
                <span class="text-indigo-600 font-bold text-xl">{{ $totalCount }}</span>
                <span class="text-gray-600 text-sm font-medium">إجمالي المستخدمين</span>
            </div>
            <button wire:click="toggleDeleted" class="flex items-center gap-3 px-4 py-2.5 rounded-xl border transition {{ $showDeleted ? 'bg-red-50 border-red-200 text-red-700 font-semibold' : 'bg-gray-50 border-gray-200 text-gray-600 hover:bg-gray-100' }}">
                <span class="{{ $showDeleted ? 'bg-red-500 text-white' : 'bg-gray-200 text-gray-700' }} px-2 py-0.5 rounded-md font-bold text-sm">{{ $deletedCount }}</span>
                <span class="text-sm font-medium">{{ $showDeleted ? 'عرض المستخدمين النشطين' : 'عرض المحذوفين مؤخراً' }}</span>
            </button>
        </div>

        <button @click="modalOpen = true" wire:click="initCreate" class="w-full md:w-auto bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-medium transition flex items-center justify-center gap-2 cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            إضافة مستخدم جديد
        </button>
    </div>

    <div class="relative w-full max-w-md">
        <input type="text" wire:model.live="search" placeholder="ابحث بالاسم،  الجوال أو الـهوية" class="w-full pr-4 pl-4 py-2.5 bg-white border border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none text-sm">
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-gray-500 text-xs font-bold uppercase tracking-wider">
                        <th class="p-4">بيانات المستخدم</th>
                        <th class="p-4">الهوية (SSN)</th>
                        <th class="p-4">الدور الوظيفي</th>
                        <th class="p-4 text-center">الحالة</th>
                        <th class="p-4 text-center">العمليات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50/40 transition">
                            <td class="p-4">
                                <div class="font-semibold text-gray-900">{{ $user->name }}</div>
                                <div class="text-xs text-gray-400">@<span>{{ $user->username }}</span> | {{ $user->email }}</div>
                            </td>
                            <td class="p-4 font-mono text-gray-600">{{ $user->ssn }}</td>
                            <td class="p-4">
                                <span class="bg-purple-50 text-purple-700 text-xs px-2.5 py-1 rounded-lg font-medium">{{ $user->role }}</span>
                            </td>
                            <td class="p-4 text-center">
                                @if(!$showDeleted)
                                    <button wire:click="toggleStatus({{ $user->id }})" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border transition {{ $user->active == 1 ? 'bg-green-50 border-green-200 text-green-700' : 'bg-red-50 border-red-200 text-red-700' }}">
                                        {{ $user->active == 1 ? 'نشط' : 'غير نشط' }}
                                    </button>
                                @else
                                    <span class="text-gray-400 text-xs">مؤرشف</span>
                                @endif
                            </td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    @if(!$showDeleted)
                                        <button wire:click="initEdit({{ $user->id }})" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        </button>

                                        <a href="{{ route('users.permissions', $user->id) }}" class="p-1.5 text-purple-600 hover:bg-purple-50 rounded-lg transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                        </a>

                                        <button onclick="confirm('نقل للمحذوفات؟') || event.stopImmediatePropagation()" wire:click="deleteUser({{ $user->id }})" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    @else
                                        <button wire:click="restoreUser({{ $user->id }})" class="p-1.5 text-green-600 hover:bg-green-50 rounded-lg transition"><svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.212 9H19" /></svg></button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="p-8 text-center text-gray-400 bg-gray-50/50">لا توجد نتائج.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100 bg-gray-50/50">{{ $users->links() }}</div>
    </div>

    <div x-show="modalOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm flex items-center justify-center z-50 p-4"
        style="display: none;">

        <div @click.away="modalOpen = false; $wire.resetInputFields()"
            x-show="modalOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-4"
            class="bg-white rounded-2xl shadow-xl w-full max-w-2xl overflow-hidden border border-gray-100 max-h-[90vh] flex flex-col">

            <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h3 class="text-base font-bold text-gray-800">
                    {{ $isEditMode ? 'تعديل بيانات المستخدم الحالي' : 'إضافة مستخدم جديد للنظام' }}
                </h3>
                <button @click="modalOpen = false; $wire.resetInputFields()" class="text-gray-400 hover:text-gray-600 bg-gray-200/50 p-1.5 rounded-lg transition cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <form wire:submit.prevent="save" class="p-6 space-y-4 overflow-y-auto flex-1 text-xs">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">الاسم الكامل</label>
                        <input type="text" wire:model="name" class="w-full border border-gray-300 rounded-lg p-2 outline-none focus:border-indigo-500">
                        @error('name') <span class="text-red-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">الرقم القومي (SSN)</label>
                        <input type="text" wire:model="ssn" class="w-full border border-gray-300 rounded-lg p-2 outline-none focus:border-indigo-500">
                        @error('ssn') <span class="text-red-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">اسم المستخدم</label>
                        <input type="text" wire:model="username" class="w-full border border-gray-300 rounded-lg p-2 outline-none focus:border-indigo-500">
                        @error('username') <span class="text-red-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">البريد الإلكتروني</label>
                        <input type="email" wire:model="email" class="w-full border border-gray-300 rounded-lg p-2 outline-none focus:border-indigo-500">
                        @error('email') <span class="text-red-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">رقم الهاتف</label>
                        <input type="text" wire:model="phone" placeholder="05xxxxxxxx" class="w-full border border-gray-300 rounded-lg p-2 outline-none focus:border-indigo-500">
                        @error('phone') <span class="text-red-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">رقم الواتساب</label>
                        <input type="text" wire:model="whatsapp" placeholder="05xxxxxxxx" class="w-full border border-gray-300 rounded-lg p-2 outline-none focus:border-indigo-500">
                        @error('whatsapp') <span class="text-red-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">كلمة المرور {{ $isEditMode ? '(اتركها فارغة للتخطي)' : '' }}</label>
                        <input type="password" wire:model="password" class="w-full border border-gray-300 rounded-lg p-2 outline-none focus:border-indigo-500">
                        @error('password') <span class="text-red-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">تأكيد كلمة المرور</label>
                        <input type="password" wire:model="password_confirmation" class="w-full border border-gray-300 rounded-lg p-2 outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">الدور الوظيفي</label>
                        <input type="text" wire:model="role" class="w-full border border-gray-300 rounded-lg p-2 outline-none focus:border-indigo-500">
                        @error('role') <span class="text-red-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">الحالة الإدارية</label>
                        <select wire:model="active" class="w-full border border-gray-300 rounded-lg p-2 bg-white outline-none focus:border-indigo-500">
                            <option value="1">نشط ومفعل</option>
                            <option value="0">معطل / غير نشط</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block font-medium text-gray-700 mb-1">العنوان السكني</label>
                    <textarea wire:model="address" rows="2" class="w-full border border-gray-300 rounded-lg p-2 outline-none focus:border-indigo-500 resize-none"></textarea>
                </div>

                <div class="border-t border-gray-100 pt-4 flex justify-end gap-2 bg-gray-50 -mx-6 -mb-6 p-4">
                    <button type="button" @click="modalOpen = false; $wire.resetInputFields()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition cursor-pointer">إلغاء</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition cursor-pointer">
                        {{ $isEditMode ? 'تحديث البيانات' : 'حفظ وإضافة' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

</main>
