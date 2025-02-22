<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;
use App\Notifications\RegisteredNotification;
use Illuminate\Notifications\Notification;

class NotifyAdminOfRegistration extends Notification implements ShouldQueue
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
        // Assuming the 'admin' role exists
        $adminUsers = User::role('admin')->get();

        foreach ($adminUsers as $admin) {
            $admin->notify(new RegisteredNotification($event->user));
        }
    }
}
