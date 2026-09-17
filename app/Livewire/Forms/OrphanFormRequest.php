<?php

namespace App\Livewire\Forms;

use App\Models\Orphan;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Livewire\Form;
use Livewire\WithFileUploads;

class OrphanFormRequest extends Form
{
    use WithFileUploads;

    public ?Orphan $orphan = null;

    // =========================
    // بيانات اليتيم الأساسية
    // =========================
    public $SSN = '';

    public $name = '';

    public $barth = '';

    public $age = 0;

    public $sex = '';

    public $health = 'جيدة';

    public $damage = '';

    public $school_level = '';

    public $wallet_number = null;

    public $wallet_owner_name = null;

    public $wallet_type = null;

    public $حالة_اليتيم_الناجي_الوحيد_يتيم_الأبوين = '';

    // =========================
    // بيانات الأب
    // =========================
    public $f_d_s = '';

    public $f_ssn = '';

    public $f_name = '';

    public $f_marital = '';

    public $f_d = '';

    public $f_date_d = '';

    public $f_work = '';

    public $f_Asylum = '';

    public $count_family = '';

    // =========================
    // بيانات الأم
    // =========================
    public $m_ssn = '';

    public $m_name = '';

    public $m_d = 'حي';

    public $m_data_d = '';

    public $m_marital = '';

    public $m_gas_mobile = '';

    public $m_work = '';

    // =========================
    // بيانات الوكيل
    // =========================
    public $a_ssn = '';

    public $a_name = '';

    public $a_sex = '';

    public $a_marital = '';

    public $relation = '';

    public $اعتماد_الوكيل_من = '';

    // =========================
    // العناوين والاتصال
    // =========================
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

    // =========================
    // ملفات الصور الجديدة
    // =========================
    public $personal_image_file;

    public $birth_image_file;

    public $father_image_file;

    public $mother_image_file;

    public $agent_image_file;

    // مسارات الصور الحالية عند التعديل
    public $current_personal_image = null;

    public $current_birth_image = null;

    public $current_father_image = null;

    public $current_mother_image = null;

    public $current_agent_image = null;

    /**
     * تحميل بيانات اليتيم في الفورم
     */
    public function setOrphan(Orphan $orphan): void
    {
        $this->orphan = $orphan;

        // البيانات الأساسية
        $this->SSN = $orphan->SSN;
        $this->name = $orphan->name;
        $this->barth = $orphan->barth
            ? Carbon::parse($orphan->barth)->format('Y-m-d')
            : '';

        $this->age = $orphan->age;
        $this->sex = $orphan->sex;
        $this->health = $orphan->health;
        $this->damage = $orphan->damage;
        $this->school_level = $orphan->school_level;
        $this->wallet_number = $orphan->wallet_number;
        $this->wallet_owner_name = $orphan->wallet_owner_name;
        $this->wallet_type = $orphan->wallet_type;

        $this->حالة_اليتيم_الناجي_الوحيد_يتيم_الأبوين =
            $orphan->حالة_اليتيم_الناجي_الوحيد_يتيم_الأبوين;

        // الأب
        $this->f_d_s = $orphan->f_d_s;
        $this->f_ssn = $orphan->f_ssn;
        $this->f_name = $orphan->f_name;
        $this->f_marital = $orphan->f_marital;
        $this->f_d = $orphan->f_d;

        $this->f_date_d = $orphan->f_date_d
            ? Carbon::parse($orphan->f_date_d)->format('Y-m-d')
            : '';

        $this->f_work = $orphan->f_work;
        $this->f_Asylum = $orphan->f_Asylum;
        $this->count_family = $orphan->count_family;

        // الأم
        $this->m_ssn = $orphan->m_ssn;
        $this->m_name = $orphan->m_name;
        $this->m_d = $orphan->m_d;

        $this->m_data_d = $orphan->m_data_d
            ? Carbon::parse($orphan->m_data_d)->format('Y-m-d')
            : '';

        $this->m_marital = $orphan->m_marital;
        $this->m_gas_mobile = $orphan->m_gas_mobile;
        $this->m_work = $orphan->m_work;

        // الوكيل
        $this->a_ssn = $orphan->a_ssn;
        $this->a_name = $orphan->a_name;
        $this->a_sex = $orphan->a_sex;
        $this->a_marital = $orphan->a_marital;
        $this->relation = $orphan->relation;

        $this->اعتماد_الوكيل_من =
            $orphan->getAttribute('اعتماد الوكيل من');

        // الاتصال والعنوان
        $this->mobile = $orphan->mobile;
        $this->mobile2 = $orphan->mobile2;

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

        // الصور الحالية
        $this->current_personal_image = $orphan->personal_image;
        $this->current_birth_image = $orphan->birth_image;
        $this->current_father_image = $orphan->father_image;
        $this->current_mother_image = $orphan->mother_image;
        $this->current_agent_image = $orphan->agent_image;
    }

