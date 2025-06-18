<?php
namespace App\Notifications\User;
 
use Illuminate\Bus\Queueable;
use App\Repositories\Traits\Standard;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserUpdatedEmail extends Notification implements ShouldQueue
{
    use Queueable, Standard;

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
        return ['mail', 'database'];
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
            ->subject('Email Updated Successfully')
            ->greeting('Hello!')
            ->line('We want to let you know that your email address has been updated successfully.')
            ->line('If you did not make this change, please contact us immediately to secure your account.')
            ->action('Visit Our Page', url('/'))
            ->line('Thank you for keeping your information up-to-date. We’re here to support you anytime!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $this->notificationDatabase("Email Updated Successfully", "Your email address has been updated. If this change wasn’t made by you, please contact support immediately.");
    }
}
