<?php
namespace App\Notifications\Setting;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CodeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * code
     * @var 
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
            ->line(__("This code has a maximum limit of life, it expires in") . " " . config('system.code_2fa_email_expires', 5) . " " . __("minutes."))
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
