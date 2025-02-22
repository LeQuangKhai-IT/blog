<?php

namespace App\Listeners;

use App\Events\PostUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\AuditLog;

class LogPostUpdatedActivity implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(PostUpdated $event)
    {
        AuditLog::create([
            'activity' => 'Post Updated',
            'user_id' => $event->post->author_id,
            'details' => "The post '{$event->post->title}' was updated.",
        ]);
    }
}
