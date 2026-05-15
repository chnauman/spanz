<?php

namespace App\Providers;

use App\Models\AdminUserMessage;
use App\Models\UserTenderNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $shareUnreadCounts = function ($view) {
            $unreadRfxCount = 0;
            $unreadMessagesCount = 0;

            if (Auth::check() && !Auth::user()->isAdmin()) {
                $userId = Auth::id();
                $unreadRfxCount = UserTenderNotification::query()
                    ->where('user_id', $userId)
                    ->whereNull('read_at')
                    ->count();
                $unreadMessagesCount = AdminUserMessage::query()
                    ->where('user_id', $userId)
                    ->whereNull('read_at')
                    ->count();
            }

            $view->with([
                'unreadRfxCount' => $unreadRfxCount,
                'unreadMessagesCount' => $unreadMessagesCount,
            ]);
        };

        View::composer(['layouts.admin', 'partials.header-notification-icons', 'partials.header-auth-actions'], $shareUnreadCounts);
    }
}
