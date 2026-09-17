<main class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-6 relative text-xs bg-gray-50/50" dir="rtl">

    {{-- =========================================================
         الترويسة العلوية (شاشة فقط)
    ========================================================== --}}
    <div
        class="print-hidden flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100">

        <div class="flex items-center gap-3">
            <div class="p-3 bg-indigo-50 rounded-xl text-indigo-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div>
                <h2 class="text-base font-bold text-gray-800">{{ $orphan->name }}</h2>
                <p class="text-gray-400 text-[11px] mt-0.5">
                    رقم الهوية: <span class="font-mono text-gray-600">{{ $orphan->SSN }}</span>
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
            <button type="button" onclick="window.print()"
                class="print-hidden px-4 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium transition flex items-center gap-1.5 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2M6 14h12v8H6v-8z" />
                </svg>
                طباعة / PDF
            </button>

            <a href="{{ route('orphans.edit', $orphan->id) }}" wire:navigate
                class="print-hidden px-4 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition flex items-center gap-1.5 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                تعديل الملف
            </a>

            <a href="{{ route('orphan.index') }}" wire:navigate
                class="print-hidden px-4 py-1.5 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-lg font-medium transition">
                عودة للقائمة
            </a>
        </div>
    </div>


    {{-- =========================================================
         محتوى الصفحة
    ========================================================== --}}
    <div class="space-y-6 max-w-5xl mx-auto print-container">

        {{-- =====================================================
             بطاقة / استمارة اليتيم — نفس تنسيق ملف الوورد
        ====================================================== --}}
        <div class="print-data-page orphan-form bg-white p-5 rounded-xl border border-gray-200 shadow-sm">

            {{-- ------------------------------------------------
                 رأس الاستمارة: الصورة + العنوان
            ------------------------------------------------- --}}
            <div class="flex items-start justify-between gap-4 pb-3 mb-4 border-b-2 border-gray-800">
                <div class="shrink-0 w-28">
                    @if ($orphan->personal_image)
                        <img src="{{ asset('storage/' . $orphan->personal_image) }}" alt="صورة {{ $orphan->name }}"
                            class="w-28 h-36 object-cover rounded-md border border-gray-400">
                    @else
                        <div
                            class="w-28 h-36 rounded-md border border-dashed border-gray-300 bg-gray-50 flex items-center justify-center text-gray-300 text-[10px]">
                            لا توجد صورة
                        </div>
                    @endif
                </div>

                <h1 class="text-2xl font-extrabold text-gray-900 self-center flex-1 text-center sm:text-right sm:pl-4">
                    استمارة يتيم
                </h1>
            </div>

            {{-- ------------------------------------------------
                 1) المعلومات الشخصية
            ------------------------------------------------- --}}
            <h3 class="form-section-title">المعلومات الشخصية</h3>

            <table class="form-table">
                <tbody>
                    <tr>
                        <td class="lbl w-[16%]">الاسم الرباعي:</td>
                        <td class="val w-[34%]">{{ $orphan->name ?? '-' }}</td>
                        <td class="lbl w-[16%]">الجنس:</td>
                        <td class="val w-[34%]">{{ $orphan->sex ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">تاريخ الميلاد:</td>
                        <td class="val">{{ $orphan->barth ? $orphan->barth->format('d/m/Y') : '-' }}</td>
                        <td class="lbl">مكان الميلاد:</td>
                        <td class="val">غزة</td>
                    </tr>
                    <tr>
                        <td class="lbl">اسم الأم الثلاثي:</td>
                        <td class="val" colspan="3">{{ $orphan->m_name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">ولي الأمر:</td>
                        <td class="val">{{ $orphan->a_name ?? '-' }}</td>
                        <td class="lbl">صلة القرابة:</td>
                        <td class="val">{{ $orphan->relation ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>

            {{-- ------------------------------------------------
                 2) المعلومات العائلية
            ------------------------------------------------- --}}
            <h3 class="form-section-title">المعلومات العائلية</h3>

            <table class="form-table">
                <tbody>
                    <tr>
                        <td class="lbl">اسم المتوفى (الأب):</td>
                        <td class="val">{{ $orphan->f_name ?? '-' }}</td>
                        <td class="lbl">رقم هوية المتوفى (الأب):</td>
                        <td class="val">{{ $orphan->f_ssn ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">تاريخ وفاة الأب:</td>
                        <td class="val">{{ $orphan->f_date_d ? $orphan->f_date_d->format('d/m/Y') : '-' }}</td>
                        <td class="lbl">سبب وفاة الأب:</td>
                        <td class="val">{{ $orphan->f_d_s ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">هل الأم على قيد الحياة؟</td>
                        <td class="val">
                            @if (!empty($orphan->m_d))
                                {{ $orphan->m_d === 'حي' ? 'نعم' : 'لا' }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="lbl">تاريخ وفاة الأم:</td>
                        <td class="val">{{ $orphan->m_data_d ? $orphan->m_data_d->format('d/m/Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">سبب وفاة الأم:</td>
                        <td class="val" colspan="3">-</td>
                    </tr>
                    <tr>
                        <td class="lbl">عدد أفراد الأسرة:</td>
                        <td class="val" colspan="3">{{ $orphan->count_family ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>

            {{-- ------------------------------------------------
                3) المستوى التعليمي
            ------------------------------------------------- --}}
            <h3 class="form-section-title">المستوى التعليمي</h3>

            <table class="form-table">
                <tbody>
                    <tr>
                        <td class="lbl">هل يدرس اليتيم:</td>
                        <td class="val">نعم</td>
                        <td class="lbl">التحصيل الدراسي:</td>
                        <td class="val">-</td>
                    </tr>
                    <tr>
                        <td class="lbl">المستوى التعليمي:</td>
                        <td class="val">{{ $orphan->school_level ?? '-' }}</td>
                        <td class="lbl">التخصص (إذا كان جامعي):</td>
                        <td class="val">-</td>
                    </tr>
                    <tr>
                        <td class="lbl">سبب التوقف عن الدراسة:</td>
                        <td class="val" colspan="3">-</td>
                    </tr>
                </tbody>
            </table>

            {{-- ------------------------------------------------
                 4) الحالة الصحية
            ------------------------------------------------- --}}
            <h3 class="form-section-title">الحالة الصحية</h3>

            <table class="form-table">
                <tbody>
                    <tr>
                        <td class="lbl">الحالة الصحية:</td>
                        <td class="val">{{ $orphan->health ?? '-' }}</td>
                        <td class="lbl">نوع المرض (إذا كان اليتيم مريض):</td>
                        <td class="val">-</td>
                    </tr>
                </tbody>
            </table>

            {{-- ------------------------------------------------
                 5) عنوان اليتيم
            ------------------------------------------------- --}}
            <h3 class="form-section-title">عنوان اليتيم</h3>

            <table class="form-table">
                <tbody>
                    <tr>
                        <td class="lbl">نوع السكن (إيجار أو ملك):</td>
                        <td class="val">{{ $orphan->{'طبيعة_المسكن'} ?? '-' }}</td>
                        <td class="lbl">حالة السكن:</td>
                        <td class="val">{{ $orphan->{'حالة_المسكن_الأصلي'} ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">المدينة:</td>
                        <td class="val">{{ $orphan->{'المحافظة'} ?? '-' }}</td>
                        <td class="lbl">الحي:</td>
                        <td class="val">{{ $orphan->{'المدينة_الحي'} ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">الهاتف:</td>
                        <td class="val" colspan="3">{{ $orphan->mobile ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">ملاحظات إضافية:</td>
                        {{-- نص ثابت لجميع البطاقات كما طُلب، وليس من قاعدة البيانات --}}
                        <td class="val" colspan="3">
                            الاسرة التي ترعى اليتيم لا تملك أي مقومات ترقى لمساعدة اليتيم في تحقيق أحلامه بالتعلم و
                            الاستمرار في الدراسة و توفير حقيبة مدرسية و قرطاسية مثل باقي زملائه و الطالبات
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>


        {{-- =====================================================
             المرفقات والصور
        ====================================================== --}}
        @if ($orphan->birth_image || $orphan->father_image || $orphan->mother_image || $orphan->agent_image)

            <div class="print-attachments bg-white p-5 rounded-xl border border-gray-100 shadow-sm space-y-6">

                <h3
                    class="print-hidden text-sm font-bold text-rose-600 border-b border-rose-50 pb-2 flex items-center gap-2">
                    <span class="w-1.5 h-3.5 bg-rose-600 rounded-sm"></span>
                    المرفقات والوثائق الرسمية
                </h3>

                <div class="space-y-6">

                    @if ($orphan->birth_image)
                        <div class="print-attachment p-3 bg-gray-50 rounded-xl border border-gray-100 space-y-2">
                            <span class="block text-gray-700 font-bold text-xs">📄 شهادة الميلاد:</span>
                            <div class="flex justify-center bg-white p-2 rounded-lg border border-gray-200">
                                <img src="{{ asset('storage/' . $orphan->birth_image) }}" alt="شهادة الميلاد"
                                    class="w-auto max-w-full max-h-[600px] object-contain rounded-md shadow-sm">
                            </div>
                        </div>
                    @endif

                    @if ($orphan->father_image)
                        <div class="print-attachment p-3 bg-gray-50 rounded-xl border border-gray-100 space-y-2">
                            <span class="block text-gray-700 font-bold text-xs">📄 وثيقة / شهادة وفاة الأب:</span>
                            <div class="flex justify-center bg-white p-2 rounded-lg border border-gray-200">
                                <img src="{{ asset('storage/' . $orphan->father_image) }}" alt="وثيقة الأب"
                                    class="w-auto max-w-full max-h-[600px] object-contain rounded-md shadow-sm">
                            </div>
                        </div>
                    @endif

                    @if ($orphan->mother_image)
                        <div class="print-attachment p-3 bg-gray-50 rounded-xl border border-gray-100 space-y-2">
                            <span class="block text-gray-700 font-bold text-xs">📄 وثيقة / هوية الأم:</span>
                            <div class="flex justify-center bg-white p-2 rounded-lg border border-gray-200">
                                <img src="{{ asset('storage/' . $orphan->mother_image) }}" alt="وثيقة الأم"
                                    class="w-auto max-w-full max-h-[600px] object-contain rounded-md shadow-sm">
                            </div>
                        </div>
                    @endif

                    @if ($orphan->agent_image)
                        <div class="print-attachment p-3 bg-gray-50 rounded-xl border border-gray-100 space-y-2">
                            <span class="block text-gray-700 font-bold text-xs">📄 صورة هوية الوكيل:</span>
                            <div class="flex justify-center bg-white p-2 rounded-lg border border-gray-200">
                                <img src="{{ asset('storage/' . $orphan->agent_image) }}" alt="صورة الوكيل"
                                    class="w-auto max-w-full max-h-[600px] object-contain rounded-md shadow-sm">
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        @endif

    </div>
</main>


{{-- =============================================================
     تنسيق استمارة اليتيم (شاشة + طباعة)
============================================================= --}}
<style>
    /* عنوان القسم بالأحمر كما في ملف الوورد */
    .orphan-form .form-section-title {
        color: #c00000;
        font-weight: 800;
        font-size: 14px;
        margin: 14px 0 6px;
        text-align: center;
    }

    /* جدول الاستمارة بحدود سوداء تشبه جدول الوورد */
    .orphan-form .form-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        margin-bottom: 4px;
    }

    .orphan-form .form-table td {
        border: 1px solid #000;
        padding: 6px 8px;
        vertical-align: middle;
        font-size: 12px;
        word-break: break-word;
    }

    /* خلية التسمية: خلفية خضراء فاتحة + خط عريض، كما في ملف الوورد */
    .orphan-form .form-table td.lbl {
        background: #e9f3e6;
        font-weight: 700;
        color: #1f2937;
        text-align: right;
    }

    /* خلية القيمة */
    .orphan-form .form-table td.val {
        background: #ffffff;
        color: #111827;
        text-align: right;
    }

    @media print {

        @page {
            size: A4 portrait;
            margin: 8mm;
        }

        .print-hidden {
            display: none !important;
        }

        html,
        body {
            background: #ffffff !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        main {
            background: #ffffff !important;
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
            max-width: none !important;
            overflow: visible !important;
        }

        .print-container {
            max-width: none !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            display: block !important;
        }

        .print-data-page {
            width: 100% !important;
            page-break-after: always !important;
            break-after: page !important;
            display: block !important;
            box-shadow: none !important;
            border: none !important;
        }

        .orphan-form .form-table td {
            font-size: 10.5px !important;
            padding: 4px 6px !important;
        }

        .print-attachments {
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            background: #ffffff !important;
            border: none !important;
            box-shadow: none !important;
            display: block !important;
        }

        .print-attachment {
            page-break-before: always !important;
            break-before: page !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
            width: 100% !important;
            min-height: 270mm !important;
            margin: 0 !important;
            padding: 5mm !important;
            background: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 4mm !important;
            box-shadow: none !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: flex-start !important;
        }

        .print-attachment>span {
            display: block !important;
            width: 100% !important;
            text-align: right !important;
            font-size: 12px !important;
            font-weight: bold !important;
            padding-bottom: 3mm !important;
            margin-bottom: 4mm !important;
            border-bottom: 1px solid #e5e7eb !important;
        }

        .print-attachment>div {
            width: 100% !important;
            height: 245mm !important;
            max-height: 245mm !important;
            padding: 2mm !important;
            background: #ffffff !important;
            border: none !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            overflow: hidden !important;
        }

        .print-attachment img {
            display: block !important;
            width: auto !important;
            height: auto !important;
            max-width: 100% !important;
            max-height: 235mm !important;
            object-fit: contain !important;
            margin: auto !important;
            border-radius: 2mm !important;
            box-shadow: none !important;
        }

        .print-attachments .space-y-6> :not([hidden])~ :not([hidden]) {
            margin-top: 0 !important;
        }

        * {
            overflow: visible !important;
        }

        a::after {
            content: none !important;
        }
    }
</style>
