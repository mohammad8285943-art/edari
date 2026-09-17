<?php

namespace App\Livewire;

use App\Models\AidBeneficiary;
use App\Models\Guarantee;
use App\Models\Orphan;
use App\Models\widow;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layout.app', ['title' => 'البحث الشامل عن مستفيد'])]
class BeneficiarySearch extends Component
{
    public string $ssn = '';
    public string $searchedSsn = '';
    public bool $hasSearched = false;

    // مجموعات النتائج
    public $orphans = [];
    public $widows = [];
    public $guarantees = [];
    public $aidBeneficiaries = [];

    // الإحصائيات
    public int $orphansCount = 0;
    public int $widowsCount = 0;
    public int $guaranteesCount = 0;
    public int $aidsCount = 0;

    protected $rules = [
        'ssn' => 'required|string|min:4|max:20',
    ];

    protected $messages = [
        'ssn.required' => 'يرجى إدخال رقم الهوية للبدء بالبحث.',
        'ssn.min' => 'رقم الهوية يجب ألا يقل عن 4 خانات.',
    ];

    public function search(): void
    {
        $cleanSsn = trim($this->ssn);
        $this->ssn = $cleanSsn;

        $this->validate();

        $this->searchedSsn = $cleanSsn;
        $this->hasSearched = true;

        // 1. استعلام الأيتام (مع التحقق من سبب الظهور)
        $rawOrphans = Orphan::query()
            ->with(['department', 'mosque'])
            ->where('SSN', $cleanSsn)
            ->orWhere('f_ssn', $cleanSsn)
            ->orWhere('m_ssn', $cleanSsn)
            ->get();

        $this->orphans = $rawOrphans->map(function ($orphan) use ($cleanSsn) {
            $reasons = [];
            if ((string) $orphan->SSN === $cleanSsn) {
                $reasons[] = 'رقم هوية اليتيم نفسه';
            }
            if ((string) $orphan->f_ssn === $cleanSsn) {
                $reasons[] = 'رقم هوية والد اليتيم';
            }
            if ((string) $orphan->m_ssn === $cleanSsn) {
                $reasons[] = 'رقم هوية والدة اليتيم';
            }
            $orphan->match_reasons = $reasons;
            return $orphan;
        });

        // 2. استعلام الأرامل (SoftDeletes مفعّلة تلقائياً في النموذج)
        $this->widows = widow::query()
            ->with(['department', 'mosque'])
            ->where('ssn', $cleanSsn)
            ->get();

        // 3. استعلام الكفالات
        $this->guarantees = Guarantee::query()
            ->with(['department', 'mosque'])
            ->where('ssn', $cleanSsn)
            ->latest('id')
            ->get();

        // 4. استعلام الاستفادات والمساعدات مع تفاصيل المساعدة
        $this->aidBeneficiaries = AidBeneficiary::query()
            ->with('aid')
            ->where('ssn', $cleanSsn)
            ->latest('id')
            ->get();

        // حساب العدادات
        $this->orphansCount = $this->orphans->count();
        $this->widowsCount = $this->widows->count();
        $this->guaranteesCount = $this->guarantees->count();
        $this->aidsCount = $this->aidBeneficiaries->count();
    }

    public function resetSearch(): void
    {
        $this->reset([
            'ssn',
            'searchedSsn',
            'hasSearched',
            'orphans',
            'widows',
            'guarantees',
            'aidBeneficiaries',
            'orphansCount',
            'widowsCount',
            'guaranteesCount',
            'aidsCount',
        ]);
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.beneficiary-search');
    }
}
