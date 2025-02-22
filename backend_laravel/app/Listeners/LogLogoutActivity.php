<?php

namespace App\Listeners;

use App\Models\AuditLog;
use Illuminate\Auth\Events\Logout;

class LogLogoutActivity
{
    /**
     * Handle the event.
     *
     * @param \App\Events\Logout $event
     * @return void
     */
    public function handle(Logout $event)
    {
        AuditLog::create([
            'activity' => 'User Logout',
            'user_id' => $event->user->id,
            'details' => 'User ' . $event->user->id . ' logged out at ' . now(),
        ]);
    }
}
