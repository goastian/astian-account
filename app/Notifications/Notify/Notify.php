<?php

namespace App\Notifications\Notify;

use ErrorException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class Notify extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var Object
     */
    public $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
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
        return $this->data->via;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        try {
            return (new MailMessage)
                ->subject($this->data->subject)
                ->greeting(Lang::get("Hi $notifiable->name"))
                ->line(Lang::get($this->data->message))
                ->action('Go to page', url($this->data->resource ?: '/'))
                ->line(Lang::get('Thank you for using our application!'));
        } catch (ErrorException $e) {
            return (new MailMessage)
                ->subject($this->data->subject)
                ->greeting(Lang::get("Hi $notifiable->name"))
                ->line(Lang::get($this->data->message))
                ->action('Go to page', url('/'))
                ->line(Lang::get('Thank you for using our application!'));
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        try {
            return [
                'title' => $this->data->subject,
                'message' => $this->data->message,
                'resource' => $this->data->resource,
            ];
        } catch (ErrorException $e) {
            return [
                'title' => $this->data->subject,
                'message' => $this->data->message,
            ];
        }
    }
}
