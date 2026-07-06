<?php

namespace App\Livewire;

use App\Models\Orphan;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layout.app', ['title' => 'الملف الشخصي لليتيم'])]
class OrphanShow extends Component
{
    public Orphan $orphan;

    public function mount(int $id): void
    {
        // جلب بيانات اليتيم مع العلاقات المحملة مسبقاً لتحسين الأداء
        $this->orphan = Orphan::with(['department', 'mosque'])->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.orphan-show');
    }
}