<?php

namespace App\Listeners;

use App\Models\AuditLog;
use Illuminate\Auth\Events\Verified;


class LogVerifyEmailActivity
{
    /**
     * Handle the event.
     *
     * @param \Illuminate\Auth\Events\Verified $event
     * @return void
     */
    public function handle(Verified $event)
    {
        AuditLog::create([
            'activity' => 'Email Verified',
            'user_id' => $event->user->id,
            'details' => 'User ' . $event->user->id . ' verified email at ' . now(),
        ]);
    }
}
