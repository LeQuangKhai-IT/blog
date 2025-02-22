<?php

namespace App\Listeners;

use App\Models\AuditLog;
use Illuminate\Auth\Events\Login;

class LogLoginActivity
{
    /**
     * Handle the event.
     *
     * @param \Illuminate\Auth\Events\Login $event
     * @return void
     */
    public function handle(Login $event)
    {
        AuditLog::create([
            'activity' => 'User Login',
            'user_id' => $event->user->id,
            'details' => 'User ' . $event->user->id . ' logged in at ' . now(),
        ]);
    }
}
