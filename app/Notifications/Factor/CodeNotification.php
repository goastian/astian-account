<?php

namespace App\Notifications\Factor;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CodeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var String
     */
    private $code;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($code)
    {
        $this->code = $code;
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
                    ->subject(Lang::get("TOKEN DE VERIFICACION 2FA"))
                    ->line(Lang::get("Hemos recibido una solicitud de inicio de session usando el 2FA"))
                    ->line(Lang::get("TOKEN : " . $this->code))
                    ->line(Lang::get("Este codigo tiene un limite maximo de vida, expira en " . env('CODE_2FA_EXPIRE',1) . " minuto."))
                    ->line(Lang::get("Si no fuiste tu, omite este mensaje y por seguridad cambia tu contraseña que ha sido vulnerada"))
                    ->line('Gracias por usar nuestros servicios!');
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
