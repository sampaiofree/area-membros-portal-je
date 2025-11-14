<?php

namespace App\Livewire\Student;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationsFeed extends Component
{
    use WithPagination;

    public function render()
    {
        $user = Auth::user();

        $notifications = Notification::published()
            ->with(['views' => fn ($query) => $query->where('user_id', $user->id ?? null)])
            ->latest()
            ->paginate(12);

        return view('livewire.student.notifications-feed', compact('notifications', 'user'));
    }
}
