<?php

namespace App\Livewire;

use App\Exports\OrphansExport;
use App\Models\Department;
use App\Models\Mosque;
use App\Models\Orphan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

#[Layout('layout.app', ['title' => 'بيانات الأيتام'])]
class OrphanIndex extends Component
{
    use WithPagination;

    // حقول الفلترة المنفصلة (الجديدة والمحدثة)
    public $searchSsn = '';

    public $searchName = '';

    public $searchFSsn = '';

    public $searchFName = '';

    public $searchMSsn = '';

    public $searchMName = '';

    public $searchAName = '';

    public $ageMin = '';

    public $ageMax = '';

    public $filterSex = '';

    public $filterOrphanStatus = '';

    public $filterDepartment = '';

    public $filterMosque = '';

    public $filterHealth = '';

    public $filterSponsorship = '';

    // متغيرات حفظ الحالات الثابتة عند الضغط على زر التصفية
    public $appliedSsn = '';

    public $appliedName = '';

    public $appliedFSsn = '';

    public $appliedFName = '';

    public $appliedMSsn = '';

    public $appliedMName = '';

    public $appliedAName = '';

    public $appliedAgeMin = '';

    public $appliedAgeMax = '';

    public $appliedSex = '';

    public $appliedHealth = '';

    public $appliedOrphanStatus = '';

    public $appliedDepartment = '';

    public $appliedMosque = '';

    public $appliedSponsorship = '';

    public function applyFilter(): void
    {
        $user = Auth::user();

        $this->appliedSsn = $this->searchSsn;
        $this->appliedName = $this->searchName;
        $this->appliedFSsn = $this->searchFSsn;
        $this->appliedFName = $this->searchFName;
        $this->appliedMSsn = $this->searchMSsn;
        $this->appliedMName = $this->searchMName;
        $this->appliedAName = $this->searchAName;
        $this->appliedAgeMin = $this->ageMin;
        $this->appliedAgeMax = $this->ageMax;
        $this->appliedSex = $this->filterSex;
        $this->appliedHealth = $this->filterHealth;
        $this->appliedOrphanStatus = $this->filterOrphanStatus;
        $this->appliedSponsorship = $this->filterSponsorship;

        $this->appliedDepartment = $user->view == 1 ? $this->filterDepartment : $user->department_id;
        $this->appliedMosque = $user->view == 3 ? $user->mosque_id : $this->filterMosque;

        $this->resetPage();
    }

    public function resetFilter(): void
    {
        $this->searchSsn = '';
        $this->searchName = '';
        $this->searchFSsn = '';
        $this->searchFName = '';
        $this->searchMSsn = '';
        $this->searchMName = '';
        $this->searchAName = '';
        $this->ageMin = '';
        $this->ageMax = '';
        $this->filterSex = '';
        $this->filterHealth = '';
        $this->filterDepartment = Auth::user()->view == 1 ? '' : Auth::user()->department_id;
        $this->filterMosque = Auth::user()->view == 3 ? Auth::user()->mosque_id : '';
        $this->filterOrphanStatus = '';
        $this->filterSponsorship = '';
        $this->applyFilter();
    }

