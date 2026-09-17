<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class guarantee extends Model
{
    protected $table = 'guarantees';

    protected $fillable = [
        'ssn',
        'name',
        'mobile',
        'mosque_id',
        'department_id',
        'home',
        'guarantor',
        'guarantor_start',
        'guarantor_end',
        'amount',
        'status'
    ];

    public function mosque()
    {
        return $this->belongsTo(Mosque::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function orphan():BelongsTo
    {
        return $this->belongsTo(Orphan::class, 'ssn', 'SSN');
    }
}
