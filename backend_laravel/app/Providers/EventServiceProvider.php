<?php

namespace App\Providers;

use App\Events\MediaUploaded;
use App\Events\PostCreated;
use App\Events\PostDeleted;
use App\Events\PostLiked;
use App\Events\PostPublished;
use App\Events\PostUnPublished;
use App\Events\PostUpdated;
use App\Listeners\GenerateThumbnail;
use App\Listeners\LogLoginActivity;
use App\Listeners\LogLogoutActivity;
use App\Listeners\LogMediaUploadedActivity;
use App\Listeners\LogPostCreatedActivity;
use App\Listeners\LogPostDeletedActivity;
use App\Listeners\LogPostLikedActivity;
use App\Listeners\LogPostPublishedActivity;
use App\Listeners\LogPostUnPublishedActivity;
use App\Listeners\LogPostUpdatedActivity;
use App\Listeners\LogRegisteredActivity;
use App\Listeners\LogVerifyEmailActivity;
use App\Listeners\NotifyAdminOfRegistration;
use App\Listeners\NotifyPostCreated;
use App\Listeners\NotifyPostDeletedForFollowers;
use App\Listeners\NotifyPostLiked;
use App\Listeners\NotifyPostPublishedForFollowers;
use App\Listeners\NotifyPostUnPublishedForFollowers;
use App\Listeners\NotifyPostUpdated;
use App\Listeners\OptimizeMedia;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;


class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        // Auth
        Registered::class => [
            SendEmailVerificationNotification::class,
            NotifyAdminOfRegistration::class,
            LogRegisteredActivity::class,
        ],
        Login::class => [
            LogLoginActivity::class
        ],
        Logout::class => [
            LogLogoutActivity::class,
        ],
        Verified::class => [
            LogVerifyEmailActivity::class,
        ],
        // Posts
        PostCreated::class => [
            NotifyPostCreated::class,
            LogPostCreatedActivity::class
        ],
        PostUpdated::class => [
            NotifyPostUpdated::class,
            LogPostUpdatedActivity::class
        ],
        PostDeleted::class => [
            NotifyPostDeletedForFollowers::class,
            LogPostDeletedActivity::class
        ],
        PostPublished::class => [
            NotifyPostPublishedForFollowers::class,
            LogPostPublishedActivity::class
        ],
        PostUnPublished::class => [
            NotifyPostUnPublishedForFollowers::class,
            LogPostUnPublishedActivity::class
        ],
        PostLiked::class => [
            NotifyPostLiked::class,
            LogPostLikedActivity::class
        ],
        // Media
        MediaUploaded::class => [
            GenerateThumbnail::class,
            OptimizeMedia::class,
            LogMediaUploadedActivity::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
