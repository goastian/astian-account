<?php

namespace App\Notifications\Subscription;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentFailed extends Notification implements ShouldQueue
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
            ->subject('Payment Failed')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Unfortunately, your recent payment attempt was not successful.')
            ->line('Please review your payment method and try again.')
            ->action('Go to dashboard', $this->url)
            ->line('If you continue to have issues, contact our support team.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Payment Failed',
            'message' => 'Your recent payment attempt was unsuccessful. Please try again.',
            'url' => $this->url,
        ];
    }
}
