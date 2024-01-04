<?php

namespace App\Notifications\Client;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClientRegistered extends Notification implements ShouldQueue
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
        $link = $this->link_generate($notifiable);

        return (new MailMessage)
            ->subject(__('Usuario registrado'))
            ->line(__("Bienvenid@ " . $notifiable->name . " " . $notifiable->last_name))
            ->line(__("Para poder verificar que eres una persona, sigue las instrucciones, que permitiran activar tu cuenta."))
            ->line(__("Para verificar su cuenta solo tiene un tiempo maximo de " . env('TIME_TO_VERIFY_ACCOUNT') . " minutos, si no la activa se procederá a borrar toda su informacion y tendra que registrarse otra vez."))
            ->action(__('Activar cuenta'), url($link))
            ->line('Gracias por ser parte de nosotros.');
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

    /**
     *
     */
    public function link_generate($client)
    {
        $token = Str::random(40);
        $email = $client->email;
        $query = http_build_query([
            'email' => $email,
            'token' => $token,
        ]);

        DB::transaction(function () use ($token, $email) {
            DB::table('password_resets')->insert([
                'email' => $email,
                'token' => $token,
                'created_at' => now(),
            ]);

        });

        return route('verify.account') . "?$query";
    }

}
