<main class="flex-1 overflow-y-auto p-6 space-y-6 relative">

    {{-- رسالة النجاح الخضراء --}}
    @if (session()->has('message'))
        <div
            class="p-4 mb-4 text-sm text-green-800 bg-green-50 rounded-xl border border-green-200 flex items-center justify-between shadow-sm">
            <span class="font-medium">{{ session('message') }}</span>
        </div>
    @endif

    {{-- الهيدر العلوي: يعرض اسم المستخدم المستهدف وزر العودة --}}
    <div
        class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h1 class="text-lg font-bold text-gray-900">صلاحيات وأدوار الحساب</h1>
            <p class="text-xs text-gray-500 mt-1">تعديل صلاحيات المستخدم: <span
                    class="text-indigo-600 font-semibold">{{ $user->name }}</span> ({{ $user->username }})</p>
        </div>

        {{-- زر العودة لصفحة إدارة المستخدمين الرئيسية --}}
        <a href="{{ route('users') }}"
            class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-xl text-sm font-medium transition cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m7 7l-7-7 7-7" />
            </svg>
            العودة للمستخدمين
        </a>
    </div>

    {{-- فورم الحفظ الرئيسي --}}
    <form wire:submit.prevent="save" class="space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- كرت إدارة الأدوار (Roles) --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-4 bg-gray-50/70 border-b border-gray-100 flex items-center gap-2">
                    <span class="p-1.5 bg-purple-50 text-purple-600 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </span>
                    <h3 class="text-sm font-bold text-gray-800">الأدوار الوظيفية المتاحة</h3>
                </div>

                {{-- كرت إدارة الأدوار (Roles) --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="p-4 bg-gray-50/70 border-b border-gray-100 flex items-center gap-2">
                        <span class="p-1.5 bg-purple-50 text-purple-600 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </span>
                        <h3 class="text-sm font-bold text-gray-800">الأدوار الوظيفية المتاحة</h3>
                    </div>

                    <div class="p-5 grid grid-cols-1 gap-4">
                        @forelse($allRoles as $role)
                            <div
                                class="p-3 bg-gray-50/50 hover:bg-gray-50 rounded-xl border border-gray-100 transition flex flex-col gap-2">
                                {{-- سطر التحديد الخاص بالدور --}}
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" wire:model="selectedRoles" value="{{ $role->name }}"
                                        class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <div class="text-xs">
                                        <span
                                            class="font-bold text-gray-800">{{ $role->display_name ?? $role->name }}</span>
                                        <span
                                            class="block text-[10px] text-gray-400 font-mono mt-0.5">{{ $role->name }}</span>
                                    </div>
                                </label>

                                {{-- عرض الصلاحيات التابعة لهذا الدور بالأسفل --}}
                                <div
                                    class="mr-7 pl-2 pt-1 border-r border-dashed border-gray-200 flex flex-wrap gap-1.5">
                                    @forelse($role->permissions as $rolePermission)
                                        <span
                                            class="inline-flex items-center text-[10px] bg-purple-50 text-purple-700 font-medium px-2 py-0.5 rounded-md border border-purple-100/60 shadow-2xs">
                                            {{ $rolePermission->display_name ?? $rolePermission->name }}
                                        </span>
                                    @empty
                                        <span class="text-[10px] text-gray-400 italic">هذا الدور لا يحتوي على أي صلاحيات
                                            حالياً.</span>
                                    @endforelse
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400 text-center py-4">لا توجد أدوار مضافة بالنظام.</p>
                        @endforelse
                    </div>
                </div></div>

                {{-- كرت إدارة الصلاحيات الفردية (Permissions) --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="p-4 bg-gray-50/70 border-b border-gray-100 flex items-center gap-2">
                        <span class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </span>
                        <h3 class="text-sm font-bold text-gray-800">الصلاحيات المباشرة المتاحة</h3>
                    </div>

                    <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @forelse($allPermissions as $permission)
                            <label
                                class="flex items-center gap-3 p-3 bg-gray-50/50 hover:bg-gray-50 rounded-xl border border-gray-100 cursor-pointer transition">
                                <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->name }}"
                                    class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                <div class="text-xs">
                                    <span
                                        class="font-semibold text-gray-800">{{ $permission->display_name ?? $permission->name }}</span>
                                    <span
                                        class="block text-[10px] text-gray-400 font-mono mt-0.5">{{ $permission->name }}</span>
                                </div>
                            </label>
                        @empty
                            <p class="text-xs text-gray-400 text-center py-4 col-span-2">لا توجد صلاحيات مضافة بالنظام.
                            </p>
                        @endforelse
                    </div>
                </div>

            </div>

            {{-- شريط أزرار التحكم السفلي الثابت --}}
            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex justify-end gap-3">
                <a href="{{ route('users') }}"
                    class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition cursor-pointer">إلغاء
                    الأمر</a>
                <button type="submit"
                    class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm transition cursor-pointer flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    حفظ التعديلات وتحديث الصلاحيات
                </button>
            </div>

    </form>

</main>
