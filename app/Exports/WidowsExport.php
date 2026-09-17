<?php

namespace App\Exports;

use App\Models\widow as Widow;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class WidowsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected array $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Widow::query()
            ->with(['mosque', 'department']);

        if (! empty($this->filters['searchName'])) {
            $query->where('name', 'like', '%' . $this->filters['searchName'] . '%');
        }

        if (! empty($this->filters['searchSsn'])) {
            $query->where('ssn', 'like', '%' . $this->filters['searchSsn'] . '%');
        }

        if (! empty($this->filters['searchHusband'])) {
            $query->where('husband', 'like', '%' . $this->filters['searchHusband'] . '%');
        }

        if (! empty($this->filters['searchHusbandSsn'])) {
            $query->where('h_ssn', 'like', '%' . $this->filters['searchHusbandSsn'] . '%');
        }

        if (($this->filters['orphanStatus'] ?? '') === 'with') {
            $query->where('orphan_count', '>', 0);
        } elseif (($this->filters['orphanStatus'] ?? '') === 'without') {
            $query->where('orphan_count', '=', 0);
        }

        if (! empty($this->filters['mosque_id'])) {
            $query->where('mosque_id', $this->filters['mosque_id']);
        } elseif (! empty($this->filters['department_id'])) {
            $query->where('department_id', $this->filters['department_id']);
        }

        return $query->latest('id');
    }

    public function headings(): array
    {
        return [
            'اسم الأرملة',
            'رقم الهوية',
            'الجوال',
            'العمل',
            'اسم الزوج',
            'هوية الزوج',
            'تاريخ الوفاة',
            'عنوان السكن',
            'عمل الزوج',
            'عدد الأيتام',
            'الشعبة',
            'المسجد',
            'جوال غزة',
        ];
    }

    public function map($widow): array
    {
        return [
            $widow->name,
            $widow->ssn,
            $widow->mobile ?? '-',
            $widow->job ?? '-',
            $widow->husband,
            $widow->h_ssn ?? '-',
            $widow->date_death ?? '-',
            $widow->address ?? '-',
            $widow->h_job ?? '-',
            $widow->orphan_count,
            $widow->department?->name ?? 'غير محدد',
            $widow->mosque?->name ?? 'غير محدد',
            $widow->gaz_mobile ?? '-',
        ];
    }
}
