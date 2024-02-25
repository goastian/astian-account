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
            ->subject(__("Account registered"))
            ->line(__("Your account has been registered, your password is") . " : " . $this->password)
            ->line(__("This password is randomly generated, if you want to change it, you can follow the following steps") . " : ")
            ->line("Enter the link below")
            ->action('New Password', url('/forgot-password'))
            ->line(__("An email will arrive, clicking on the link will redirect you to update your password writes and confirms your password that will then redirect you to login"));
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
