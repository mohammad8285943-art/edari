<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrphansExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $query;

    public function __construct($query)
    {
        // نستقبل الكويري المفلترة من الكومبوننت
        $this->query = $query;
    }

    public function query()
    {
        return $this->query;
    }

    /**
     * جلب جميع أسماء الأعمدة (العناوين) بالترتيب المكتوب في الـ fillable
     */
    public function headings(): array
    {
        return [
            'المعرف (ID)',
            'رقم الهوية (SSN)',
            'الاسم الكامل',
            'تاريخ الميلاد',
            'العمر',
            'الجنس',
            'الحالة الصحية',
            'الضرر/الإصابة',
            'المستوى التعليمي',
            'شهيد/متوفى',
            'هوية الأب',
            'اسم الأب',
            'الحالة الاجتماعية للأب',
            'حالة وفاة الاب',
            'تاريخ وفاة الأب',
            'عمل الأب',
            'لجوء الأب',
            'عدد أفراد العائلة',
            'هوية الأم',
            'اسم الأم',
            'حالة وفاة الام',
            'تاريخ وفاة الأم',
            'الحالة الاجتماعية للأم',
            'جوال غاز الأم',
            'عمل الأم',
            'هوية الوكيل',
            'اسم الوكيل',
            'جنس الوكيل',
            'الحالة الاجتماعية للوكيل',
            'صلة القرابة',
            'رقم الجوال 1',
            'رقم الجوال 2',
            'اعتماد الوكيل من',
            'مصدر البيانات',
            'طبيعة المسكن',
            'عنوان المسكن الأصلي',
            'طبيعة الإقامة الحالية',
            'عنوان الإقامة الحالية',
            'حالة المسكن الأصلي',
            'مكان التواجد الحالي',
            'المحافظة',
            'المدينة / الحي',
            'رقم الشعبة',
            'رقم المسجد',
            'حالة اليتيم الناجي الوحيد يتيم الأبوين',
            'الشعبة',
            'المسجد'
        ];
    }

    /**
     * تفريغ وتوزيع كافة بيانات الأعمدة لكل صف
     */
    public function map($orphan): array
    {
        return [
            $orphan->id,
            $orphan->SSN,
            $orphan->name,
            $orphan->barth ? $orphan->barth->format('Y-m-d') : '',
            $orphan->age,
            $orphan->sex,
            $orphan->health,
            $orphan->damage,
            $orphan->school_level,
            $orphan->f_d_s,
            $orphan->f_ssn,
            $orphan->f_name,
            $orphan->f_marital,
            $orphan->f_d,
            $orphan->f_date_d ? $orphan->f_date_d->format('Y-m-d') : '',
            $orphan->f_work,
            $orphan->f_Asylum,
            $orphan->count_family,
            $orphan->m_ssn,
            $orphan->m_name,
            $orphan->m_d,
            $orphan->m_data_d ? $orphan->m_data_d->format('Y-m-d') : '',
            $orphan->m_marital,
            $orphan->m_gas_mobile,
            $orphan->m_work,
            $orphan->a_ssn,
            $orphan->a_name,
            $orphan->a_sex,
            $orphan->a_marital,
            $orphan->relation,
            $orphan->mobile,
            $orphan->mobile2,
            $orphan->{'اعتماد الوكيل من'}, // للتعامل مع المسافات في اسم العمود
            $orphan->مصدر_البيانات,
            $orphan->طبيعة_المسكن,
            $orphan->عنوان_المسكن_الأصلي,
            $orphan->طبيعة_الإقامة_الحالية,
            $orphan->عنوان_الإقامة_الحالية,
            $orphan->حالة_المسكن_الأصلي,
            $orphan->مكان_التواجد_الحالي,
            $orphan->المحافظة,
            $orphan->المدينة_الحي,
            $orphan->department_id,
            $orphan->mosque_id,
            $orphan->حالة_اليتيم_الناجي_الوحيد_يتيم_الأبوين,
            
            // علاقات إضافية لجلب الأسماء النصية للشعبة والمسجد بدلاً من الأرقام فقط
            $orphan->department?->name ?? 'غير محدد',
            $orphan->mosque?->name ?? 'غير محدد',
        ];
    }

    /**
     * تنسيقات ملف الإكسل (Indigo Header)
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5']
                ]
            ],
        ];
    }
}