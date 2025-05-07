<?php

namespace App\Notifications\Subscription;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequestSubscription extends Notification implements ShouldQueue
{
    use Queueable;

    public $code;

    public $url;

    /**
     * Create a new notification instance.
     */
    public function __construct($code)
    {
        $this->code = $code;
        $this->url = config('app.url') . config('system.redirect_to', '/about');

    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Subscription Request is Being Processed')
            ->greeting('Dear User,')
            ->line('We have received your subscription request and it is currently being processed.')
            ->line("Transaction Code: {$this->code}")
            ->line('You will receive a confirmation email once the process is completed.')
            ->line('Thank you for your interest and for choosing our services.')
            ->action('Go to dashboard', $this->url)
            ->salutation('Best regards, The Team');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Subscription Request Received',
            'message' => "Your subscription request with transaction code {$this->code} is being processed. You will be notified once it is confirmed.",
            'transaction_code' => $this->code,
            'url' => $this->url,
        ];
    }
}
