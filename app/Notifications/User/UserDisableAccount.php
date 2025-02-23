<?php
namespace App\Notifications\User;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserDisableAccount extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {

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
            ->subject(__('Account Deactivation Notice'))
            ->greeting(__('Hello ') . $notifiable->name . ' ' . $notifiable->last_name . ',')
            ->line(__('We are writing to inform you that your account has been successfully deactivated.'))
            ->line(__('If this was a mistake or you wish to regain access, you can reactivate your account at any time by logging back in.'))
            ->action(__('Visit ') . settingItem('app.name'), url(env('FRONTEND_URL')))
            ->line(__('Thank you for being part of our platform. We appreciate your presence and support.'));
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
