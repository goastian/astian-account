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
    public $code;

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
                    ->subject(__("Two Factor verification"))
                    ->line(__("We have received a login application using the 2FA."))
                    ->line(__("Code" . " : " . $this->code))
                    ->line(__("This code has a maximum limit of life, it expires in") . " " . env('CODE_2FA_EXPIRE',1) . " ". __("minutes."))
                    ->line(__("If you were not, omit this message and for security changes your password that has been violated"))
                    ->line('Thanks for using our services!');
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
