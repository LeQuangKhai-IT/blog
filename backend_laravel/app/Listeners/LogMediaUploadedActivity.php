<?php

namespace App\Listeners;

use App\Events\MediaUploaded;
use App\Models\AuditLog;
use Carbon\Carbon;

class LogMediaUploadedActivity
{
    /**
     * Handle the event.
     *
     * @param \App\Events\MediaUploaded $event
     * @return void
     */
    public function handle(MediaUploaded $event)
    {
        AuditLog::create([
            'activity' => 'Media Uploaded',
            'user_id' => $event->user->id,
            'details' => 'Media ' . $event->media->file_name . ' uploaded by user ' . $event->user->id . ' at ' . Carbon::now(),
        ]);
    }
}
