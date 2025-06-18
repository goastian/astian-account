<?php
namespace App\Notifications\User;
 
use Illuminate\Bus\Queueable;
use App\Repositories\Traits\Standard;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserUpdatedPassword extends Notification implements ShouldQueue
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
            ->subject('Password Updated Successfully')
            ->greeting('Hello!')
            ->line('We want to inform you that your password has been updated successfully.')
            ->line('If you did not make this change, please contact us immediately to secure your account.')
            ->action('Go to the App', url('/'))
            ->line('Thank you for trusting us to keep your account secure. We are always here to help!');

    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $this->notificationDatabase("Password Updated Successfully", "Your password has been successfully updated. If this wasn’t you, please contact support immediately.");
    }
}
