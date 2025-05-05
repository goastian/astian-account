<?php
namespace App\Notifications\Member;

use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MemberCreatedAccount extends Notification implements ShouldQueue
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
        $link = $this->generateLink($notifiable);

        return (new MailMessage)
            ->subject(__('Welcome to Our Platform'))
            ->greeting(__("Hello " . $notifiable->name . " " . $notifiable->last_name . ","))
            ->line(__("We’re excited to have you with us! To complete your registration and verify your identity, please follow the instructions provided."))
            ->line(__("You have a maximum of ") . settingItem('verify_account_time', 5) . __(" minutes to verify your account. If the verification is not completed within this time, your information will be permanently deleted, and you’ll need to register again."))
            ->action(__('Verify Your Account'), url($link))
            ->line(__('Thank you for choosing us. We’re here to support you every step of the way.'));
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
     * Generate a new url to verify account
     * @param mixed $client
     * @return string
     */
    public function generateLink($user)
    {
        $token = Str::random(40);
        $email = $user->email;

        $query = http_build_query([
            'email' => $email,
            'token' => $token,
        ]);

        DB::transaction(function () use ($token, $email) {
            DB::table('password_resets')->updateOrInsert(
                [
                    'email' => $email,
                ],
                [
                    'email' => $email,
                    'token' => $token,
                    'created_at' => now(),
                ]
            );
        });

        return route('users.verify.account') . "?$query";
    }
}
