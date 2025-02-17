<?php
namespace App\Notifications\Member;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MemberDisableAccount extends Notification implements ShouldQueue
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
            ->greeting(__('Dear ') . $notifiable->name . ' ' . $notifiable->last_name . ',')
            ->line(__('We are sorry to hear about your decision to deactivate your account.'))
            ->line(__('For security purposes, your account will not be removed immediately. Instead, it is scheduled for permanent deletion in ') . settingItem('destroy_user_after', 30) . __(' days.'))
            ->line(__('If you change your mind, you can easily reactivate your account by logging in before the deletion period ends.'))
            ->action(__('Visit ') . config('app.name'), url(env('FRONTEND_URL')))
            ->line(__('Thank you for being a valued member of our community. We truly appreciate your trust and the time you spent with us.'));

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
