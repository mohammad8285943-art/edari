<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BeneficiariesTemplateExport implements FromArray, WithHeadings, ShouldAutoSize
{
    /**
     * عناوين أعمدة نموذج المستفيدين
     */
    public function headings(): array
    {
        return [
            'رقم الهوية',
            'اسم المستفيد',
            'نوع المستفيد',
            'رقم الجوال',
            'رقم المحفظة',
            'القسم',
            'المسجد',
        ];
    }

    /**
     * بيانات النموذج
     *
     * نتركه فارغًا حتى يكون الملف جاهزًا للتعبئة.
     */
    public function array(): array
    {
        return [];
    }
}
