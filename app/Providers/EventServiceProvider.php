<?php

namespace App\Providers;

use App\Listeners\Admin\Backup\MailSuccessfulDatabaseBackup;
use App\Models\Address;
use App\Models\EmailWeb;
use App\Models\File;
use App\Models\Image;
use App\Models\Newsletter;
use App\Models\Order;
use App\Models\User;
use App\Observers\AddressObserver;
use App\Observers\EmailWebObserver;
use App\Observers\FileObserver;
use App\Observers\ImageObserver;
use App\Observers\NewsletterObserver;
use App\Observers\OrderObserver;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use SocialiteProviders\Google\Provider as GoogleProvider;
use SocialiteProviders\Manager\SocialiteWasCalled;
use Spatie\Backup\Events\BackupZipWasCreated;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // MODELS - LOCAL
        User::observe(UserObserver::class);
        Image::observe(ImageObserver::class);
        File::observe(FileObserver::class);
        Newsletter::observe(NewsletterObserver::class);
        EmailWeb::observe(EmailWebObserver::class);
        Address::observe(AddressObserver::class);
        Order::observe(OrderObserver::class);
        // CUSTOM - LOCAL
        Event::listen(BackupZipWasCreated::class, MailSuccessfulDatabaseBackup::class);
        // SOCIALITE - PROVIDER
        Event::listen(function (SocialiteWasCalled $event) {
            $event->extendSocialite('google', GoogleProvider::class);
        });
    }
}
