<?php

namespace App\Providers;

use App\User;
use App\Product;
use App\Notifications\UserCreated;
use App\Notifications\UserMailChanged;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Notification;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /*
         * Laravel 5.4 made a change to the default database character set, and
         * it’s now utf8mb4 which includes support for storing emojis. This only
         * affects new applications and as long as you are running MySQL v5.7.7
         * and higher you do not need to do anything.
         * For those running MariaDB or older versions of MySQL you may hit this
         * error when trying to run migrations and the solution is...
         */
        Schema::defaultStringLength(191);

        User::created(function ($user) {
            // Attempt 5 times while resting 100ms in between attempts...
            retry(5, function () use ($user) {
                Notification::send($user, new UserCreated);
            }, 100);
        });

        User::updated(function ($user) {
            if ($user->isDirty('email')) {
                // Attempt 5 times while resting 100ms in between attempts...
                retry(5, function () use ($user) {
                    Notification::send($user, new UserMailChanged);
                }, 100);
            }
        });

        Product::updated(function ($product) {
            if ($product->quantity == 0 && $product->isAvailable()) {
                $product->status = Product::NOT_AVAILABLE;
                $product->save();
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
