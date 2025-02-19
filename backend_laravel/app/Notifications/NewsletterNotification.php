<?php

namespace App\Notifications;

use App\Models\Newsletter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewsletterNotification extends Notification
{
    use Queueable;

    protected $newsletter;

    /**
     * Create a new notification instance.
     *
     * @param  \App\Models\Newsletter  $newsletter
     * @return void
     */
    public function __construct(Newsletter $newsletter)
    {
        $this->newsletter = $newsletter;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Newsletter: ' . $this->newsletter->title)
            ->greeting('Hello!')
            ->line('We have a new newsletter for you!')
            ->line('Title: ' . $this->newsletter->title)
            ->line($this->newsletter->content)
            ->line('Published on: ' . $this->newsletter->created_at->format('F j, Y, g:i a'))
            ->action('View Newsletter', url('/newsletters/' . $this->newsletter->id))
            ->line('Thank you for subscribing to our newsletter!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'title' => $this->newsletter->title,
            'content' => $this->newsletter->content,
            'created_at' => $this->newsletter->created_at,
        ];
    }
}
