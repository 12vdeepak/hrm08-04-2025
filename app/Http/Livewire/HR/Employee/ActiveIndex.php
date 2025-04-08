<?php

namespace App\Http\Livewire\HR\Employee;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class ActiveIndex extends Component
{
    use WithPagination;

    public $search = '';
    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $query = User::query();
        $query = $query->whereNotIn('role_id', [1])->where('employee_status',1)->orderBy('name', 'asc');
        if($this->search){
            $query->where('name', 'like', "%{$this->search}%");
            $query->orWhere('lastname', 'like', "%{$this->search}%");
        }

        return view('livewire.h-r.employee.active-index', [
            'employees' => $query->paginate(10)
        ]);
    }

    public function updated($property){
        if($property == 'search'){
            $this->resetPage();
        }
    }
}
