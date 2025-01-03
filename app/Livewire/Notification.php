<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Notification extends Component
{

    public function markAsRead($notificationId, $href)
    {
        $notification = Auth::user()->notifications->find($notificationId);
        if ($notification) {
            $notification->markAsRead();
        }
        redirect()->to($href);
    }
    public function render()
    {
        return view('livewire.notification');
    }
}
