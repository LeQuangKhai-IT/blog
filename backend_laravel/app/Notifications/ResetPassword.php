<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends ResetPasswordNotification
{
    /**
     * Build the mail representation of the message.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    protected function buildMailMessage($url)
    {
        $apiUrl = config('app.url') . '/api/reset-password'; // URL API POST reset password

        return (new MailMessage)
            ->subject('Reset Password Notification')
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->line('Please make a POST request to the following API endpoint to reset your password:')
            ->line('POST: ' . $apiUrl)
            ->line('Include the following token in your request:')
            ->line('Token: ' . $this->token)
            ->line('If you did not request a password reset, no further action is required.')
            ->salutation('Best regards, Khai Blog');
    }
}
