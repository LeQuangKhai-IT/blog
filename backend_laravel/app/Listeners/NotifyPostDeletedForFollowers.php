<?php

namespace App\Listeners;

use App\Events\PostDeleted;
use App\Notifications\PostDeletedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Notifications\Notification;

class NotifyPostDeletedForFollowers extends Notification implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(PostDeleted $event)
    {
        $followers = $event->post->author->followers;

        foreach ($followers as $follower) {
            $follower->notify(new PostDeletedNotification($event->post));
        }
    }
}
