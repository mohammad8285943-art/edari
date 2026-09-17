<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class widow extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'ssn',
        'mobile',
        'job',
        'husband',
        'h_ssn',
        'date_death',
        'address',
        'h_job',
        'orphan_count',
        'mosque_id',
        'department_id',
        'gaz_mobile'
    ];

    public function mosque()
    {
        return $this->belongsTo(Mosque::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function orphans()
    {
        return $this->hasMany(Orphan::class, 'm_ssn', 'ssn');
    }
}
