<?php

namespace App\Listeners;

use App\Events\PostLiked;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\AuditLog;

class LogPostLikedActivity implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(PostLiked $event)
    {
        AuditLog::create([
            'activity' => 'Post Liked',
            'user_id' => $event->user->id,
            'details' => "The post '{$event->post->title}' was liked by {$event->user->name}.",
        ]);
    }
}
