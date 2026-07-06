<?php

namespace App\Livewire\Forms;

use App\Models\Orphan;
use Carbon\Carbon;
use Livewire\Form;
use Illuminate\Validation\Rule;

class OrphanFormRequest extends Form
{
    public ?Orphan $orphan = null;

    // الحقول الأساسية لليتيم
    public $SSN = '';
    public $name = '';
    public $barth = '';
    public $age = 0;
    public $sex = '';
    public $health = 'جيدة';
    public $damage = '';
    public $school_level = '';
    public $حالة_اليتيم_الناجي_الوحيد_يتيم_الأبوين = '';

    // بيانات الأب
    public $f_d_s = '';
    public $f_ssn = '';
    public $f_name = '';
    public $f_marital = '';
    public $f_d = '';
    public $f_date_d = '';
    public $f_work = '';
    public $f_Asylum = '';
    public $count_family = '';

    // بيانات الأم
    public $m_ssn = '';
    public $m_name = '';
    public $m_d = 'حي';
    public $m_data_d = '';
    public $m_marital = '';
    public $m_gas_mobile = '';
    public $m_work = '';

    // بيانات الوكيل
    public $a_ssn = '';
    public $a_name = '';
    public $a_sex = '';
    public $a_marital = '';
    public $relation = '';
    public $اعتماد_الوكيل_من = ''; // تم استبدال الفراغ لتسهيل التعامل بالخصائص

    // العناوين والاتصال
    public $mobile = '';
    public $mobile2 = '';
    public $مصدر_البيانات = '';
    public $طبيعة_المسكن = '';
    public $عنوان_المسكن_الأصلي = '';
    public $طبيعة_الإقامة_الحالية = '';
    public $عنوان_الإقامة_الحالية = '';
    public $حالة_المسكن_الأصلي = '';
    public $مكان_التواجد_الحالي = '';
    public $المحافظة = '';
    public $المدينة_الحي = '';
    public $department_id = '';
    public $mosque_id = '';

    public function setOrphan(Orphan $orphan)
    {
        $this->orphan = $orphan;

        $this->SSN = $orphan->SSN;
        $this->name = $orphan->name;
        $this->barth = $orphan->barth ? $orphan->barth->format('Y-m-d') : '';
        $this->age = $orphan->age;
        $this->sex = $orphan->sex;
        $this->health = $orphan->health;
        $this->damage = $orphan->damage;
        $this->school_level = $orphan->school_level;
        $this->حالة_اليتيم_الناجي_الوحيد_يتيم_الأبوين = $orphan->حالة_اليتيم_الناجي_الوحيد_يتيم_الأبوين;
        $this->f_d_s = $orphan->f_d_s;
        $this->f_ssn = $orphan->f_ssn;
        $this->f_name = $orphan->f_name;
        $this->f_marital = $orphan->f_marital;
        $this->f_d = $orphan->f_d;
        $this->f_date_d = $orphan->f_date_d ? $orphan->f_date_d->format('Y-m-d') : '';
        $this->f_work = $orphan->f_work;
        $this->f_Asylum = $orphan->f_Asylum;
        $this->count_family = $orphan->count_family;
        $this->m_ssn = $orphan->m_ssn;
        $this->m_name = $orphan->m_name;
        $this->m_d = $orphan->m_d;
        $this->m_data_d = $orphan->m_data_d ? $orphan->m_data_d->format('Y-m-d') : '';
        $this->m_marital = $orphan->m_marital;
        $this->m_gas_mobile = $orphan->m_gas_mobile;
        $this->m_work = $orphan->m_work;
        $this->a_ssn = $orphan->a_ssn;
        $this->a_name = $orphan->a_name;
        $this->a_sex = $orphan->a_sex;
        $this->a_marital = $orphan->a_marital;
        $this->relation = $orphan->relation;
        $this->اعتماد_الوكيل_من = $orphan->getAttribute('اعتماد الوكيل من');
        $this->مصدر_البيانات = $orphan->مصدر_البيانات;
        $this->طبيعة_المسكن = $orphan->طبيعة_المسكن;
        $this->عنوان_المسكن_الأصلي = $orphan->عنوان_المسكن_الأصلي;
        $this->طبيعة_الإقامة_الحالية = $orphan->طبيعة_الإقامة_الحالية;
        $this->عنوان_الإقامة_الحالية = $orphan->عنوان_الإقامة_الحالية;
        $this->حالة_المسكن_الأصلي = $orphan->حالة_المسكن_الأصلي;
        $this->مكان_التواجد_الحالي = $orphan->مكان_التواجد_الحالي;
        $this->المحافظة = $orphan->المحافظة;
        $this->المدينة_الحي = $orphan->المدينة_الحي;
        $this->department_id = $orphan->department_id;
        $this->mosque_id = $orphan->mosque_id;
    }

