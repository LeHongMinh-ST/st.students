<?php

declare(strict_types=1);

namespace App\Livewire\Post;

use App\Models\PostNotification;
use App\Services\SsoService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Notifications extends Component
{
    public array $notifications = [];
    public int $unreadCount = 0;

    public function mount(): void
    {
        $this->loadNotifications();
    }

    public function render()
    {
        return view('livewire.post.notifications');
    }

    public function loadNotifications(): void
    {
        $facultyId = app(SsoService::class)->getFacultyId();

        $this->notifications = PostNotification::where('user_id', Auth::id())
            ->where('faculty_id', $facultyId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->toArray();

        $this->unreadCount = PostNotification::where('user_id', Auth::id())
            ->where('faculty_id', $facultyId)
            ->where('read', false)
            ->count();
    }

    public function markAsRead(int $notificationId): void
    {
        $notification = PostNotification::find($notificationId);

        if ($notification && $notification->user_id === Auth::id()) {
            $notification->update(['read' => true]);
            $this->loadNotifications();
        }
    }

    public function markAllAsRead(): void
    {
        $facultyId = app(SsoService::class)->getFacultyId();

        PostNotification::where('user_id', Auth::id())
            ->where('faculty_id', $facultyId)
            ->where('read', false)
            ->update(['read' => true]);

        $this->loadNotifications();
    }
}
