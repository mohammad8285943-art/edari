<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Aid extends Model
{
    use HasFactory;

    protected $table = 'aids';

    protected $fillable = [
        'name',
        'type',
        'beneficiary_type',
        'amount',
        'count',
        'donor',
        'execution_place',
        'date_execution',
        'date_nomination',
        'status',
    ];

    protected $casts = [
        'date_execution' => 'date',
        'date_nomination' => 'date',
        'amount' => 'double',
    ];

    public function beneficiaries(): HasMany
    {
        return $this->hasMany(AidBeneficiary::class, 'aid_id');
    }

    // حساب عدد المستفيدين الفعليين
    public function getBeneficiariesCountAttribute(): int
    {
        return $this->beneficiaries()->count();
    }

    // حساب إجمالي قيمة المساعدة تلقائيًا
    public function getTotalAmountAttribute(): float
    {
        return (float) ($this->beneficiaries()->count() * $this->amount);
    }
}
