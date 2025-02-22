<?php

namespace App\Listeners;

use App\Events\PostDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\AuditLog;

class LogPostDeletedActivity implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(PostDeleted $event)
    {
        AuditLog::create([
            'activity' => 'Post Deleted',
            'user_id' => $event->post->author_id,
            'details' => "The post '{$event->post->title}' was deleted.",
        ]);
    }
}
