<?php

namespace App\Listeners;

use App\Events\PostUnPublished;
use App\Notifications\PostUnPublishedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NotifyPostUnPublishedForFollowers extends Notification implements ShouldQueue
{
    /**
     * Handle the event and notify followers via database and broadcast channels.
     *
     * @param \App\Events\PostUnPublished $event The event that holds the unpublished post.
     * @return void
     */
    public function handle(PostUnPublished $event)
    {
        $followers = $event->post->author->followers;
        foreach ($followers as $follower) {
            $follower->notify(new PostUnPublishedNotification($event->post));
        }
    }
}
