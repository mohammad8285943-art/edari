<main class="flex-1 overflow-y-auto p-4 space-y-4 relative text-xs">
    {{-- قسم الإحصائيات العلوي وأزرار التحكم --}}
    <div
        class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-white p-3 rounded-xl shadow-sm border border-gray-100">
        <div class="flex flex-wrap items-center gap-2">
            <div class="bg-indigo-50 border border-indigo-100 px-3 py-1.5 rounded-lg flex items-center gap-3">
                <span class="text-gray-600 font-medium">إجمالي المساعدات:</span>
                <span class="text-indigo-600 font-bold text-base">{{ $totalCount }}</span>
            </div>

            <div class="bg-emerald-50 border border-emerald-100 px-3 py-1.5 rounded-lg flex items-center gap-3">
                <span class="text-gray-600 font-medium">المنفذة:</span>
                <span class="text-emerald-600 font-bold text-base">{{ $executedCount }}</span>
            </div>

            <div class="bg-amber-50 border border-amber-100 px-3 py-1.5 rounded-lg flex items-center gap-3">
                <span class="text-gray-600 font-medium">المرشحة:</span>
                <span class="text-amber-600 font-bold text-base">{{ $nominatedCount }}</span>
            </div>
        </div>
        @can('orphan.aids.create')
            <div class="flex flex-col sm:flex-row items-center gap-2 w-full sm:w-auto">
                <button type="button" wire:click="openCreateModal"
                    class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-1.5 rounded-lg font-medium transition flex items-center justify-center gap-1.5 cursor-pointer shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    إضافة مساعدة
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
                تصفية المساعدات
            </h4>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-2.5">
            <div>
                <label class="block font-medium text-gray-500 mb-1">بحث شامل (اسم / مانح / مكان)</label>
                <input type="text" wire:model="search" placeholder="ابحث هنا..."
                    class="w-full px-2.5 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500 text-xs">
            </div>

            <div>
                <label class="block font-medium text-gray-500 mb-1">النوع</label>
                <select wire:model="filterType"
                    class="w-full px-2 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500 text-xs bg-white">
                    <option value="">كل الأنواع</option>
                    @foreach ($types as $t)
                        <option value="{{ $t }}">{{ $t }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-medium text-gray-500 mb-1">الفئة</label>
                <select wire:model="filterBeneficiaryType"
                    class="w-full px-2 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500 text-xs bg-white">
                    <option value="">كل الفئات</option>
                    <option value="orphan">أيتام</option>
                    <option value="widow">أرامل</option>
                </select>
            </div>

            <div>
                <label class="block font-medium text-gray-500 mb-1">الحالة</label>
                <select wire:model="filterStatus"
                    class="w-full px-2 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500 text-xs bg-white">
                    <option value="">كل الحالات</option>
                    <option value="nominated">مرشحة</option>
                    <option value="executed">منفذة</option>
                </select>
            </div>
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

    {{-- جدول المساعدات --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden text-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-gray-500 font-bold uppercase tracking-wider">
                        <th class="p-3">اسم المساعدة</th>
                        <th class="p-3">النوع / الفئة</th>
                        <th class="p-3">قيمة الفرد</th>
                        <th class="p-3">المستفيدين / الإجمالي</th>
                        <th class="p-3">التاريخ / المانح</th>
                        <th class="p-3">الحالة</th>
                        <th class="p-3 text-center">العمليات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($aids as $aid)
                        <tr class="hover:bg-gray-50/30 transition">
                            <td class="p-3">
                                @can('orphan.aids.nominate.view')
                                    <a href="{{ route('orphans.aids.show', $aid->id) }}"
                                        class="font-bold text-indigo-600 hover:underline">
                                        {{ $aid->name }}
                                    </a>
                                @endcan
                                @cannot('orphan.aids.nominate.view')
                                    <span class="font-bold text-gray-800">{{ $aid->name }}</span>
                                @endcannot
                                <div class="text-[11px] text-gray-400 mt-0.5">
                                    {{ $aid->execution_place ?? 'المكان غير محدد' }}</div>
                            </td>
                            <td class="p-3">
                                <span
                                    class="bg-gray-100 text-gray-700 px-2 py-0.5 rounded text-[11px] font-medium">{{ $aid->type }}</span>
                                <span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded text-[11px] font-medium">
                                    {{ $aid->beneficiary_type === 'orphan' ? 'أيتام' : 'أرامل' }}
                                </span>
                            </td>
                            <td class="p-3">
                                <span class="font-bold text-emerald-700">{{ number_format($aid->amount, 2) }}
                                    شيكل</span>
                            </td>
                            <td class="p-3">
                                <div class="font-bold text-gray-900">{{ $aid->beneficiaries_count }} مستفيد</div>
                                <div class="text-[11px] text-gray-500 mt-0.5">
                                    الإجمالي: <span
                                        class="font-bold text-emerald-600">{{ number_format($aid->beneficiaries_count * $aid->amount, 2) }}</span>
                                </div>
                            </td>
                            <td class="p-3">
                                <div>
                                    @if ($aid->date_execution)
                                        {{ $aid->date_execution?->format('Y-m-d') ?? '-' }}
                                    @elseif($aid->date_nomination)
                                        {{ $aid->date_nomination?->format('Y-m-d') ?? '-' }}
                                    @else
                                        -
                                    @endif
                                </div>
                                <div class="text-[11px] text-gray-400 mt-0.5">{{ $aid->donor ?? 'المانح غير محدد' }}
                                </div>
                            </td>
                            <td class="p-3">
                                @if ($aid->status === 'executed')
                                    <span
                                        class="bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-full text-[11px] font-bold">منفذة</span>
                                @else
                                    <span
                                        class="bg-amber-100 text-amber-800 px-2 py-0.5 rounded-full text-[11px] font-bold">مرشحة</span>
                                @endif
                            </td>
                            <td class="p-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    @can('orphan.aids.nominate.view')
                                        <a href="{{ route('orphans.aids.show', $aid->id) }}"
                                            class="inline-flex items-center justify-center p-1.5 text-indigo-600 hover:bg-indigo-50 rounded-lg transition"
                                            title="تفاصيل المساعدة">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                    @endcan
                                    @can('orphan.aids.update')
                                        <button type="button" wire:click="openEditModal({{ $aid->id }})"
                                            class="inline-flex items-center justify-center p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                            title="تعديل المساعدة">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                    @endcan
                                    @can('orphan.aids.delete')
                                        <button type="button" wire:click="confirmDelete({{ $aid->id }})"
                                            class="inline-flex items-center justify-center p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition"
                                            title="حذف المساعدة">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-gray-400 bg-gray-50/50">
                                لا توجد مساعدات مسجلة تطابق التصفية.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3 border-t border-gray-100 bg-gray-50/50">
            {{ $aids->links() }}
        </div>
    </div>

    {{-- مودال الإضافة / التعديل --}}
    @if ($showModal)
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4" wire:click.self="closeModal">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-xl max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between p-4 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800">
                        {{ $editingId ? 'تعديل بيانات المساعدة' : 'إضافة مساعدة جديدة' }}
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
                        <div class="sm:col-span-2">
                            <label class="block font-medium text-gray-500 mb-1">اسم المساعدة</label>
                            <input type="text" wire:model="form_name" placeholder="مثال: كسوة الشتاء"
                                class="w-full px-2.5 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500">
                            @error('form_name')
                                <span class="text-red-600 text-[11px]">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-medium text-gray-500 mb-1">نوع المساعدة</label>
                            <select wire:model="form_type"
                                class="w-full px-2 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500 bg-white">
                                <option value="">اختر نوع المساعدة</option>
                                <option value="طرد">طرد</option>
                            <option value="نقدي">نقدي</option>
                            <option value="كسوة">كسوة</option>
                            <option value="تعليم">تعليم</option>
                            </select>
                            @error('form_type')
                                <span class="text-red-600 text-[11px]">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-medium text-gray-500 mb-1">فئة المستفيدين</label>
                            <select wire:model="form_beneficiary_type"
                                class="w-full px-2 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500 bg-white">
                                <option value="orphan">أيتام</option>
                                <option value="widow">أرامل</option>
                            </select>
                            @error('form_beneficiary_type')
                                <span class="text-red-600 text-[11px]">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-medium text-gray-500 mb-1">قيمة الفرد (شيكل)</label>
                            <input type="number" step="0.01" wire:model="form_amount"
                                class="w-full px-2.5 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500">
                            @error('form_amount')
                                <span class="text-red-600 text-[11px]">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block font-medium text-gray-500 mb-1">عدد المستفيدين</label>
                            <input type="number" wire:model="form_count"
                                class="w-full px-2.5 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500">
                            @error('form_count')
                                <span class="text-red-600 text-[11px]">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-medium text-gray-500 mb-1">تاريخ التنفيذ</label>
                            <input type="date" wire:model="form_date_execution"
                                class="w-full px-2.5 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500">
                            @error('form_date_execution')
                                <span class="text-red-600 text-[11px]">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-medium text-gray-500 mb-1">تاريخ الترشيح</label>
                            <input type="date" wire:model="form_date_nomination"
                                class="w-full px-2.5 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500">
                            @error('form_date_nomination')
                                <span class="text-red-600 text-[11px]">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-medium text-gray-500 mb-1">الجهة المانحة</label>
                            <input type="text" wire:model="form_donor" placeholder="اسم المؤسسة"
                                class="w-full px-2.5 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500">
                            @error('form_donor')
                                <span class="text-red-600 text-[11px]">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-medium text-gray-500 mb-1">مكان التنفيذ</label>
                            <input type="text" wire:model="form_execution_place" placeholder="المسجد"
                                class="w-full px-2.5 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500">
                            @error('form_execution_place')
                                <span class="text-red-600 text-[11px]">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block font-medium text-gray-500 mb-1">حالة المساعدة</label>
                            <select wire:model="form_status"
                                class="w-full px-2 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500 bg-white">
                                <option value="nominated">مرشحة </option>
                                <option value="executed">منفذة</option>
                            </select>
                            @error('form_status')
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
                        {{ $editingId ? 'حفظ التعديلات' : 'إضافة المساعدة' }}
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
                <p class="text-gray-500 text-xs">هل أنت متأكد من حذف هذه المساعدة؟ سيتم حذف جميع سجلات المستفيدين
                    المقترنة بها.</p>
                <div class="flex justify-center gap-2 pt-2">
                    <button type="button" wire:click="cancelDelete"
                        class="px-4 py-1.5 text-gray-500 bg-gray-100 hover:bg-gray-200 rounded-lg transition cursor-pointer text-xs">
                        إلغاء
                    </button>
                    <button type="button" wire:click="deleteAid"
                        class="px-4 py-1.5 text-white bg-red-600 hover:bg-red-700 rounded-lg transition font-medium cursor-pointer text-xs">
                        نعم، احذف
                    </button>
                </div>
            </div>
        </div>
    @endif
</main>
