<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class mosque extends Model
{
    // table name
    protected $table = 'mosque';
    protected $fillable = ['name', 'department_id'];

    public function department()
    {
        return $this->belongsTo(department::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function orphans()
    {
        return $this->hasMany(Orphan::class);
    }
}
