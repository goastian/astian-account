<?php

namespace App\Notifications\Client;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

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
            ->subject(__('Cuenta desactivada'))
            ->line(__("Lamentamos su decision ") . $notifiable->name . " " . $notifiable->last_name)
            ->line(__("Esperemos cambie de opnion"))
            ->line(__("Por medidas de seguridad su cuenta no se eliminara inmediatamente"))
            ->line(__("Su cuenta se eliminará en los proximos ") . env('DESTROY_CLIENTS_AFTER', 30) . __("dias"))
            ->line(__(""))
            ->action(__('Ir a Midori'), url(env('FRONTEND_URL')))
            ->line(__('Agracemos su instancia y muchas gracias por haber usado nuestors servicios'));

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
