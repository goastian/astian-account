<?php

namespace App\Notifications\Employee;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CreatedNewUser extends Notification implements ShouldQueue
{
    use Queueable;
    
    /**
     * @var String
     */
    public $password;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($password = null)
    {
        $this->password = $password;
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
            ->subject("Cuenta registrada")
            ->line("Su cuenta ha sido registrada, su contraseña es : " . $this->password)
            ->line("Esta contraseña se genero aleatoriamente, si desea cambiarla")
            ->line("Puede seguir los siguientes pasos:")
            ->line("Ingrese al enlace que se encuentra debajo:")
            ->line("Escriba su email y de click en aceptar:")
            ->line("Llegará un email, al dar click en el enlace te redireccionará para que actualices tu contraseña:")
            ->line("Escribe y confirma tu contraseña que luego te redireccionará al Login:")
            ->action('Crear Contraseña', url('/forgot-password'));
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
