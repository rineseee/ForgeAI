<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\Session;

/**
 * Flashes a toast notification for the next request, honoring the user's
 * notification preferences so toggled-off event types stay silent.
 */
class Toast
{
    public static function success(User $user, string $preferenceKey, string $message): void
    {
        self::flash($user, $preferenceKey, 'success', $message);
    }

    public static function error(User $user, string $message): void
    {
        self::flash($user, 'notify_on_errors', 'error', $message);
    }

    private static function flash(User $user, string $preferenceKey, string $type, string $message): void
    {
        if (! $user->{$preferenceKey}) {
            return;
        }

        Session::flash('toast', ['type' => $type, 'message' => $message]);
    }
}
