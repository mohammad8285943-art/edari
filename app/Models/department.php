<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class department extends Model
{

    // table name department
    protected $table = 'department';
    protected $fillable = ['name'];

    public function mosques()
    {
        return $this->hasMany(mosque::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function Orphans()
    {
        return $this->hasMany(Orphan::class);
    }
}
