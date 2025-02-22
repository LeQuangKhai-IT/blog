<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class PostLikedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $post;
    protected $user;

    public function __construct(Post $post, $user)
    {
        $this->post = $post;
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
            'post_id' => $this->post->id,
            'title' => $this->post->title,
            'user_name' => $this->user->name,
            'message' => "Your post titled '{$this->post->title}' was liked by {$this->user->name}.",
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
            'post_id' => $this->post->id,
            'title' => $this->post->title,
            'user_name' => $this->user->name,
            'message' => "Your post titled '{$this->post->title}' was liked by {$this->user->name}.",
        ]);
    }
}
