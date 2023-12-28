<?php

namespace App\Notifications\Client;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

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
            ->subject(__("Eliminacion de cuenta"))
            ->line(__('Lamentamos su despedida ') . $notifiable->name . " " . $notifiable->last_name)
            ->line(__('Su cuenta ha sido eliminada de nuestro sistema'))
            ->line(__('Ningun dato proporcionado por usted ha sido almacenado'))
            ->line(__('toda su informacion ha sido eliminada, si desea volver con nosotros'))
            ->line(__('deberá registrarse otra vez, realizando el mismo proceso.'))
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
