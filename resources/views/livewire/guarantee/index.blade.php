<main class="flex-1 overflow-y-auto p-4 space-y-4 relative text-xs">

    {{-- قسم الإحصائيات العلوي وأزرار التحكم --}}
    <div
        class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-white p-3 rounded-xl shadow-sm border border-gray-100">
        <div class="flex flex-wrap items-center gap-2">
            <div class="bg-indigo-50 border border-indigo-100 px-3 py-1.5 rounded-lg flex items-center gap-3">
                <span class="text-gray-600 font-medium">إجمالي الكفالات:</span>
                <span class="text-indigo-600 font-bold text-base">{{ $totalCount }}</span>
            </div>

            <div class="bg-emerald-50 border border-emerald-100 px-3 py-1.5 rounded-lg flex items-center gap-3">
                <span class="text-gray-600 font-medium">المستفيدين (بدون تكرار):</span>
                <span class="text-emerald-600 font-bold text-base">{{ $uniqueBeneficiariesCount }}</span>
            </div>

            <div class="bg-emerald-50 border border-emerald-100 px-3 py-1.5 rounded-lg flex items-center gap-3">
                <span class="text-gray-600 font-medium">إجمالي المبالغ:</span>
                <span class="text-emerald-600 font-bold text-base">{{ number_format($sumAmount, 2) }}</span>
            </div>

            <div class="bg-amber-50 border border-amber-100 px-3 py-1.5 rounded-lg flex items-center gap-3">
                <span class="text-gray-600 font-medium">بدون يتيم مسجل:</span>
                <span class="text-amber-600 font-bold text-base">{{ $noOrphanCount }}</span>
            </div>

        </div>
        <div class="flex flex-col sm:flex-row items-center gap-2 w-full sm:w-auto">

            <button type="button" wire:click="exportExcel" wire:loading.attr="disabled"
                class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-4 py-1.5 rounded-lg font-medium transition flex items-center justify-center gap-1.5 cursor-pointer shadow-sm">

                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v9a2 2 0 01-2 2z" />
                </svg>

                <span wire:loading.remove wire:target="exportExcel">
                    تصدير Excel
                </span>

                <span wire:loading wire:target="exportExcel">
                    جاري التصدير...
                </span>
            </button>
            @can('orphan.guarantees.edit')
                <button type="button" wire:click="openCreateModal"
                    class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-1.5 rounded-lg font-medium transition flex items-center justify-center gap-1.5 cursor-pointer shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    إضافة كفالة
                </button>
            @endcan
        </div>
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
                تصفية الكفالات
            </h4>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2.5">
            <div class="lg:col-span-2">
                <label class="block font-medium text-gray-500 mb-1">بحث شامل (هوية اليتيم / اسم اليتيم / الكافل)</label>
                <input type="text" wire:model="search" placeholder="ابحث هنا..."
                    class="w-full px-2.5 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500 text-xs">
            </div>

            <div>
                <label class="block font-medium text-gray-500 mb-1">الكافل</label>
                <select wire:model="filterGuarantor"
                    class="w-full px-2 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500 text-xs bg-white">
                    <option value="">كل الكافلين</option>
                    @foreach ($guarantorsList as $guarantorName)
                        <option value="{{ $guarantorName }}">{{ $guarantorName }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-medium text-gray-500 mb-1">
                    تسجيل اليتيم
                </label>

                <select wire:model="filterOrphanStatus"
                    class="w-full px-2 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500 text-xs bg-white">

                    <option value="">كل الكفالات</option>

                    <option value="registered">
                        اليتيم مسجل
                    </option>

                    <option value="not_registered">
                        اليتيم غير مسجل
                    </option>

                </select>
            </div>

            @if (auth()->user()->view == 1)
                <div>
                    <label class="block font-medium text-gray-500 mb-1">الشعبة (تلغى عند تحديد المسجد)</label>
                    <select wire:model="filterDepartment"
                        class="w-full px-2 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500 text-xs bg-white">
                        <option value="">كل الشعب</option>
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

    {{-- جدول الكفالات --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden text-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-gray-500 font-bold uppercase tracking-wider">
                        <th class="p-3">اليتيم</th>
                        <th class="p-3">الكافل</th>
                        <th class="p-3">القسم / المسجد</th>
                        <th class="p-3">المبلغ</th>
                        <th class="p-3">بداية / نهاية الكفالة</th>
                        @can('orphan.guarantees.edit')
                            <th class="p-3 text-center">العمليات</th>
                        @endcan
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($guarantees as $guarantee)
                        <tr class="hover:bg-gray-50/30 transition">
                            <td class="p-3">
                                @if ($guarantee->orphan_name)
                                    <div class="font-bold text-gray-900">
                                        {{ $guarantee->orphan_name }}
                                    </div>
                                @else
                                    <div class="font-bold text-gray-900">
                                        {{ $guarantee->name }}
                                        <span class="font-bold text-red-600">
                                            **
                                        </span>
                                    </div>
                                @endif
                                <div class="text-gray-400 mt-0.5">{{ $guarantee->ssn }}</div>
                            </td>
                            <td class="p-3">
                                <div class="font-medium text-gray-800">{{ $guarantee->guarantor ?? '-' }}</div>
                                <div class="text-[11px] text-gray-400 mt-0.5">{{ $guarantee->mobile ?? 'لا يوجد' }}
                                </div>
                            </td>
                            <td class="p-3">
                                <div class="text-gray-700">{{ $guarantee->department?->name ?? 'غير محدد' }}</div>
                                <div class="text-[11px] text-gray-400 mt-0.5">
                                    {{ $guarantee->mosque?->name ?? 'غير محدد' }}</div>
                            </td>
                            <td class="p-3">
                                <span
                                    class="font-bold text-emerald-700">{{ number_format($guarantee->amount, 2) }}</span>
                            </td>
                            <td class="p-3 space-y-0.5">
                                <div><span class="text-gray-400">من:</span>
                                    {{ $guarantee->guarantor_start ? \Carbon\Carbon::parse($guarantee->guarantor_start)->format('Y-m-d') : '-' }}
                                </div>
                                <div><span class="text-gray-400">إلى:</span>
                                    {{ $guarantee->guarantor_end ? \Carbon\Carbon::parse($guarantee->guarantor_end)->format('Y-m-d') : '-' }}
                                </div>
                            </td>
                            @can('orphan.guarantees.edit')
                                <td class="p-3 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <button type="button" wire:click="openEditModal({{ $guarantee->id }})"
                                            class="inline-flex items-center justify-center p-1.5 text-blue-600 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition"
                                            title="تعديل الكفالة">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button type="button" wire:click="confirmDelete({{ $guarantee->id }})"
                                            class="inline-flex items-center justify-center p-1.5 text-red-600 hover:bg-red-50 hover:text-red-700 rounded-lg transition"
                                            title="حذف الكفالة">
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
                                لا توجد كفالات تطابق التصفية المطلوبة.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3 border-t border-gray-100 bg-gray-50/50">
            {{ $guarantees->links() }}
        </div>
    </div>

    {{-- مودال الإضافة / التعديل --}}
    @if ($showModal)
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4" wire:click.self="closeModal">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between p-4 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800">
                        {{ $editingId ? 'تعديل الكفالة' : 'إضافة كفالة جديدة' }}
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
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                        <div>
                            <label class="block font-medium text-gray-500 mb-1">رقم هوية اليتيم (SSN)</label>
                            <input type="text" wire:model.live="form_ssn"
                                class="w-full px-2.5 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500">
                            @error('form_ssn')
                                <span class="text-red-600 text-[11px]">{{ $message }}</span>
                            @enderror

                            @if ($orphanPreviewName)
                                <div
                                    class="mt-1 text-[11px] text-emerald-700 bg-emerald-50 border border-emerald-100 rounded px-2 py-1">
                                    يتيم مسجل: {{ $orphanPreviewName }}
                                </div>
                            @elseif ($orphanNotFound)
                                <div
                                    class="mt-1 text-[11px] text-amber-700 bg-amber-50 border border-amber-100 rounded px-2 py-1">
                                    لا يوجد يتيم مسجل بهذا الرقم، وسيتم حفظ الكفالة ويمكن ربطها باليتيم لاحقًا.
                                </div>
                            @endif
                        </div>

                        <div>
                            <label class="block font-medium text-gray-500 mb-1">الاسم</label>
                            <input type="text" wire:model="form_name"
                                class="w-full px-2.5 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500">
                            @error('form_name')
                                <span class="text-red-600 text-[11px]">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-medium text-gray-500 mb-1">رقم الجوال</label>
                            <input type="text" wire:model="form_mobile"
                                class="w-full px-2.5 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500">
                            @error('form_mobile')
                                <span class="text-red-600 text-[11px]">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-medium text-gray-500 mb-1">الشعبة</label>
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
                            <label class="block font-medium text-gray-500 mb-1">المسجد</label>
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

                        <div class="">
                            <label class="block font-medium text-gray-500 mb-1">العنوان / المنزل</label>
                            <input type="text" wire:model="form_home"
                                class="w-full px-2.5 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500">
                            @error('form_home')
                                <span class="text-red-600 text-[11px]">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-medium text-gray-500 mb-1">اسم الكافل</label>
                            <input type="text" wire:model="form_guarantor"
                                class="w-full px-2.5 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500">
                            @error('form_guarantor')
                                <span class="text-red-600 text-[11px]">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-medium text-gray-500 mb-1">قيمة الكفالة</label>
                            <input type="number" step="0.01" wire:model="form_amount"
                                class="w-full px-2.5 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500">
                            @error('form_amount')
                                <span class="text-red-600 text-[11px]">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-medium text-gray-500 mb-1">تاريخ بداية الكفالة</label>
                            <input type="date" wire:model="form_guarantor_start"
                                class="w-full px-2.5 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500">
                            @error('form_guarantor_start')
                                <span class="text-red-600 text-[11px]">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-medium text-gray-500 mb-1">تاريخ نهاية الكفالة</label>
                            <input type="date" wire:model="form_guarantor_end"
                                class="w-full px-2.5 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500">
                            @error('form_guarantor_end')
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
                        {{ $editingId ? 'حفظ التعديلات' : 'إضافة الكفالة' }}
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
                <p class="text-gray-500 text-xs">هل أنت متأكد من حذف هذه الكفالة؟ لا يمكن التراجع عن هذا الإجراء.</p>
                <div class="flex justify-center gap-2 pt-2">
                    <button type="button" wire:click="cancelDelete"
                        class="px-4 py-1.5 text-gray-500 bg-gray-100 hover:bg-gray-200 rounded-lg transition cursor-pointer text-xs">
                        إلغاء
                    </button>
                    <button type="button" wire:click="deleteGuarantee"
                        class="px-4 py-1.5 text-white bg-red-600 hover:bg-red-700 rounded-lg transition font-medium cursor-pointer text-xs">
                        نعم، احذف
                    </button>
                </div>
            </div>
        </div>
    @endif

</main>
