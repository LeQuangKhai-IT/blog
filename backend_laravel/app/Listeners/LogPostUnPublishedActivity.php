<?php

namespace App\Listeners;

use App\Events\PostUnPublished;
use App\Models\AuditLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogPostUnPublishedActivity implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event and log the activity of post unpublishing.
     *
     * This method logs the unpublishing of the post into the audit log.
     *
     * @param \App\Events\PostUnPublished $event
     * @return void
     */
    public function handle(PostUnPublished $event)
    {
        AuditLog::create([
            'activity' => 'Post Unpublished',
            'user_id' => $event->post->author_id,
            'details' => "The post '{$event->post->title}' was unpublished.",
        ]);
    }
}