    protected function buildOrphansQuery()
    {
        $query = Orphan::query()->with(['department', 'mosque']);

        if (! empty($this->appliedSsn)) {
            $query->where('SSN', 'like', '%'.$this->appliedSsn.'%');
        }
        if (! empty($this->appliedName)) {
            $query->where('name', 'like', '%'.$this->appliedName.'%');
        }
        if (! empty($this->appliedFSsn)) {
            $query->where('f_ssn', 'like', '%'.$this->appliedFSsn.'%');
        }
        if (! empty($this->appliedFName)) {
            $query->where('f_name', 'like', '%'.$this->appliedFName.'%');
        }
        if (! empty($this->appliedMSsn)) {
            $query->where('m_ssn', 'like', '%'.$this->appliedMSsn.'%');
        }
        if (! empty($this->appliedMName)) {
            $query->where('m_name', 'like', '%'.$this->appliedMName.'%');
        }
        if (! empty($this->appliedAName)) {
            $query->where('a_name', 'like', '%'.$this->appliedAName.'%');
        }
        if (! empty($this->appliedSex)) {
            $query->where('sex', $this->appliedSex);
        }
        // فلتر حالة اليتيم
        if (! empty($this->appliedOrphanStatus)) {
            $query->where(
                'حالة_اليتيم_الناجي_الوحيد_يتيم_الأبوين',
                $this->appliedOrphanStatus
            );
        }

        if (! empty($this->appliedAgeMin)) {
            $query->whereDate('barth', '<=', Carbon::today()->subYears($this->appliedAgeMin));
        }

        if (! empty($this->appliedAgeMax)) {
            // العمر أصغر من أو يساوي العمر الأعلى
            $query->whereDate('barth', '>=', Carbon::today()->subYears($this->appliedAgeMax + 1)->addDay());
        }

        // الفحص الصحي (جيدة أو يعاني من مرض)
        if (! empty($this->appliedHealth)) {
            if ($this->appliedHealth === 'good') {
                $query->where('health', 'جيدة');
            } elseif ($this->appliedHealth === 'not_good') {
                $query->where('health', '!=', 'جيدة');
            }
        }

        // فلتر حالة الكفالة (مكفول / غير مكفول)
        if (! empty($this->appliedSponsorship)) {
            if ($this->appliedSponsorship === 'unsupported') {
                $query->whereNotExists(function ($q) {
                    $q->select(DB::raw(1))
                        ->from('guarantees')
                        ->whereColumn('guarantees.ssn', 'orphan.SSN');
                });
            } elseif ($this->appliedSponsorship === 'supported') {
                $query->whereExists(function ($q) {
                    $q->select(DB::raw(1))
                        ->from('guarantees')
                        ->whereColumn('guarantees.ssn', 'orphan.SSN');
                });
            }
        }

        if (! empty($this->appliedMosque)) {
            $query->where('mosque_id', $this->appliedMosque);
        } elseif (! empty($this->appliedDepartment)) {
            $query->where('department_id', $this->appliedDepartment);
        }

        return $query;
    }

    public function exportToExcel()
    {
        abort_unless(Auth::user()->can('orphan.export'), 403);
        $filteredQuery = $this->buildOrphansQuery();

        return Excel::download(new OrphansExport($filteredQuery), 'قائمة_الأيتام_المفلترة_'.now()->format('Y-m-d').'.xlsx');
    }

    protected function getDepartments()
    {
        $user = Auth::user();

        if ($user->view == 1) {
            return Department::all();
        }

        return collect();
    }

    // soft delete orphan
    public function deleteOrphan($orphanId)
    {
        abort_unless(Auth::user()->can('orphan.delete'), 403);
        $orphan = Orphan::find($orphanId);
        if ($orphan) {
            $orphan->Delete();
        }
    }

    protected function getMosques()
    {
        $user = Auth::user();

        if ($user->view == 3) {
            // يرى المسجد الخاص به فقط
            return collect();
        } elseif ($user->view == 2) {
            // يرى جميع المساجد التابعة لقسمه
            return Mosque::where('department_id', $user->department_id)->get();
        }

        // غير ذلك يرى كل المساجد
        return Mosque::all();
    }

    public function render()
    {
        $baseQuery = $this->buildOrphansQuery();

        // تم تغيير orphans.SSN إلى orphan.SSN
        $sponsoredCount = (clone $baseQuery)
            ->whereExists(function ($q) {
                $q->select(DB::raw(1))
                    ->from('guarantees')
                    ->whereColumn('guarantees.ssn', 'orphan.SSN');
            })
            ->count();

        $orphansPaginated = (clone $baseQuery)->latest('orphan.id')->paginate(15);

        return view('livewire.orphan-index', [
            'orphans'        => $orphansPaginated,
            'totalCount'     => $orphansPaginated->total(),
            'sponsoredCount' => $sponsoredCount,
            'departments'    => $this->getDepartments(),
            'mosques'        => $this->getMosques(),
        ]);
    }
}
