<?php

namespace App\Livewire\Student;

use App\Models\Notification;
use Livewire\Component;

class NotificationsFeed extends Component
{
    public function render()
    {
        $notifications = Notification::published()->latest()->take(12)->get();

        return view('livewire.student.notifications-feed', compact('notifications'));
    }
}
