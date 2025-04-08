<?php

namespace App\Http\Livewire;

use App\Models\Leave as ModelsLeave;
use Livewire\Component;

class Leave extends Component
{
    public $leave_requests,$leaves;

    public function getListeners(){
        return [
            "echo:leave,Leave" => 'refresh',
        ];
    }

    public function refresh(){
        $this->mount();
    }
 
    public function mount()
    {
        $this->leave_requests=ModelsLeave::orderBy('created_at', 'desc')->limit(3)->get();
    }
    public function fetchleaves()
    {
        $this->leaves = ModelsLeave::orderBy('created_at', 'desc')->limit(3)->get();
        return $this->leaves;
    }

    public function render()
    {
        return view('livewire.leave');
    }
}
