<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as LaravelResetPasswordNotification;
use App\Mail\ResetPasswordMail;

class CustomResetPasswordNotification extends LaravelResetPasswordNotification
{
    /**
     * Build the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        $url = $this->resetUrl($notifiable);
        
        // Return custom Mailable for consistent email design
        return new ResetPasswordMail($url);
    }

    /**
     * Get the reset URL for the given notifiable.
     */
    protected function resetUrl($notifiable)
    {
        if (static::$createUrlCallback) {
            return call_user_func(static::$createUrlCallback, $notifiable, $this->token);
        }

        return url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));
    }
}
