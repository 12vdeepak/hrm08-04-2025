<?php

namespace App\Http\Livewire;

use Livewire\Component;

class HrEmployeeModel extends Component
{
    public $date;

    public function render()
    {
        return view('livewire.hr-employee-model', compact('date'));
    }

    public function edit_log_time($date){
    }

    public function view_time_tracker($date){
    }

    public function update_log_time(){

    }
}
