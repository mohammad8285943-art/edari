<?php

namespace App\Imports;

use App\Models\AidBeneficiary;
use App\Models\Orphan;
use App\Models\widow;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

class AidBeneficiariesImport implements ToModel, WithHeadingRow
{
    protected int $aidId;
    protected string $defaultBeneficiaryType;

    public function __construct(
        int $aidId,
        string $defaultBeneficiaryType = 'orphan'
    ) {
        $this->aidId = $aidId;
        $this->defaultBeneficiaryType = $defaultBeneficiaryType;

        // المحافظة على أسماء الأعمدة كما هي في ملف Excel
        HeadingRowFormatter::default('none');
    }

    public function model(array $row)
    {
        // قراءة الأعمدة العربية
        $ssn = trim((string) ($row['رقم الهوية'] ?? ''));
        $name = trim((string) ($row['اسم المستفيد'] ?? ''));

        // إذا كان أحد الحقلين الأساسيين فارغًا نتجاهل الصف
        if ($ssn === '' || $name === '') {
            return null;
        }

        // منع التكرار داخل نفس المساعدة
        $exists = AidBeneficiary::where('aid_id', $this->aidId)
            ->where('ssn', $ssn)
            ->exists();

        if ($exists) {
            return null;
        }

        // تحديد نوع المستفيد
        $rawType = trim((string) ($row['نوع المستفيد'] ?? ''));

        $beneficiaryType = $this->resolveType($rawType);

        // البحث عن المستفيد في النظام
        $beneficiaryId = null;

        if ($beneficiaryType === 'orphan') {

            $orphan = Orphan::where('SSN', $ssn)->first();

            $beneficiaryId = $orphan?->id;

        } elseif ($beneficiaryType === 'widow') {

            $widow = widow::where('ssn', $ssn)->first();

            $beneficiaryId = $widow?->id;
        }

        return new AidBeneficiary([
            'aid_id'           => $this->aidId,
            'beneficiary_type' => $beneficiaryType,
            'beneficiary_id'   => $beneficiaryId,
            'ssn'              => $ssn,
            'name'             => $name,

            'mobile' => $this->value($row, 'رقم الجوال'),

            'wallet_number' => $this->value($row, 'رقم المحفظة'),

            'department' => $this->value($row, 'القسم'),

            'mosque' => $this->value($row, 'المسجد'),
        ]);
    }

    /**
     * الحصول على قيمة العمود مع إزالة المسافات
     */
    protected function value(array $row, string $column): ?string
    {
        $value = $row[$column] ?? null;

        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        return $value !== '' ? $value : null;
    }

    /**
     * تحديد نوع المستفيد
     */
    protected function resolveType(string $type): string
    {
        $type = trim(mb_strtolower($type));

        if (
            str_contains($type, 'أرمل') ||
            str_contains($type, 'ارمل') ||
            $type === 'widow'
        ) {
            return 'widow';
        }

        if (
            str_contains($type, 'يتيم') ||
            $type === 'orphan'
        ) {
            return 'orphan';
        }

        if (
            str_contains($type, 'خارج') ||
            $type === 'external'
        ) {
            return 'external';
        }

        // إذا لم يوجد نوع في الملف نستخدم نوع المساعدة
        return $this->defaultBeneficiaryType;
    }
}
