<?php

namespace App\Listeners;

use App\Events\PostCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\AuditLog;

class LogPostCreatedActivity implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(PostCreated $event)
    {
        AuditLog::create([
            'activity' => 'Post Created',
            'user_id' => $event->post->author_id,
            'details' => "A new post '{$event->post->title}' was created.",
        ]);
    }
}
