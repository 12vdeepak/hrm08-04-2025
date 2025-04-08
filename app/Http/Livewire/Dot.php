<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Dot extends Component
{
    public $count;
    public $unreadotifications=[];
    public function mount()
    {
        // Fetch initial notifications
        $this->unreadNotifications = auth()->user()->unreadNotifications()->take(10)->get();
    }

    public function countnotifications()
    {
        $today= date('Y-m-d');
        $this->unreadNotifications = auth()->user()->unreadNotifications()->whereDate('created_at', '=', $today)->take(10)->get();
        return $this->unreadNotifications;
    }
    public function render()
    {
        return view('livewire.dot');
    }
}