    /**
     * Validation
     */
    public function rules(): array
    {
        $orphanId = $this->orphan?->id;

        return [

            // الأساسية
            'SSN' => [
                'required',
                'digits:9',
                Rule::unique('orphan', 'SSN')->ignore($orphanId),
            ],

            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('orphan', 'name')->ignore($orphanId),
            ],

            'barth' => [
                'required',
                'date',
                'before:today',
            ],

            'sex' => [
                'required',
                'in:ذكر,أنثى',
            ],

            'health' => [
                'required',
                'string',
                'max:255',
            ],

            'damage' => [
                'nullable',
                'string',
                'max:255',
            ],

            'school_level' => [
                'nullable',
                'string',
                'max:10',
            ],

            // حالة اليتيم
            'حالة_اليتيم_الناجي_الوحيد_يتيم_الأبوين' => [
                '',
                Rule::in([
                    'ناجي وحيد',
                    'يتيم الأبوين',
                    'يتيم الأب',
                    'يتيم الأم',
                    'أب مفقود',
                ]),
            ],
            'wallet_number' => ['nullable', 'string', 'max:50'],
            'wallet_owner_name' => ['nullable', 'string', 'max:255'],
            'wallet_type' => ['nullable', 'in:بنك فلسطين,بال باي,جوال باي'],

            // الأب
            'f_d_s' => ['nullable', 'string', 'max:10'],
            'f_ssn' => ['nullable', 'digits:9'],
            'f_name' => ['nullable', 'string', 'max:100'],
            'f_marital' => ['nullable', 'string', 'max:20'],
            'f_d' => ['nullable', 'string', 'max:10'],
            'f_date_d' => ['nullable', 'date'],
            'f_work' => ['nullable', 'string', 'max:100'],
            'f_Asylum' => ['nullable', 'string', 'max:100'],
            'count_family' => ['nullable', 'integer', 'min:1'],

            // الأم
            'm_ssn' => ['nullable', 'digits:9'],
            'm_name' => ['nullable', 'string', 'max:100'],
            'm_d' => ['nullable', 'string', 'max:20'],
            'm_data_d' => ['nullable', 'date'],
            'm_marital' => ['nullable', 'string', 'max:50'],
            'm_gas_mobile' => ['nullable', 'numeric'],
            'm_work' => ['nullable', 'string', 'max:50'],

            // الوكيل
            'a_ssn' => ['nullable', 'digits:9'],
            'a_name' => ['nullable', 'string', 'max:100'],
            'a_sex' => ['nullable', 'in:ذكر,أنثى'],
            'a_marital' => ['nullable', 'string', 'max:50'],
            'relation' => ['nullable', 'string', 'max:10'],

            // الاتصال
            'mobile' => ['nullable', 'digits:15'],
            'mobile2' => ['nullable', 'digits:15'],

            // باقي البيانات
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

            // العلاقات
            'department_id' => [
                'nullable',
                'exists:department,id',
            ],

            'mosque_id' => [
                'nullable',
                'exists:mosque,id',
            ],

            // الصور
            'personal_image_file' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'birth_image_file' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'father_image_file' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'mother_image_file' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'agent_image_file' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
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
            'حالة_اليتيم_الناجي_الوحيد_يتيم_الأبوين' => 'حالة اليتيم',
            'department_id' => 'القسم',
            'mosque_id' => 'المسجد',

            'personal_image_file' => 'الصورة الشخصية',
            'birth_image_file' => 'صورة شهادة الميلاد',
            'father_image_file' => 'صورة الأب',
            'mother_image_file' => 'صورة الأم',
            'agent_image_file' => 'صورة الوكيل',
        ];
    }

    /**
     * حساب العمر تلقائياً
     */
    public function updatedBarth($value): void
    {
        if ($value) {
            $this->age = Carbon::parse($value)->diffInYears(Carbon::now());
        } else {
            $this->age = 0;
        }
    }

    /**
     * الحفظ
     */
    public function save(): void
    {
        $this->validate();

        // الحقول الأساسية
        $data = $this->except([
            'orphan',

            'اعتماد_الوكيل_من',

            'personal_image_file',
            'birth_image_file',
            'father_image_file',
            'mother_image_file',
            'agent_image_file',

            'current_personal_image',
            'current_birth_image',
            'current_father_image',
            'current_mother_image',
            'current_agent_image',
        ]);

        // الحقل الذي يحتوي على مسافات في اسم قاعدة البيانات
        $data['اعتماد الوكيل من'] = $this->اعتماد_الوكيل_من;

        // تحويل التواريخ الفارغة إلى NULL حتى يقبلها MySQL
        foreach (['barth', 'f_date_d', 'm_data_d'] as $dateField) {
            if (isset($data[$dateField]) && $data[$dateField] === '') {
                $data[$dateField] = null;
            }
        }
        // تحويل القيم الرقمية الفارغة إلى NULL
        foreach ([
            'm_gas_mobile',
            'm_ssn',
            'f_ssn',
            'a_ssn',
            'count_family',
            'department_id',
            'mosque_id',
        ] as $field) {
            if (isset($data[$field]) && $data[$field] === '') {
                $data[$field] = null;
            }
        }
        // حساب العمر
        if ($this->barth) {
            $data['age'] = Carbon::parse($this->barth)->diffInYears(Carbon::now());
        }

        /*
        |--------------------------------------------------------------------------
        | حفظ الصور
        |--------------------------------------------------------------------------
        */

        $images = [
            'personal_image_file' => 'personal_image',
            'birth_image_file' => 'birth_image',
            'father_image_file' => 'father_image',
            'mother_image_file' => 'mother_image',
            'agent_image_file' => 'agent_image',
        ];
        foreach ($images as $fileProperty => $databaseColumn) {

            if ($this->{$fileProperty}) {

                $extension = $this->{$fileProperty}->getClientOriginalExtension();

                $filename = $this->SSN.'_'.$databaseColumn.'.'.$extension;

                $data[$databaseColumn] = $this->{$fileProperty}
                    ->storeAs('orphans', $filename, 'public');
            }
        }

        // تحديث
        if ($this->orphan) {
            $this->orphan->update($data);
        }

        // إضافة
        else {
            Orphan::create($data);
        }
    }
}
