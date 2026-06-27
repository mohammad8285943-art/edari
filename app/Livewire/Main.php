<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;


#[Layout('layout.app', ['title' => 'لوحة التحكم'])]
class Main extends Component
{
    public function render()
    {
        return view('livewire.main');
    }
}
