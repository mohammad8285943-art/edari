<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Orphan extends Model
{
    use HasFactory,SoftDeletes;

    // اسم الجدول في قاعدة البيانات
    protected $table = 'orphan';

    // اسم العمود الأساسي (Primary Key)
    protected $primaryKey = 'id';

    // إذا كانت قاعدة البيانات لا تدعم التوقيت التلقائي (created_at, updated_at)، اجعلها false
    // جدولك لا يحتوي على هذه الأعمدة حالياً، لذلك سنقوم بإيقافها
    public $timestamps = false;

    /**
     * الأعمدة القابلة للتعبئة (Mass Assignable)
     */
    protected $fillable = [
        'SSN',
        'name',
        'barth',
        'age',
        'sex',
        'health',
        'damage',
        'school_level',
        'f_d_s',
        'f_ssn',
        'f_name',
        'f_marital',
        'f_d',
        'f_date_d',
        'f_work',
        'f_Asylum',
        'count_family',
        'm_ssn',
        'm_name',
        'm_d',
        'm_data_d',
        'm_marital',
        'm_gas_mobile',
        'm_work',
        'a_ssn',
        'a_name',
        'a_sex',
        'a_marital',
        'relation',
        'mobile',
        'mobile2',
        'اعتماد الوكيل من',
        'مصدر_البيانات',
        'طبيعة_المسكن',
        'عنوان_المسكن_الأصلي',
        'طبيعة_الإقامة_الحالية',
        'عنوان_الإقامة_الحالية',
        'حالة_المسكن_الأصلي',
        'مكان_التواجد_الحالي',
        'المحافظة',
        'المدينة_الحي',
        'department_id',
        'mosque_id',
        'حالة_اليتيم_الناجي_الوحيد_يتيم_الأبوين',
        'personal_image',
        'birth_image',
        'father_image',
        'mother_image',
        'agent_image',
        'wallet_number',
    'wallet_owner_name',
    'wallet_type',
    ];

    /**
     * تحويل أنواع البيانات تلقائياً عند التعامل مع المودل (Casting)
     */
    protected $casts = [
        'barth' => 'date',
        'f_date_d' => 'date',
        'm_data_d' => 'date',
        'age' => 'double',
        'id' => 'integer',
        'SSN' => 'integer',
        'f_ssn' => 'integer',
        'm_ssn' => 'integer',
        'm_gas_mobile' => 'integer',
        'a_ssn' => 'integer',
        'count_family' => 'integer',
        'department_id' => 'integer',
        'mosque_id' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | العلاقات (Relationships)
    |--------------------------------------------------------------------------
    */

    /**
     * علاقة اليتيم بالقسم الخاص به
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    /**
     * علاقة اليتيم بالمسجد التابع له
     */
    public function mosque(): BelongsTo
    {
        return $this->belongsTo(Mosque::class, 'mosque_id', 'id');
    }

    /**
     * علاقة اليتيم بالضامن الخاص به
     */
    public function guarantees():HasMany
    {
        return $this->hasMany(guarantee::class, 'ssn', 'SSN');
    }

    public function widow(): BelongsTo
    {
        return $this->belongsTo(widow::class, 'm_ssn', 'ssn');
    }
}
