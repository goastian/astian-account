<?php

namespace App\Notifications\Subscription;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RenewSuccessfully extends Notification implements ShouldQueue
{
    use Queueable;

    public $url;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        $this->url = config('app.url') . config('system.redirect_to', '/about');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Subscription Renewed Successfully')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('We’re happy to inform you that your subscription has been successfully renewed.')
            ->line('Thank you for continuing to use our service.')
            ->action('Go to dashboard', $this->url)
            ->line('If you have any questions, feel free to contact our support team.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Subscription Renewed',
            'message' => 'Your subscription has been successfully renewed.',
            'url' => $this->url,
        ];
    }
}
