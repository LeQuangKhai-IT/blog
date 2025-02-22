<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class RegisteredNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'user_id' => $this->user->id,
            'name' => $this->user->name,
            'message' => "A new user named '{$this->user->name}' has registered.",
        ];
    }

    /**
     * Prepare the data for broadcasting a notification.
     *
     * @param  \Illuminate\Notifications\Notifiable  $notifiable
     * @return \Illuminate\Broadcasting\BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'user_id' => $this->user->id,
            'name' => $this->user->name,
            'message' => "A new user named '{$this->user->name}' has registered.",
        ]);
    }
}
