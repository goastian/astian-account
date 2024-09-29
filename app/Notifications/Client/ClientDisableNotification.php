<?php

namespace App\Notifications\Client;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ClientDisableNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->queue = 'notify';

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
            ->subject(__('Account deactivated'))
            ->greeting(__("We regret your decision ") . $notifiable->name . " " . $notifiable->last_name)
            ->line(__("Hopefully change your mind"))
            ->line(__("By security measures your account is not removed immediately, your account will be deleted in the next") ." ". env('DESTROY_CLIENTS_AFTER', 30) . " " . __("days"))
            ->line(__("To reactivate your account, if you change opinion, start session before the time established for total elimination") )
            ->action(__('Go to ') . config('ap.name'), url(env('FRONTEND_URL')))
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
