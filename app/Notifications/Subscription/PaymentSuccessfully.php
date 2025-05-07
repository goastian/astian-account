<?php

namespace App\Notifications\Subscription;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentSuccessfully extends Notification implements ShouldQueue
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
            ->subject('Payment Confirmation')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('We have successfully received your payment.')
            ->line('Your subscription has been activated and is now in effect.')
            ->action('Go to dashboard', $this->url)
            ->line('Thank you for your trust!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Payment Successfully Received',
            'message' => 'Your payment was received and your subscription is now active.',
            'url' => $this->url,
        ];
    }
}
