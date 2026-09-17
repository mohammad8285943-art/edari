<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>استمارة يتيم - {{ $orphan->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'dejavusans', 'Arial', sans-serif;
            direction: rtl;
            text-align: right;
            font-size: 12px;
            color: #1a202c;
            background-color: #ffffff;
            padding: 10px;
            line-height: 1.7;
        }

        /* رأس الصفحة */
        .header {
            text-align: center;
            padding: 12px 0 10px 0;
            border-bottom: 3px solid #1e3a8a;
            margin-bottom: 15px;
        }

        .header h1 {
            font-size: 24px;
            color: #1e3a8a;
            font-weight: bold;
            letter-spacing: 2px;
            font-family: 'dejavusans', 'Arial', sans-serif;
        }

        .header .sub {
            font-size: 12px;
            color: #475569;
            margin-top: 5px;
        }

        .header .sub span {
            background: #f1f5f9;
            padding: 3px 14px;
            border-radius: 20px;
            margin: 0 5px;
        }

        /* ===== تنسيق الجدول الرئيسي ===== */
        .personal-section {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            margin-bottom: 10px;
            background: #ffffff;
        }

        .personal-section > tbody > tr > td {
            vertical-align: top;
            border: none;
            padding: 0;
        }

        /* ===== عمود الصورة ===== */
        .personal-image-cell {
            width: 150px; /* ✅ عرض ثابت 150px */
            padding: 15px;
            background: #f8fafc;
            text-align: center;
            border-left: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        .personal-image-cell .image-label {
            display: block;
            font-size: 10px;
            color: #64748b;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .personal-image-cell img {
            width: 120px;  /* ✅ عرض ثابت للصورة */
            height: 160px; /* ✅ ارتفاع ثابت للصورة */
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #e2e8f0;
        }

        .personal-image-cell .no-image {
            width: 120px;
            height: 160px;
            border-radius: 8px;
            border: 2px dashed #cbd5e1;
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            color: #94a3b8;
            font-size: 11px;
            background: #f8fafc;
        }

        /* ===== عمود البيانات ===== */
        .personal-data-cell {
            padding: 12px 18px;
        }

        /* جدول البيانات الداخلي */
        .personal-data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .personal-data-table td {
            padding: 5px 10px;
            border: none;
            width: 33.33%;
            vertical-align: top;
        }

        .personal-data-table .label {
            font-size: 10.5px;
            color: #64748b;
            display: block;
            font-weight: 600;
        }

        .personal-data-table .value {
            font-size: 12.5px;
            font-weight: 500;
            color: #0f172a;
            display: block;
            padding: 2px 0;
        }

        .personal-data-table .special-status-cell {
            background: #eef2ff;
            padding: 6px 14px;
            border-radius: 6px;
        }

        .personal-data-table .special-status-cell .label {
            color: #4338ca;
            display: inline;
        }

        .personal-data-table .special-status-cell .value {
            color: #312e81;
            display: inline;
            font-weight: 600;
        }

        /* بقية التنسيقات */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
        }

        .info-table td {
            padding: 7px 12px;
            border: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        .info-table .label {
            background: #f8fafc;
            font-weight: 600;
            color: #1e293b;
            font-size: 11px;
            white-space: nowrap;
            width: 16%;
        }

        .info-table .value {
            font-weight: 500;
            color: #0f172a;
            font-size: 12px;
        }

        .info-table .value .badge {
            display: inline-block;
            padding: 2px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-green { background: #dcfce7; color: #15803d; }
        .badge-red { background: #fee2e2; color: #b91c1c; }
        .badge-gray { background: #f1f5f9; color: #334155; }
        .badge-blue { background: #dbeafe; color: #1d4ed8; }
        .badge-amber { background: #fef3c7; color: #b45309; }

        .section-title {
            font-size: 15px;
            font-weight: bold;
            color: #1e3a8a;
            padding: 8px 0 5px 0;
            margin: 12px 0 8px 0;
            border-bottom: 2px solid #1e3a8a;
        }

        .section-title .icon {
            font-size: 18px;
        }

        .notes-section {
            background: #fefce8;
            border: 1px solid #fde68a;
            border-radius: 8px;
            padding: 12px 16px;
            margin-top: 12px;
        }

        .notes-section .notes-title {
            font-weight: bold;
            color: #92400e;
            font-size: 13px;
            margin-bottom: 5px;
        }

        .notes-section .notes-content {
            font-size: 12px;
            color: #78350f;
            line-height: 1.8;
        }

        .attachments-grid {
            width: 100%;
            border-collapse: collapse;
        }

        .attachments-grid td {
            width: 50%;
            padding: 6px;
            vertical-align: top;
        }

        .attachment-item {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px;
            text-align: center;
            background: #fafafa;
        }

        .attachment-item .att-title {
            font-size: 11px;
            font-weight: 600;
            color: #475569;
            margin-bottom: 6px;
        }

        .attachment-item img {
            max-width: 100%;
            max-height: 220px;
            height: auto;
            border-radius: 4px;
        }

        .attachments-page {
            page-break-before: always;
            padding-top: 10px;
        }

        .attachments-page .section-title {
            font-size: 17px;
            margin-bottom: 15px;
        }

        .footer {
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
        }

        @page {
            margin: 12px;
        }

        /* ✅ تحسين عرض الصورة في mPDF */
        .personal-image-cell img {
            max-width: 120px;
            max-height: 160px;
        }
    </style>
</head>
<body>

    {{-- رأس الصفحة --}}
    <div class="header">
        <h1>استمارة يتيم</h1>
        <div class="sub">
            <span>🆔 رقم الهوية: {{ $orphan->SSN }}</span>
            <span>📅 تاريخ التصدير: {{ now()->format('Y-m-d') }}</span>
        </div>
    </div>

    {{-- القسم الأول: المعلومات الشخصية مع الصورة --}}
    <div class="section-title">
        <span class="icon">👤</span> المعلومات الشخصية
    </div>

    <table class="personal-section">
        <colgroup>
            <col style="width: 150px;">  {{-- عمود الصورة --}}
            <col style="width: auto;">   {{-- عمود البيانات --}}
        </colgroup>
        <tbody>
            <tr>
                {{-- الصورة الشخصية --}}
                <td class="personal-image-cell">
                    <span class="image-label">📷 الصورة الشخصية</span>
                    @if($orphan->personal_image && file_exists(public_path('storage/' . $orphan->personal_image)))
                        <img src="{{ public_path('storage/' . $orphan->personal_image) }}" alt="صورة شخصية">
                    @else
                        <div class="no-image">
                            <span>لا توجد صورة</span>
                        </div>
                    @endif
                </td>

                {{-- البيانات الشخصية --}}
                <td class="personal-data-cell">
                    <table class="personal-data-table">
                        <tbody>
                            <tr>
                                <td class="info-item">
                                    <span class="label">الاسم الرباعي</span>
                                    <span class="value">{{ $orphan->name }}</span>
                                </td>
                                <td class="info-item">
                                    <span class="label">الجنس</span>
                                    <span class="value">{{ $orphan->sex ?? '-' }}</span>
                                </td>
                                <td class="info-item">
                                    <span class="label">تاريخ الميلاد</span>
                                    <span class="value">{{ $orphan->barth ? $orphan->barth->format('d/m/Y') : '-' }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="info-item">
                                    <span class="label">مكان الميلاد</span>
                                    <span class="value">{{ $orphan->مكان_التواجد_الحالي ?? 'غزة' }}</span>
                                </td>
                                <td class="info-item">
                                    <span class="label">اسم الأم الثلاثي</span>
                                    <span class="value">{{ $orphan->m_name ?? '-' }}</span>
                                </td>
                                <td class="info-item">
                                    <span class="label">ولي الأمر</span>
                                    <span class="value">{{ $orphan->a_name ?? '-' }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="info-item">
                                    <span class="label">صلة القرابة</span>
                                    <span class="value">{{ $orphan->relation ?? '-' }}</span>
                                </td>
                                <td class="special-status-cell" colspan="2">
                                    <span class="label">⭐ تصنيف اليتيم الخاص:</span>
                                    <span class="value">{{ $orphan->حالة_اليتيم_الناجي_الوحيد_يتيم_الأبوين ?? 'لا يوجد تصنيف خاص' }}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>

    {{-- باقي الأقسام كما هي --}}
    <div class="section-title">
        <span class="icon">👨‍👩‍👧‍👦</span> المعلومات العائلية
    </div>

    <table class="info-table">
        <tr>
            <td class="label">اسم المتوفى (الأب)</td>
            <td class="value">{{ $orphan->f_name ?? '-' }}</td>
            <td class="label">رقم هوية المتوفى (الأب)</td>
            <td class="value">{{ $orphan->f_ssn ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">تاريخ وفاة الأب</td>
            <td class="value">{{ $orphan->f_date_d ? $orphan->f_date_d->format('d/m/Y') : '-' }}</td>
            <td class="label">سبب وفاة الأب</td>
            <td class="value">
                @if($orphan->f_d_s == 'شهيد')
                    <span class="badge badge-red">شهيد</span>
                @else
                    {{ $orphan->f_d_s ?? '-' }}
                @endif
            </td>
        </tr>
        <tr>
            <td class="label">هل الأم على قيد الحياة؟</td>
            <td class="value">
                @if($orphan->m_d == 'حي')
                    <span class="badge badge-green">نعم</span>
                @else
                    <span class="badge badge-red">لا</span>
                @endif
            </td>
            <td class="label">تاريخ وفاة الأم</td>
            <td class="value">{{ $orphan->m_data_d ? $orphan->m_data_d->format('d/m/Y') : '-' }}</td>
        </tr>
        <tr>
            <td class="label">سبب وفاة الأم</td>
            <td class="value" colspan="3">{{ $orphan->f_d_s ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">عدد الأخوة</td>
            <td class="value" colspan="3">
                <span style="margin-left:15px;">👨 ذكور: {{ $orphan->count_boys ?? '0' }}</span>
                <span>👩 إناث: {{ $orphan->count_girls ?? '0' }}</span>
                <span style="margin-right:15px; background:#fef3c7; padding:3px 14px; border-radius:20px; font-weight:600;">
                    حاجة اليتيم: {{ $orphan->حاجة_اليتيم ?? 'شديدة' }}
                </span>
            </td>
        </tr>
    </table>

    <div class="section-title">
        <span class="icon">📚</span> المستوى التعليمي
    </div>

    <table class="info-table">
        <tr>
            <td class="label">هل يدرس اليتيم</td>
            <td class="value">
                @if($orphan->school_level && $orphan->school_level != 'لا يدرس')
                    <span class="badge badge-green">نعم</span>
                @else
                    <span class="badge badge-red">لا</span>
                @endif
            </td>
            <td class="label">التحصيل الدراسي</td>
            <td class="value">{{ $orphan->school_level ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">المستوى التعليمي</td>
            <td class="value">{{ $orphan->school_level ?? 'طفل' }}</td>
            <td class="label">التخصص (إذا كان جامعي)</td>
            <td class="value">{{ $orphan->specialization ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">سبب التوقف عن الدراسة</td>
            <td class="value" colspan="3">{{ $orphan->study_stop_reason ?? 'لا يوجد' }}</td>
        </tr>
    </table>

    <div class="section-title">
        <span class="icon">🏥</span> الحالة الصحية
    </div>

    <table class="info-table">
        <tr>
            <td class="label">الحالة الصحية</td>
            <td class="value">
                @if($orphan->health == 'جيدة')
                    <span class="badge badge-green">جيدة</span>
                @elseif($orphan->health == 'متوسطة')
                    <span class="badge badge-amber">متوسطة</span>
                @else
                    <span class="badge badge-red">{{ $orphan->health ?? 'جيدة' }}</span>
                @endif
            </td>
            <td class="label">نوع المرض (إذا كان اليتيم مريض)</td>
            <td class="value">{{ $orphan->disease_type ?? 'لا يوجد' }}</td>
        </tr>
    </table>

    <div class="section-title">
        <span class="icon">📍</span> عنوان اليتيم
    </div>

    <table class="info-table">
        <tr>
            <td class="label">نوع السكن (إيجار أو ملك)</td>
            <td class="value">{{ $orphan->طبيعة_المسكن ?? 'ملك' }}</td>
            <td class="label">حالة السكن</td>
            <td class="value">
                @if($orphan->حالة_السكن == 'هدم كلي')
                    <span class="badge badge-red">هدم كلي</span>
                @elseif($orphan->حالة_السكن == 'هدم جزئي')
                    <span class="badge badge-amber">هدم جزئي</span>
                @else
                    <span class="badge badge-green">{{ $orphan->حالة_السكن ?? 'سليم' }}</span>
                @endif
            </td>
        </tr>
        <tr>
            <td class="label">المدينة</td>
            <td class="value">{{ $orphan->المحافظة ?? 'غزة' }}</td>
            <td class="label">الحي</td>
            <td class="value">{{ $orphan->المدينة_الحي ?? '-' }}</td>
        </tr>
    </table>

    <div class="notes-section">
        <div class="notes-title">📝 ملاحظات إضافية</div>
        <div class="notes-content">
            {{ $orphan->notes ?? 'الأسرة التي ترعى اليتيم لا تملك أي مقومات ترقى لمساعدة اليتيم في تحقيق أحلامها بالتعلم والاستمرار في الدراسة وتوفير حقيبة مدرسية وقرطاسية مثل باقي زملائها والطالبات' }}
        </div>
    </div>

    @php
        $hasAttachments = ($orphan->birth_image && file_exists(public_path('storage/' . $orphan->birth_image))) ||
                         ($orphan->father_image && file_exists(public_path('storage/' . $orphan->father_image))) ||
                         ($orphan->mother_image && file_exists(public_path('storage/' . $orphan->mother_image))) ||
                         ($orphan->agent_image && file_exists(public_path('storage/' . $orphan->agent_image)));
    @endphp

    @if($hasAttachments)
    <div class="attachments-page">
        <div class="section-title">
            <span class="icon">📎</span> المرفقات والوثائق
        </div>

        <table class="attachments-grid">
            <tr>
                @if($orphan->birth_image && file_exists(public_path('storage/' . $orphan->birth_image)))
                    <td>
                        <div class="attachment-item">
                            <div class="att-title">📄 شهادة الميلاد</div>
                            <img src="{{ public_path('storage/' . $orphan->birth_image) }}" alt="شهادة الميلاد">
                        </div>
                    </td>
                @endif

                @if($orphan->father_image && file_exists(public_path('storage/' . $orphan->father_image)))
                    <td>
                        <div class="attachment-item">
                            <div class="att-title">📄 شهادة وفاة الأب</div>
                            <img src="{{ public_path('storage/' . $orphan->father_image) }}" alt="شهادة وفاة الأب">
                        </div>
                    </td>
                @endif
            </tr>
            <tr>
                @if($orphan->mother_image && file_exists(public_path('storage/' . $orphan->mother_image)))
                    <td>
                        <div class="attachment-item">
                            <div class="att-title">📄 شهادة وفاة الأم</div>
                            <img src="{{ public_path('storage/' . $orphan->mother_image) }}" alt="شهادة وفاة الأم">
                        </div>
                    </td>
                @endif

                @if($orphan->agent_image && file_exists(public_path('storage/' . $orphan->agent_image)))
                    <td>
                        <div class="attachment-item">
                            <div class="att-title">🪪 صورة هوية الوكيل</div>
                            <img src="{{ public_path('storage/' . $orphan->agent_image) }}" alt="هوية الوكيل">
                        </div>
                    </td>
                @endif
            </tr>
        </table>
    </div>
    @endif

    <div class="footer">
        تم إنشاء هذا التقرير بواسطة النظام | جميع الحقوق محفوظة © {{ now()->year }}
    </div>

</body>
</html>
