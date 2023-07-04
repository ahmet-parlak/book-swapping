<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        view()->composer('*', function ($view) {
            if (Auth::check()) {
                //$notifications = NotificationModel::whereUser_id(Auth::user()->user_id)->get();
                $notifications = DB::table("notifications")
                    ->join("users", "users.user_id", "=", "notifications.sender")
                    ->where('notifications.user_id', '=', Auth::user()->user_id)->select(["notifications.*", "users.first_name", "users.last_name", "users.user_photo"])->get();

                $view->with('notifications', $notifications);
            }
        });
    }
}
