<?php

namespace App\Notifications\Info;

use ErrorException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class Alert extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var Object
     */
    private $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->queue = 'notify';
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return $this->data->method;
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
                ->subject($this->data->title)
                ->greeting(Lang::get("Hi $notifiable->name"))
                ->line(Lang::get($this->data->message))
                ->action('Go to page', url($this->data->resource ?: '/'))
                ->line(Lang::get('Thank you for using our application!'));
        } catch (ErrorException $e) {
            return (new MailMessage)
                ->subject($this->data->title)
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
    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->data->title,
            'message' => $this->data->message,
            'resource' => $this->data->resource,
        ];
    }
}
