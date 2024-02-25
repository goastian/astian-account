<?php

namespace App\Notifications\Client;

use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ClientRegistered extends Notification
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
            ->subject(__('Welcome to Astian'))
            ->greeting(__("Welcome " . $notifiable->name . " " . $notifiable->last_name))
            ->line(__("To be able to verify that you are a person, follow the instructions, to allow you to activate your account."))
            ->line(__("To verify your account you only have a maximum time of") . " " . env('TIME_TO_VERIFY_ACCOUNT') . __("Minutes, if not the active will proceed to erase all its information and will have to register again."))
            ->action(__('Go to ') . config('ap.name'), url($link))
            ->line(__('Thank you for being a part of us.'));
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
