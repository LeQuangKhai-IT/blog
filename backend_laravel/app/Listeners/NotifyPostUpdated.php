<?php

namespace App\Listeners;

use App\Events\PostUpdated;
use App\Models\User;
use App\Notifications\PostUpdatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Notifications\Notification;

class NotifyPostUpdated extends Notification implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(PostUpdated $event)
    {
        $admins = User::role('admin')->get();

        foreach ($admins as $admin) {
            $admin->notify(new PostUpdatedNotification($event->post));
        }
    }
}
