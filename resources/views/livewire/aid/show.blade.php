<main class="flex-1 overflow-y-auto p-4 space-y-4 relative text-xs">
    {{-- أنماط الطباعة و PDF المحسّنة --}}
    <style>
        @media print {
            @page {
                size: A4 portrait;
                margin: 12mm 10mm;
            }

            body {
                background: white !important;
                color: #000 !important;
                font-size: 10pt !important;
                direction: rtl !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .no-print,
            header,
            nav,
            aside,
            button,
            .pagination {
                display: none !important;
            }

            .print-only {
                display: block !important;
            }

            .printable-card {
                border: 1.5px solid #111827 !important;
                box-shadow: none !important;
                page-break-inside: avoid;
                margin-bottom: 15px !important;
            }

            table {
                width: 100% !important;
                border-collapse: collapse !important;
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            th,
            td {
                border: 1px solid #374151 !important;
                padding: 5px 8px !important;
            }

            .print-header-bg {
                background-color: #f3f4f6 !important;
                color: #000 !important;
            }
        }
    </style>

    {{-- ترويسة خاصة بالطباعة والتقارير الرسمية --}}
    <div class="hidden print-only text-center pb-3 border-b-2 border-gray-800 mb-4">
        <h1 class="text-xl font-extrabold text-black">كشف مستفيدي المساعدة</h1>
        <p class="text-xs text-gray-600 mt-1">تاريخ استخراج التقرير: {{ now()->format('Y-m-d H:i') }}</p>
    </div>

    {{-- بطاقة بيانات المساعدة (جدول معلومات متناسق للشاشة والطباعة) --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden printable-card">
        {{-- شريط العنوان والعمليات --}}
        <div
            class="p-4 bg-gray-50/75 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div class="flex items-center gap-3">
                <h2 class="text-base font-bold text-gray-900">{{ $aid->name }}</h2>
                @if ($aid->status === 'executed')
                    <span
                        class="bg-emerald-100 text-emerald-800 border border-emerald-300 px-2.5 py-0.5 rounded-full text-xs font-bold print-header-bg">
                        منفذة
                    </span>
                @else
                    <span
                        class="bg-amber-100 text-amber-800 border border-amber-300 px-2.5 py-0.5 rounded-full text-xs font-bold print-header-bg">
                        مرشحة
                    </span>
                @endif
            </div>

            {{-- أزرار التحكم والطباعة --}}
            <div class="flex items-center gap-2 no-print">
                @can('orphan.aids.nominate')
                    @if ($aid->status !== 'executed')
                        <a href="{{ route('orphans.aids.nomination', $aid->id) }}"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg font-medium transition flex items-center gap-1.5 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                            ترشيح المستفيدين
                        </a>

                        <button type="button" wire:click="openCreateBeneficiaryModal"
                            class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg font-medium transition flex items-center gap-1.5 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            إضافة مستفيد
                        </button>

                        <button type="button" wire:click="openImportModal"
                            class="bg-teal-600 hover:bg-teal-700 text-white px-3 py-1.5 rounded-lg font-medium transition flex items-center gap-1.5 shadow-sm text-xs">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            استيراد إكسل
                        </button>
                    @endif
                @endcan

                @can('orphan.aids.export')
                    <button type="button" onclick="window.print()"
                        class="bg-slate-700 hover:bg-slate-800 text-white px-3 py-1.5 rounded-lg font-medium transition flex items-center gap-1.5 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        طباعة / PDF
                    </button>
                @endcan
            </div>
        </div>

        {{-- جدول تفاصيل المساعدة الموحد للطباعة والشاشة --}}
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse text-xs">
                <tbody>
                    <tr class="border-b border-gray-200">
                        <th class="w-1/6 bg-gray-50 print-header-bg p-2.5 font-bold text-gray-700">نوع المساعدة</th>
                        <td class="w-2/6 p-2.5 font-semibold text-gray-900">{{ $aid->type ?? '-' }}</td>
                        <th class="w-1/6 bg-gray-50 print-header-bg p-2.5 font-bold text-gray-700">فئة المستفيدين</th>
                        <td class="w-2/6 p-2.5 font-semibold text-gray-900">
                            @if ($aid->beneficiary_type === 'orphan')
                                أيتام
                            @elseif($aid->beneficiary_type === 'widow')
                                أرامل
                            @else
                                كلاهما / عام
                            @endif
                        </td>
                    </tr>
                    <tr class="border-b border-gray-200">
                        <th class="bg-gray-50 print-header-bg p-2.5 font-bold text-gray-700">مبلغ الفرد</th>
                        <td class="p-2.5 font-bold text-emerald-700">{{ number_format($aid->amount, 2) }} شيكل</td>
                        <th class="bg-gray-50 print-header-bg p-2.5 font-bold text-gray-700">العدد المستهدف / المسجل
                        </th>
                        <td class="p-2.5 text-gray-900 font-medium">
                            <span class="font-bold text-indigo-700">{{ $beneficiaries->total() }}</span>
                            مسجل من أصل
                            <span class="font-bold">{{ $aid->count ?? 'غير محدد' }}</span> مستهدف
                        </td>
                    </tr>
                    <tr class="border-b border-gray-200">
                        <th class="bg-gray-50 print-header-bg p-2.5 font-bold text-gray-700">تاريخ الترشيح</th>
                        <td class="p-2.5 text-gray-800">
                            {{ $aid->date_nomination ? \Carbon\Carbon::parse($aid->date_nomination)->format('Y-m-d') : '-' }}
                        </td>
                        <th class="bg-gray-50 print-header-bg p-2.5 font-bold text-gray-700">تاريخ التنفيذ</th>
                        <td class="p-2.5 text-gray-800">
                            {{ $aid->date_execution ? \Carbon\Carbon::parse($aid->date_execution)->format('Y-m-d') : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-gray-50 print-header-bg p-2.5 font-bold text-gray-700">الجهة المانحة</th>
                        <td class="p-2.5 text-gray-800 font-semibold">{{ $aid->donor ?? '-' }}</td>
                        <th class="bg-gray-50 print-header-bg p-2.5 font-bold text-gray-700">مكان التنفيذ</th>
                        <td class="p-2.5 text-gray-800">{{ $aid->execution_place ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="p-3 bg-green-100 text-green-800 border border-green-200 rounded-lg font-medium no-print">
            {{ session('message') }}
        </div>
    @endif

    {{-- شريط البحث --}}
    {{-- شريط البحث والفلاتر --}}
    <div
        class="bg-white p-3 rounded-xl border border-gray-100 shadow-sm flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3 no-print">
        <div class="flex flex-col sm:flex-row items-center gap-2.5 flex-1">
            {{-- حقل البحث العام --}}
            <div class="w-full sm:w-72">
                <input type="text" wire:model.live.debounce.300ms="search"
                    placeholder="ابحث بالاسم أو الهوية أو الجوال..."
                    class="w-full px-2.5 py-1.5 border border-gray-300 rounded-lg outline-none focus:border-indigo-500 text-xs">
            </div>

            {{-- فلتر الشعبة / القسم (يظهر فقط لمن يملك صلاحية عرض كاملة view == 1) --}}
            @if (auth()->user()->view == 1)
                <div class="w-full sm:w-44">
                    <select wire:model.live="filterDepartment"
                        class="w-full px-2.5 py-1.5 border border-gray-300 rounded-lg outline-none focus:border-indigo-500 text-xs bg-white">
                        <option value="">كل الشعب / الأقسام</option>
                        @foreach ($filterDepartments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            {{-- فلتر المسجد (يظهر للمستوى 1 والمستوى 2 فقط) --}}
            @if (auth()->user()->view != 3)
                <div class="w-full sm:w-44">
                    <select wire:model.live="filterMosque"
                        class="w-full px-2.5 py-1.5 border border-gray-300 rounded-lg outline-none focus:border-indigo-500 text-xs bg-white">
                        <option value="">كل المساجد</option>
                        @foreach ($filterMosques as $mosque)
                            <option value="{{ $mosque->id }}">{{ $mosque->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>

        <div class="text-gray-500 font-medium text-left sm:text-right whitespace-nowrap">
            المستفيدون الظاهرون: <span class="font-bold text-gray-800">{{ $beneficiaries->total() }}</span>
        </div>
    </div>







    {{-- جدول المستفيدين --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden text-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-gray-700 font-bold print-header-bg">
                        <th class="p-2.5 w-10 text-center">#</th>
                        <th class="p-2.5">اسم المستفيد</th>
                        <th class="p-2.5">رقم الهوية</th>
                        <th class="p-2.5">رقم الجوال</th>
                        <th class="p-2.5">رقم المحفظة</th>
                        <th class="p-2.5">القسم / المسجد</th>
                        <th class="p-2.5">النوع</th>
                        <th class="p-2.5">قيمة المساعدة</th>
                                    @if ($aid->status !== 'executed')
                        <th class="p-2.5 text-center no-print">الإجراءات</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($beneficiaries as $item)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="p-2.5 text-center text-gray-500 font-medium">{{ $loop->iteration }}</td>
                            <td class="p-2.5 font-bold text-gray-900">{{ $item->name }}</td>
                            <td class="p-2.5 font-medium text-gray-800">{{ $item->ssn }}</td>
                            <td class="p-2.5">{{ $item->mobile ?? '-' }}</td>
                            <td class="p-2.5">{{ $item->wallet_number ?? '-' }}</td>
                            <td class="p-2.5">
                                <div class="font-medium text-gray-800">{{ $item->department ?? '-' }}</div>
                                <div class="text-[11px] text-gray-500">{{ $item->mosque ?? '-' }}</div>
                            </td>
                            <td class="p-2.5">
                                @if ($item->beneficiary_type === 'orphan')
                                    <span
                                        class="bg-blue-50 text-blue-700 border border-blue-200 px-2 py-0.5 rounded text-[11px]">يتيم</span>
                                @elseif ($item->beneficiary_type === 'widow')
                                    <span
                                        class="bg-purple-50 text-purple-700 border border-purple-200 px-2 py-0.5 rounded text-[11px]">أرملة</span>
                                @else
                                    <span
                                        class="bg-gray-100 text-gray-700 border border-gray-200 px-2 py-0.5 rounded text-[11px]">خارجي</span>
                                @endif
                            </td>
                            <td class="p-2.5 font-bold text-emerald-700">{{ number_format($aid->amount, 2) }} شيكل
                            </td>
                            <td class="p-2.5 text-center no-print">
                                <div class="flex items-center justify-center gap-1">
                                    @if ($aid->status !== 'executed')
                                        @can('orphan.aids.nominate.update')
                                            <button type="button"
                                                wire:click="openEditBeneficiaryModal({{ $item->id }})"
                                                class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                                title="تعديل">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                        @endcan
                                        @can('orphan.aids.nominate.delete')
                                            <button type="button" wire:click="confirmDelete({{ $item->id }})"
                                                class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition"
                                                title="حذف">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        @endcan
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-8 text-center text-gray-400 bg-gray-50/50">
                                لا يوجد مستفيدين مضافين لهذه المساعدة حتى الآن.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3 border-t border-gray-200 bg-gray-50/50 no-print">
            {{ $beneficiaries->links() }}
        </div>
    </div>

    {{-- مودال إضافة وتعديل المستفيد --}}
    @if ($showBeneficiaryModal)
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4 no-print"
            wire:click.self="closeBeneficiaryModal">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-xl max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between p-4 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800">
                        {{ $editingBeneficiaryId ? 'تعديل بيانات المستفيد المسجل بالمساعدة' : 'إضافة مستفيد جديد' }}
                    </h3>
                    <button type="button" wire:click="closeBeneficiaryModal"
                        class="text-gray-400 hover:text-gray-600">
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
                            <label class="block font-medium text-gray-500 mb-1">نوع المستفيد</label>
                            <select wire:model.live="b_type"
                                class="w-full px-2 py-1.5 border border-gray-300 rounded-lg outline-none focus:border-indigo-500 bg-white">
                                <option value="orphan">يتيم مسجل</option>
                                <option value="widow">أرملة مسجلة</option>
                                <option value="external">مستفيد خارجي غير مسجل</option>
                            </select>
                            @error('b_type')
                                <span class="text-red-600 text-[11px]">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-medium text-gray-500 mb-1">رقم الهوية</label>
                            <input type="text" wire:model.live.debounce.400ms="b_ssn"
                                placeholder="أدخل رقم الهوية..."
                                class="w-full px-2.5 py-1.5 border border-gray-300 rounded-lg outline-none focus:border-indigo-500">
                            @error('b_ssn')
                                <span class="text-red-600 text-[11px]">{{ $message }}</span>
                            @enderror
                            @if ($previewName)
                                <div
                                    class="mt-1 text-[11px] text-emerald-700 bg-emerald-50 border border-emerald-100 rounded px-2 py-0.5">
                                    مسجل بالنظام: {{ $previewName }}
                                </div>
                            @endif
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block font-medium text-gray-500 mb-1">اسم المستفيد</label>
                            <input type="text" wire:model="b_name"
                                class="w-full px-2.5 py-1.5 border border-gray-300 rounded-lg outline-none focus:border-indigo-500">
                            @error('b_name')
                                <span class="text-red-600 text-[11px]">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-medium text-gray-500 mb-1">رقم الجوال</label>
                            <input type="text" wire:model="b_mobile"
                                class="w-full px-2.5 py-1.5 border border-gray-300 rounded-lg outline-none focus:border-indigo-500">
                            @error('b_mobile')
                                <span class="text-red-600 text-[11px]">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-medium text-gray-500 mb-1">رقم المحفظة الإلكترونية</label>
                            <input type="text" wire:model="b_wallet_number"
                                class="w-full px-2.5 py-1.5 border border-gray-300 rounded-lg outline-none focus:border-indigo-500">
                            @error('b_wallet_number')
                                <span class="text-red-600 text-[11px]">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-medium text-gray-500 mb-1">الشعبة / القسم</label>
                            <select wire:model="b_department"
                                class="w-full px-2 py-1.5 border border-gray-300 rounded-lg outline-none focus:border-indigo-500 bg-white">
                                <option value="">اختر القسم</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->name }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                            @error('b_department')
                                <span class="text-red-600 text-[11px]">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-medium text-gray-500 mb-1">المسجد</label>
                            <select wire:model="b_mosque"
                                class="w-full px-2 py-1.5 border border-gray-300 rounded-lg outline-none focus:border-indigo-500 bg-white">
                                <option value="">اختر المسجد</option>
                                @foreach ($mosques as $mosque)
                                    <option value="{{ $mosque->name }}">{{ $mosque->name }}</option>
                                @endforeach
                            </select>
                            @error('b_mosque')
                                <span class="text-red-600 text-[11px]">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-1.5 p-4 border-t border-gray-50">
                    <button type="button" wire:click="closeBeneficiaryModal"
                        class="px-3 py-1.5 text-gray-500 bg-gray-100 hover:bg-gray-200 rounded-lg transition text-xs">
                        إلغاء
                    </button>
                    <button type="button" wire:click="saveBeneficiary"
                        class="px-4 py-1.5 text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition font-medium shadow-sm text-xs">
                        حفظ المستفيد
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- تأكيد حذف المستفيد --}}
    @if ($confirmingDeleteId)
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4 no-print"
            wire:click.self="cancelDelete">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-sm p-5 text-center space-y-3">
                <h3 class="font-bold text-gray-800">تأكيد الإزالة</h3>
                <p class="text-gray-500 text-xs">هل أنت متأكد من إزالة هذا المستفيد من هذه المساعدة؟</p>
                <div class="flex justify-center gap-2 pt-2">
                    <button type="button" wire:click="cancelDelete"
                        class="px-4 py-1.5 text-gray-500 bg-gray-100 hover:bg-gray-200 rounded-lg transition text-xs">
                        إلغاء
                    </button>
                    <button type="button" wire:click="deleteBeneficiary"
                        class="px-4 py-1.5 text-white bg-red-600 hover:bg-red-700 rounded-lg transition font-medium text-xs">
                        نعم، احذف
                    </button>
                </div>
            </div>
        </div>
    @endif
    {{-- مودال رفع واستيراد كشف الإكسل --}}
    @if ($showImportModal)
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4 no-print"
            wire:click.self="closeImportModal">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-md overflow-hidden">
                <div class="flex items-center justify-between p-4 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        استيراد كشف مستفيدين من إكسل
                    </h3>
                    <button type="button" wire:click="closeImportModal" class="text-gray-400 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-5 space-y-4 text-xs">
                    {{-- تنزيل القالب --}}
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                        <div class="font-bold text-amber-900 mb-1">تعليمات وتنسيق الملف:</div>
                        <p class="text-amber-700 mb-2.5 leading-relaxed">
                            يجب أن يحتوي السطر الأول على العناوين بالعربية (رقم الهوية، اسم المستفيد، نوع المستفيد، رقم
                            الجوال، رقم المحفظة، القسم، المسجد).
                        </p>
                        <button type="button" wire:click="downloadTemplate"
                            class="inline-flex items-center gap-1.5 text-xs font-semibold text-amber-800 bg-white border border-amber-300 hover:bg-amber-100 px-3 py-1.5 rounded-md transition shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            تحميل نموذج الإكسل الجاهز
                        </button>
                    </div>

                    {{-- حقل رفع الملف --}}
                    <div>
                        <label class="block font-medium text-gray-700 mb-1.5">اختر ملف الإكسل (XLSX, XLS, CSV):</label>
                        <input type="file" wire:model="excelFile" accept=".xlsx,.xls,.csv"
                            class="w-full text-xs text-gray-700 file:mr-0 file:ml-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 border border-gray-300 rounded-lg cursor-pointer bg-gray-50/50 p-1">

                        <div wire:loading wire:target="excelFile" class="text-indigo-600 font-medium mt-1">
                            جاري تجهيز ورفع الملف...
                        </div>

                        @error('excelFile')
                            <span class="text-red-600 text-[11px] block mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-1.5 p-4 border-t border-gray-100 bg-gray-50">
                    <button type="button" wire:click="closeImportModal"
                        class="px-3 py-1.5 text-gray-600 bg-white border border-gray-300 hover:bg-gray-100 rounded-lg transition text-xs">
                        إلغاء
                    </button>
                    <button type="button" wire:click="importExcel" wire:loading.attr="disabled"
                        class="px-4 py-1.5 text-white bg-teal-600 hover:bg-teal-700 rounded-lg transition font-medium shadow-sm text-xs flex items-center gap-1">
                        <span wire:loading.remove wire:target="importExcel">بدء الاستيراد والحفظ</span>
                        <span wire:loading wire:target="importExcel">جاري الاستيراد...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</main>
