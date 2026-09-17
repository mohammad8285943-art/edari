<main class="flex-1 overflow-y-auto p-4 space-y-4 relative text-xs">

    {{-- قسم الإحصائيات العلوي وأزرار التحكم --}}
    <div
        class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-white p-3 rounded-xl shadow-sm border border-gray-100">
        <div class="flex flex-wrap items-center gap-2">
            <div class="bg-indigo-50 border border-indigo-100 px-3 py-1.5 rounded-lg flex items-center gap-3">
                <span class="text-gray-600 font-medium">إجمالي الأرامل:</span>
                <span class="text-indigo-600 font-bold text-base">{{ $totalWidowsCount }}</span>
            </div>

            <div class="bg-emerald-50 border border-emerald-100 px-3 py-1.5 rounded-lg flex items-center gap-3">
                <span class="text-gray-600 font-medium">لديهن أيتام:</span>
                <span class="text-emerald-600 font-bold text-base">{{ $withOrphansCount }}</span>
            </div>

            <div class="bg-amber-50 border border-amber-100 px-3 py-1.5 rounded-lg flex items-center gap-3">
                <span class="text-gray-600 font-medium">ليس لديهن أيتام:</span>
                <span class="text-amber-600 font-bold text-base">{{ $withoutOrphansCount }}</span>
            </div>

            <div class="bg-blue-50 border border-blue-100 px-3 py-1.5 rounded-lg flex items-center gap-3">
                <span class="text-gray-600 font-medium">إجمالي الأيتام:</span>
                <span class="text-blue-600 font-bold text-base">{{ $totalOrphansCount }}</span>
            </div>
        </div>
        <button type="button" wire:click="exportExcel" wire:loading.attr="disabled"
            class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 text-white px-4 py-1.5 rounded-lg font-medium transition flex items-center justify-center gap-1.5 cursor-pointer shadow-sm text-xs">

            {{-- الأيقونة العادية --}}
            <svg wire:loading.remove wire:target="exportExcel" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>

            {{-- Spinner أثناء التحميل --}}
            <svg wire:loading wire:target="exportExcel" class="animate-spin h-4 w-4 text-white"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>

            <span wire:loading.remove wire:target="exportExcel">تصدير Excel</span>
            <span wire:loading wire:target="exportExcel">جاري التصدير...</span>
        </button>
        @can('orphan.widows.edit')
            <div class="flex flex-col sm:flex-row items-center gap-2 w-full sm:w-auto">
                <button type="button" wire:click="openCreateModal"
                    class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-1.5 rounded-lg font-medium transition flex items-center justify-center gap-1.5 cursor-pointer shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    إضافة أرملة
                </button>
            </div>
        @endcan
    </div>

    {{-- رسائل النجاح --}}
    @if (session()->has('message'))
        <div class="p-3 bg-green-100 text-green-800 border border-green-200 rounded-lg font-medium">
            {{ session('message') }}
        </div>
    @endif

    {{-- لوحة الفلاتر --}}
    <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm space-y-3">
        <div class="flex items-center justify-between border-b border-gray-50 pb-2">
            <h4 class="font-bold text-gray-700 flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-indigo-500" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                تصفية الأرامل
            </h4>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-7 gap-2.5">
            <div>
                <label class="block font-medium text-gray-500 mb-1">اسم الأرملة</label>
                <input type="text" wire:model="searchName" placeholder="بحث بالاسم..."
                    class="w-full px-2.5 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500 text-xs">
            </div>

            <div>
                <label class="block font-medium text-gray-500 mb-1">هوية الأرملة</label>
                <input type="text" wire:model="searchSsn" placeholder="رقم الهوية..."
                    class="w-full px-2.5 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500 text-xs">
            </div>

            <div>
                <label class="block font-medium text-gray-500 mb-1">اسم الزوج</label>
                <input type="text" wire:model="searchHusband" placeholder="اسم الزوج..."
                    class="w-full px-2.5 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500 text-xs">
            </div>

            <div>
                <label class="block font-medium text-gray-500 mb-1">هوية الزوج</label>
                <input type="text" wire:model="searchHusbandSsn" placeholder="هوية الزوج..."
                    class="w-full px-2.5 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500 text-xs">
            </div>

            <div>
                <label class="block font-medium text-gray-500 mb-1">حالة الأيتام</label>
                <select wire:model="filterOrphanStatus"
                    class="w-full px-2 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500 text-xs bg-white">
                    <option value="">الكل</option>
                    <option value="with">لديها أيتام</option>
                    <option value="without">ليس لديها أيتام</option>
                </select>
            </div>

            @if (auth()->user()->view == 1)
                <div>
                    <label class="block font-medium text-gray-500 mb-1">القسم</label>
                    <select wire:model="filterDepartment"
                        class="w-full px-2 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500 text-xs bg-white">
                        <option value="">كل الأقسام</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            @if (auth()->user()->view != 3)
                <div>
                    <label class="block font-medium text-gray-500 mb-1">المسجد</label>
                    <select wire:model="filterMosque"
                        class="w-full px-2 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500 text-xs bg-white">
                        <option value="">كل المساجد</option>
                        @foreach ($mosques as $mosque)
                            <option value="{{ $mosque->id }}">{{ $mosque->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>

        <div class="flex justify-end gap-1.5 pt-2 border-t border-gray-50">
            <button type="button" wire:click="resetFilter"
                class="px-3 py-1.5 text-gray-500 bg-gray-100 hover:bg-gray-200 rounded-lg transition cursor-pointer">
                إعادة تعيين
            </button>
            <button type="button" wire:click="applyFilter"
                class="px-4 py-1.5 text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition flex items-center gap-1 cursor-pointer font-medium shadow-sm">
                تصفية النتائج
            </button>
        </div>
    </div>

    {{-- جدول الأرامل --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden text-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-gray-500 font-bold uppercase tracking-wider">
                        <th class="p-3">#</th>
                        <th class="p-3">اسم الأرملة / الهوية</th>
                        <th class="p-3">الجوال / جوال غزة</th>
                        <th class="p-3">اسم الزوج / هويته</th>
                        <th class="p-3 text-center">عدد الأيتام</th>
                        <th class="p-3">القسم / المسجد</th>
                        @can('orphan.widows.edit')
                            <th class="p-3 text-center">الإجراءات</th>
                        @endcan
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($widows as $index => $widow)
                        <tr class="hover:bg-gray-50/30 transition">
                            <td class="p-3 font-medium text-gray-400">
                                {{ $widows->firstItem() + $index }}
                            </td>
                            <td class="p-3">
                                <button type="button" wire:click="showOrphans({{ $widow->id }})"
                                    class="font-bold text-indigo-600 hover:text-indigo-900 hover:underline cursor-pointer text-right">
                                    {{ $widow->name }}
                                </button>
                                <div class="text-gray-400 mt-0.5">{{ $widow->ssn }}</div>
                            </td>
                            <td class="p-3">
                                <div class="font-medium text-gray-800">{{ $widow->mobile ?? '-' }}</div>
                                <div class="text-[11px] text-gray-400 mt-0.5">غزة: {{ $widow->gaz_mobile ?? '-' }}
                                </div>
                            </td>
                            <td class="p-3">
                                <div class="font-medium text-gray-800">{{ $widow->husband }}</div>
                                <div class="text-[11px] text-gray-400 mt-0.5">{{ $widow->h_ssn ?? '-' }}</div>
                            </td>
                            <td class="p-3 text-center">
                                @if ($widow->orphan_count > 0)
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                        {{ $widow->orphan_count }}
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                        0
                                    </span>
                                @endif
                            </td>
                            <td class="p-3">
                                <div class="text-gray-700">{{ $widow->department?->name ?? 'غير محدد' }}</div>
                                <div class="text-[11px] text-gray-400 mt-0.5">
                                    {{ $widow->mosque?->name ?? 'غير محدد' }}</div>
                            </td>
                            @can('orphan.widows.edit')
                                <td class="p-3 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <button type="button" wire:click="openEditModal({{ $widow->id }})"
                                            class="inline-flex items-center justify-center p-1.5 text-blue-600 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition"
                                            title="تعديل الأرملة">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button type="button" wire:click="confirmDelete({{ $widow->id }})"
                                            class="inline-flex items-center justify-center p-1.5 text-red-600 hover:bg-red-50 hover:text-red-700 rounded-lg transition"
                                            title="حذف الأرملة">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            @endcan
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-gray-400 bg-gray-50/50">
                                لا توجد أرامل تطابق شروط التصفية والبحث الحالية.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3 border-t border-gray-100 bg-gray-50/50">
            {{ $widows->links() }}
        </div>
    </div>

    {{-- مودال إضافة / تعديل أرملة --}}
    @if ($showModal)
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4" wire:click.self="closeModal">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-3xl max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between p-4 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800">
                        {{ $editingId ? 'تعديل بيانات الأرملة' : 'إضافة أرملة جديدة' }}
                    </h3>
                    <button type="button" wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-4 space-y-3">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 text-xs">
                        <div>
                            <label class="block font-medium text-gray-500 mb-1">اسم الأرملة <span
                                    class="text-red-500">*</span></label>
                            <input type="text" wire:model="form_name"
                                class="w-full px-2.5 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500">
                            @error('form_name')
                                <span class="text-red-600 text-[11px]">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-medium text-gray-500 mb-1">رقم الهوية <span
                                    class="text-red-500">*</span></label>
                            <input type="text" wire:model="form_ssn"
                                class="w-full px-2.5 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500">
                            @error('form_ssn')
                                <span class="text-red-600 text-[11px]">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-medium text-gray-500 mb-1">الجوال</label>
                            <input type="text" wire:model="form_mobile"
                                class="w-full px-2.5 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500">
                            @error('form_mobile')
                                <span class="text-red-600 text-[11px]">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-medium text-gray-500 mb-1">جوال الغاز</label>
                            <input type="text" wire:model="form_gaz_mobile"
                                class="w-full px-2.5 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500">
                            @error('form_gaz_mobile')
                                <span class="text-red-600 text-[11px]">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-medium text-gray-500 mb-1">العمل</label>
                            <input type="text" wire:model="form_job"
                                class="w-full px-2.5 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500">
                            @error('form_job')
                                <span class="text-red-600 text-[11px]">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-medium text-gray-500 mb-1">عدد الأيتام <span
                                    class="text-red-500">*</span></label>
                            <input type="number" min="0" wire:model="form_orphan_count"
                                class="w-full px-2.5 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500">
                            @error('form_orphan_count')
                                <span class="text-red-600 text-[11px]">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-medium text-gray-500 mb-1">اسم الزوج <span
                                    class="text-red-500">*</span></label>
                            <input type="text" wire:model="form_husband"
                                class="w-full px-2.5 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500">
                            @error('form_husband')
                                <span class="text-red-600 text-[11px]">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-medium text-gray-500 mb-1">هوية الزوج</label>
                            <input type="text" wire:model="form_h_ssn"
                                class="w-full px-2.5 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500">
                            @error('form_h_ssn')
                                <span class="text-red-600 text-[11px]">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-medium text-gray-500 mb-1">عمل الزوج</label>
                            <input type="text" wire:model="form_h_job"
                                class="w-full px-2.5 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500">
                            @error('form_h_job')
                                <span class="text-red-600 text-[11px]">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-medium text-gray-500 mb-1">تاريخ الوفاة</label>
                            <input type="text" wire:model="form_date_death" placeholder="مثال: 2023-10-07"
                                class="w-full px-2.5 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500">
                            @error('form_date_death')
                                <span class="text-red-600 text-[11px]">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-medium text-gray-500 mb-1">الشعبة <span
                                    class="text-red-500">*</span></label>
                            <select wire:model="form_department_id"
                                class="w-full px-2 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500 bg-white">
                                @if ($departments->isNotEmpty())
                                    <option value="">اختر الشعبة</option>

                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                @else
                                    <option value="{{ auth()->user()->department->id }}">
                                        {{ auth()->user()->department->name }}</option>
                                @endif
                            </select>
                            @error('form_department_id')
                                <span class="text-red-600 text-[11px]">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-medium text-gray-500 mb-1">المسجد <span
                                    class="text-red-500">*</span></label>
                            <select wire:model="form_mosque_id"
                                class="w-full px-2 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500 bg-white">
                                <option value="">اختر المسجد</option>
                                @foreach ($mosques as $mosque)
                                    <option value="{{ $mosque->id }}">{{ $mosque->name }}</option>
                                @endforeach
                            </select>
                            @error('form_mosque_id')
                                <span class="text-red-600 text-[11px]">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="sm:col-span-2 md:col-span-3">
                            <label class="block font-medium text-gray-500 mb-1">عنوان السكن</label>
                            <input type="text" wire:model="form_address"
                                placeholder="المنطقة / الشارع / تفاصيل العنوان"
                                class="w-full px-2.5 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500">
                            @error('form_address')
                                <span class="text-red-600 text-[11px]">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-1.5 p-4 border-t border-gray-50">
                    <button type="button" wire:click="closeModal"
                        class="px-3 py-1.5 text-gray-500 bg-gray-100 hover:bg-gray-200 rounded-lg transition cursor-pointer text-xs">
                        إلغاء
                    </button>
                    <button type="button" wire:click="save"
                        class="px-4 py-1.5 text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition font-medium shadow-sm cursor-pointer text-xs">
                        {{ $editingId ? 'حفظ التعديلات' : 'إضافة الأرملة' }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- مودال عرض الأيتام المرتبطين بالأرملة --}}
    @if ($showOrphansModal && $selectedWidow)
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4"
            wire:click.self="closeOrphansModal">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between p-4 border-b border-gray-100">
                    <div>
                        <h3 class="font-bold text-gray-800 text-sm">
                            أيتام الأرملة: {{ $selectedWidow->name }}
                        </h3>
                        <p class="text-[11px] text-gray-400 mt-0.5">
                            رقم الهوية: {{ $selectedWidow->ssn }} | عدد الأيتام المسجل:
                            {{ $selectedWidow->orphan_count }}
                        </p>
                    </div>
                    <button type="button" wire:click="closeOrphansModal" class="text-gray-400 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-4">
                    @if (count($widowOrphans) > 0)
                        <div class="border border-gray-100 rounded-lg overflow-hidden">
                            <table class="w-full text-right border-collapse text-xs">
                                <thead>
                                    <tr class="bg-gray-50 border-b border-gray-100 text-gray-500 font-bold">
                                        <th class="p-2.5">اسم اليتيم</th>
                                        <th class="p-2.5">رقم الهوية</th>
                                        <th class="p-2.5">الجنس</th>
                                        <th class="p-2.5">العمر</th>
                                        <th class="p-2.5">الجوال</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-gray-700">
                                    @foreach ($widowOrphans as $orphan)
                                        <tr class="hover:bg-gray-50/50">
                                            <td class="p-2.5 font-bold text-gray-900">{{ $orphan->name }}</td>
                                            <td class="p-2.5">{{ $orphan->SSN ?? ($orphan->ssn ?? '-') }}</td>
                                            <td class="p-2.5">{{ $orphan->gender ?? '-' }}</td>
                                            <td class="p-2.5">{{ $orphan->age ?? '-' }}</td>
                                            <td class="p-2.5">{{ $orphan->mobile ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-6 text-center space-y-2 bg-amber-50/50 rounded-lg border border-amber-100">
                            <div class="text-amber-700 font-bold">لا يوجد أيتام مسجلون مرتبطون بهذه الأرملة في قاعدة
                                البيانات.</div>
                            <div class="text-[11px] text-gray-500">
                                تم البحث باستخدام رقم هوية الأم (m_ssn: {{ $selectedWidow->ssn }}).
                            </div>
                        </div>
                    @endif
                </div>

                <div class="flex justify-end p-4 border-t border-gray-50">
                    <button type="button" wire:click="closeOrphansModal"
                        class="px-4 py-1.5 text-gray-500 bg-gray-100 hover:bg-gray-200 rounded-lg transition cursor-pointer text-xs">
                        إغلاق
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- تأكيد الحذف --}}
    @if ($confirmingDeleteId)
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4"
            wire:click.self="cancelDelete">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-sm p-5 text-center space-y-3">
                <h3 class="font-bold text-gray-800">تأكيد الحذف</h3>
                <p class="text-gray-500 text-xs">هل أنت متأكد من حذف هذه الأرملة؟ لا يمكن التراجع عن هذا الإجراء.</p>
                <div class="flex justify-center gap-2 pt-2">
                    <button type="button" wire:click="cancelDelete"
                        class="px-4 py-1.5 text-gray-500 bg-gray-100 hover:bg-gray-200 rounded-lg transition cursor-pointer text-xs">
                        إلغاء
                    </button>
                    <button type="button" wire:click="deleteWidow"
                        class="px-4 py-1.5 text-white bg-red-600 hover:bg-red-700 rounded-lg transition font-medium cursor-pointer text-xs">
                        نعم، احذف
                    </button>
                </div>
            </div>
        </div>
    @endif

</main>
