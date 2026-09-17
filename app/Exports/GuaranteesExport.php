<?php

namespace App\Exports;

use App\Models\guarantee as Guarantee;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class GuaranteesExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(
        protected string $search = '',
        protected string $guarantor = '',
        protected string $status = '',
        protected string $department = '',
        protected string $mosque = '',
        protected string $orphanStatus = '',
    ) {
    }

    public function query()
    {
        $query = Guarantee::query()
            ->leftJoin(
                'orphan',
                'guarantees.ssn',
                '=',
                'orphan.ssn'
            )
            ->leftJoin(
                'department',
                'guarantees.department_id',
                '=',
                'department.id'
            )
            ->leftJoin(
                'mosque',
                'guarantees.mosque_id',
                '=',
                'mosque.id'
            )
            ->select([
                'guarantees.*',
                'orphan.name as orphan_name',
                'department.name as department_name',
                'mosque.name as mosque_name',
            ]);

        /*
        |--------------------------------------------------------------------------
        | البحث
        |--------------------------------------------------------------------------
        */

        if (! empty($this->search)) {
            $term = $this->search;

            $query->where(function ($q) use ($term) {
                $q->where('guarantees.ssn', 'like', '%' . $term . '%')
                    ->orWhere('guarantees.guarantor', 'like', '%' . $term . '%')
                    ->orWhere('orphan.name', 'like', '%' . $term . '%');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | الكافل
        |--------------------------------------------------------------------------
        */

        if (! empty($this->guarantor)) {
            $query->where(
                'guarantees.guarantor',
                $this->guarantor
            );
        }

        /*
        |--------------------------------------------------------------------------
        | حالة الكفالة
        |--------------------------------------------------------------------------
        */

        if (! empty($this->status)) {
            $query->where(
                'guarantees.status',
                $this->status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | حالة تسجيل اليتيم
        |--------------------------------------------------------------------------
        */

        if ($this->orphanStatus === 'registered') {
            $query->whereNotNull('orphan.id');
        }

        if ($this->orphanStatus === 'not_registered') {
            $query->whereNull('orphan.id');
        }

        /*
        |--------------------------------------------------------------------------
        | المسجد / القسم
        |--------------------------------------------------------------------------
        */

        if (! empty($this->mosque)) {
            $query->where(
                'guarantees.mosque_id',
                $this->mosque
            );
        } elseif (! empty($this->department)) {
            $query->where(
                'guarantees.department_id',
                $this->department
            );
        }

        return $query->latest('guarantees.id');
    }

    public function headings(): array
    {
        return [
            'رقم الهوية',
            'اسم اليتيم',
            'اسم الكافل',
            'رقم الجوال',
            'الشعبة',
            'المسجد',
            'العنوان / المنزل',
            'قيمة الكفالة',
            'حالة الكفالة',
            'بداية الكفالة',
            'نهاية الكفالة',
            'حالة تسجيل اليتيم',
        ];
    }

    public function map($guarantee): array
    {
        return [
            $guarantee->ssn,

            // إذا كان مسجلاً نأخذ اسمه من جدول orphan
            // وإلا نأخذ الاسم المخزن في guarantees
            $guarantee->orphan_name ?: $guarantee->name,

            $guarantee->guarantor,
            $guarantee->mobile,

            $guarantee->department_name ?? 'غير محدد',
            $guarantee->mosque_name ?? 'غير محدد',

            $guarantee->home,

            $guarantee->amount,

            $guarantee->status,

            $guarantee->guarantor_start
                ? \Carbon\Carbon::parse($guarantee->guarantor_start)->format('Y-m-d')
                : '',

            $guarantee->guarantor_end
                ? \Carbon\Carbon::parse($guarantee->guarantor_end)->format('Y-m-d')
                : '',

            $guarantee->orphan_name
                ? 'مسجل'
                : 'غير مسجل',
        ];
    }
}
