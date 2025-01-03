<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserNotification extends Notification
{
    use Queueable;
    protected $message;
    protected $url;
    /**
     * Create a new notification instance.
     */
    public function __construct($message, $url)
    {
        $this->message = $message; // Pesan notifikasi
        $this->url = $url; // Pesan notifikasi
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // Pilih jenis notifikasi: email, database, dll.
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Approval Notification')
            ->line('The introduction to the notification.')
            ->action('Notification Action', url($this->url))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => $this->message,
            'url' => $this->url, // Tautan in-app
        ];
    }
}
