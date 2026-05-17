<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class PasswordResetNotification extends ResetPassword
{
    public function toMail($notifiable): MailMessage
    {
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Reset your Bronx password')
            ->view('emails.auth.password-reset', [
                'user' => $notifiable,
                'resetUrl' => $resetUrl,
                'appName' => config('app.name', 'Bronx'),
            ]);
    }
}
