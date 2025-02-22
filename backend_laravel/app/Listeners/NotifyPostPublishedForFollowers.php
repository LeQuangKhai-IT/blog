<?php

namespace App\Listeners;

use App\Events\PostPublished;
use App\Notifications\PostPublishedNotification;

class NotifyPostPublishedForFollowers
{
    /**
     * Handle the event.
     *
     * @param \App\Events\PostPublished $event
     * @return void
     */
    public function handle(PostPublished $event)
    {
        $followers = $event->post->author->followers;

        foreach ($followers as $follower) {
            $follower->notify(new PostPublishedNotification($event->post));
        }
    }
}
