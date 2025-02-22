<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\AuditLog;

class LogRegisteredActivity implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        AuditLog::create([
            'activity' => 'User Registered',
            'user_id' => $event->user->id,
            'details' => "The user '{$event->user->name}' has registered.",
        ]);
    }
}