    public function rules(): array
    {
        $orphanId = $this->orphan ? $this->orphan->id : 'NULL';

        return [
            // الحقول المطلوبة الأساسية بناءً على طلبك والـ DB
            'SSN' => ['required', 'digits:9', Rule::unique('orphan', 'SSN')->ignore($orphanId)],
            'name' => ['required', 'string', 'max:100', Rule::unique('orphan', 'name')->ignore($orphanId)],
            'barth' => ['required', 'date', 'before:today'],
            'sex' => ['required', 'in:ذكر,أنثى'],
            'health' => ['required', 'string', 'max:255'],
            
            // باقي الحقول اختيارية أو مشروطة بالفحص (Nullable)
            'damage' => ['nullable', 'string', 'max:255'],
            'school_level' => ['nullable', 'string', 'max:10'],
            'حالة_اليتيم_الناجي_الوحيد_يتيم_الأبوين' => ['nullable', 'string', 'max:255'],
            'f_d_s' => ['nullable', 'string', 'max:10'],
            'f_ssn' => ['nullable', 'digits:9'],
            'f_name' => ['nullable', 'string', 'max:100'],
            'f_marital' => ['nullable', 'string', 'max:20'],
            'f_d' => ['nullable', 'string', 'max:10'],
            'f_date_d' => ['nullable', 'date'],
            'f_work' => ['nullable', 'string', 'max:100'],
            'f_Asylum' => ['nullable', 'string', 'max:100'],
            'count_family' => ['nullable', 'integer', 'min:1'],
            'm_ssn' => ['nullable', 'digits:9'],
            'm_name' => ['nullable', 'string', 'max:100'],
            'm_d' => ['nullable', 'string', 'max:10'],
            'm_data_d' => ['nullable', 'date'],
            'm_marital' => ['nullable', 'string', 'max:10'],
            'm_gas_mobile' => ['nullable', 'numeric'],
            'm_work' => ['nullable', 'string', 'max:50'],
            'a_ssn' => ['nullable', 'digits:9'],
            'a_name' => ['nullable', 'string', 'max:100'],
            'a_sex' => ['nullable', 'in:ذكر,أنثى'],
            'a_marital' => ['nullable', 'string', 'max:10'],
            'relation' => ['nullable', 'string', 'max:10'],
            'mobile' => ['nullable', 'string', 'max:15'],
            'mobile2' => ['nullable', 'string', 'max:15'],
            'اعتماد_الوكيل_من' => ['nullable', 'string', 'max:20'],
            'مصدر_البيانات' => ['nullable', 'string', 'max:20'],
            'طبيعة_المسكن' => ['nullable', 'string', 'max:255'],
            'عنوان_المسكن_الأصلي' => ['nullable', 'string', 'max:255'],
            'طبيعة_الإقامة_الحالية' => ['nullable', 'string', 'max:255'],
            'عنوان_الإقامة_الحالية' => ['nullable', 'string', 'max:255'],
            'حالة_المسكن_الأصلي' => ['nullable', 'string', 'max:255'],
            'مكان_التواجد_الحالي' => ['nullable', 'string', 'max:255'],
            'المحافظة' => ['nullable', 'string', 'max:255'],
            'المدينة_الحي' => ['nullable', 'string', 'max:255'],
            'department_id' => ['nullable', 'exists:department,id'],
            'mosque_id' => ['nullable', 'exists:mosque,id'],
        ];
    }

    public function validationAttributes(): array
    {
        return [
            'SSN' => 'هوية اليتيم',
            'name' => 'اسم اليتيم الكامل',
            'barth' => 'تاريخ الميلاد',
            'sex' => 'الجنس',
            'health' => 'الحالة الصحية',
            'department_id' => 'القسم',
            'mosque_id' => 'المسجد',
        ];
    }

    // دالة لحساب العمر تلقائياً بناءً على تاريخ الميلاد
    public function updatedBarth($value)
    {
        if ($value) {
            $this->age = Carbon::parse($value)->diffInYears(Carbon::now());
        }
    }

    public function save(): void
    {
        $this->validate();

        // تجهيز مصفوفة البيانات الأساسية
        $data = $this->except(['orphan', 'اعتماد_الوكيل_من']);
        
        // إعادة تعيين الحقل الذي يحتوي على فراغات في الـ DB بشكل صحيح
        $data['اعتماد الوكيل من'] = $this->اعتماد_الوكيل_من;
        
        // حساب العمر بدقة قبل الحفظ
        if ($this->barth) {
            $data['age'] = Carbon::parse($this->barth)->floatDiffInYears(Carbon::now());
        }

        if ($this->orphan) {
            $this->orphan->update($data);
        } else {
            Orphan::create($data);
        }
    }
}