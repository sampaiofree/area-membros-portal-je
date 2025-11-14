<?php

namespace App\Livewire\Student;

use App\Models\Notification;
use App\Models\NotificationView;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationModal extends Component
{
    public ?Notification $notification = null;
    public bool $open = false;

    public function mount(): void
    {
        $user = Auth::user();

        if (! $user || ! $user->isStudent()) {
            return;
        }

        $this->notification = Notification::published()
            ->whereDoesntHave('views', fn ($query) => $query->where('user_id', $user->id))
            ->latest('published_at')
            ->first();

        $this->open = (bool) $this->notification;
    }

    public function dismiss(): void
    {
        if (! $this->notification) {
            return;
        }

        $user = Auth::user();

        if (! $user) {
            return;
        }

        NotificationView::updateOrCreate(
            [
                'notification_id' => $this->notification->id,
                'user_id' => $user->id,
            ],
            ['dismissed_at' => now()]
        );

        $this->open = false;
        $this->notification = null;
    }

    public function render()
    {
        return view('livewire.student.notification-modal');
    }
}
