<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;

class VerifyEmail extends BaseVerifyEmail
{
    use Queueable;

    private $userId;
    private $hash;

    /**
     * Constructor
     *
     * @param string $id
     * @param string $hash
     */

    public function __construct($id, $hash)
    {
        $this->id = $id;
        $this->hash = $hash;
    }

    /**
     * Override the buildMailMessage method to include id and hash in the email content.
     *
     * @param string $url
     * @return \Illuminate\Notifications\Messages\MailMessage
     */

    protected function buildMailMessage($url)
    {
        return (new MailMessage)
            ->subject('User Verification Information')
            ->greeting('Hello!')
            ->line('We have received a request to verify your account.')
            ->line('Your User Id: ' . $this->id)
            ->line('Please use the this code to verify your account:')
            ->line('Verification Code: ' . $this->id . "/" . $this->hash)
            ->line('If you did not request this, please ignore this email.')
            ->salutation('Best regards, Khai Blog');
    }

    /**
     * Generate the verification URL with id and hash.
     *
     * @param mixed $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        // Custom URL with the ID and hash in the query string
        return url(route('verification.verify', [
            'id' => $this->id,
            'hash' => $this->hash,
        ], false));
    }
}
