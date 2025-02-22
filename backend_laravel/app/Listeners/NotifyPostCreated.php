<?php

namespace App\Listeners;

use App\Events\PostCreated;
use App\Models\User;
use App\Notifications\PostCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Notifications\Notification;

class NotifyPostCreated extends Notification implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(PostCreated $event)
    {
        $admins = User::role('admin')->get();  // Assuming you have a role system

        foreach ($admins as $admin) {
            $admin->notify(new PostCreatedNotification($event->post));
        }
    }
}
