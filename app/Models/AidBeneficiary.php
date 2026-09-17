<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AidBeneficiary extends Model
{
    use HasFactory;

    protected $table = 'aid_beneficiaries';

    protected $fillable = [
        'aid_id',
        'beneficiary_type',
        'beneficiary_id',
        'ssn',
        'name',
        'mobile',
        'wallet_number',
        'mosque',
        'department',
    ];

    public function aid(): BelongsTo
    {
        return $this->belongsTo(Aid::class, 'aid_id');
    }

    // إرجاع السجل الأصلي إذا كان مسجلاً بالنظام
    public function getOriginalBeneficiaryAttribute()
    {
        if ($this->beneficiary_type === 'orphan') {
            return Orphan::find($this->beneficiary_id);
        }

        if ($this->beneficiary_type === 'widow') {
            return widow::find($this->beneficiary_id);
        }

        return null;
    }
}
