<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as LaravelResetPasswordNotification;
use Illuminate\Notifications\Messages\MailMessage;

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
        
        // Use a custom Mailable-like approach by returning a MailMessage with view
        // Note: MailMessage doesn't support view() directly, so we'll use via() with custom channel
        return (new MailMessage)
            ->subject('Reset Password - Spanz')
            ->markdown('emails.reset-password', ['url' => $url]);
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
