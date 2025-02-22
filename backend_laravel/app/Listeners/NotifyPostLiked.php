<?php

namespace App\Listeners;

use App\Events\PostLiked;
use App\Notifications\PostLikedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Notifications\Notification;

class NotifyPostLiked extends Notification implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(PostLiked $event)
    {
        $author = $event->post->author;

        $author->notify(new PostLikedNotification($event->post, $event->user));
    }
}
