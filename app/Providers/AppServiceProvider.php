<?php

namespace App\Providers;

use App\Interfaces\SubscriberInterface;
use App\Interfaces\PublisherInterface;
use App\Interfaces\VideoConverter;
use App\Models\Profile;
use App\Models\User;
use App\Services\FFMpegVideoConverter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PublisherInterface::class, Profile::class);
        $this->app->bind(SubscriberInterface::class, User::class);
        $this->app->bind(VideoConverter::class, FFMpegVideoConverter::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
