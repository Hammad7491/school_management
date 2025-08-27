<?php

namespace App\Providers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        /**
         * Inject the student header dropdown data:
         *  - $headerNotifications     => latest 5 published notifications
         *  - $headerUnreadCount       => count of UNREAD notifications for this user
         */
        View::composer('students.layouts.header', function ($view) {
            $user = Auth::user();

            $items  = collect();
            $unread = 0;

            if ($user) {
                // Base query= published & effective (<= now); honor is_active column if present
                $base = Notification::query()
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());

                if (Schema::hasColumn('notifications', 'is_active')) {
                    $base->where('is_active', 1);
                }

                // Unread count: notifications without a read receipt for this user
                $unread = (clone $base)
                    ->whereNotExists(function ($q) use ($user) {
                        $q->select(DB::raw(1))
                          ->from('notification_reads as nr')
                          ->whereColumn('nr.notification_id', 'notifications.id')
                          ->where('nr.user_id', $user->id);
                    })
                    ->count();

                // Latest 5 for the dropdown list
                $items = (clone $base)
                    ->latest('published_at')
                    ->take(5)
                    ->get();
            }

            $view->with('headerNotifications', $items);
            $view->with('headerUnreadCount', $unread);
        });
    }
}
