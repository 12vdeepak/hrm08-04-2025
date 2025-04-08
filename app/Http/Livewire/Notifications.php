<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Notifications extends Component
{
    public $unreadNotifications = [];
    public $Notifications = [];
    public function getListeners()
    {   
        $auth_id = auth()->id();
        return [
            "echo:leave,Leave" => 'refresh',
            // "echo:holiday,Holiday" => 'refresh',
            "echo-private:announcement.{$auth_id},Announcement" => 'refresh',
            //"echo:companypolicy,.company-policy" => 'refresh',
        ];
    }

    public function refresh()
    {
        $this->mount();
    }
    public function mount()
    {
        // Fetch initial notifications
        $this->unreadNotifications = auth()->user()->unreadNotifications()->take(10)->get();
    }

    public function fetchnotifications()
    {
        $today= date('Y-m-d');
        $this->Notifications = auth()->user()->notifications()->whereDate('created_at', '=', $today)->take(15)->get();
        //$this->Notifications = auth()->user()->notifications()->take(15)->get();
        return $this->Notifications;
    }

    public function render()
    {
        return view('livewire.notifications');
    }

}
