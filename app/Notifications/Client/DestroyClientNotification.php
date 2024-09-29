<?php

namespace App\Notifications\Client;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class DestroyClientNotification extends Notification 
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->queue = env('REDIS_QUEUE_NOTIFICATIONS', 'notify');
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
            ->subject(__("Deleted account."))
            ->greeting(__("We're sorry to see you go"). " ". $notifiable->name . " " . $notifiable->last_name)
            ->line(__('Your account has been eliminated from our system'))
            ->line(__('All your data will be deleted')) 
            ->action(__('Go to') . " " . config('ap.name'), url(env('FRONTEND_URL')))
            ->line(__('We appreciate your instance and thank you very much for having used our services'));
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
            //
        ];
    }
}
