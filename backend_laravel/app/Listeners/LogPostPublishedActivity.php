<?php

namespace App\Listeners;

use App\Events\PostPublished;
use App\Models\AuditLog;

class LogPostPublishedActivity
{
    /**
     * Handle the event.
     *
     * @param \App\Events\PostPublished $event
     * @return void
     */
    public function handle(PostPublished $event)
    {
        AuditLog::create([
            'activity' => 'Post Published',
            'user_id' => $event->post->author->id,
            'details' => "Post titled '{$event->post->title}' was published.",
        ]);
    }
}
