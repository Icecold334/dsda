<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\DetailPermintaanMaterial;

class PermintaanMaterialNotification extends Notification
{
    use Queueable;

    private $permintaan;

    /**
     * Create a new notification instance.
     */
    public function __construct(DetailPermintaanMaterial $permintaan)
    {
        $this->permintaan = $permintaan;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('Ada permintaan material baru yang memerlukan persetujuan.')
            ->action('Lihat Permintaan', url('/permintaan/material/' . $this->permintaan->id))
            ->line('Silakan periksa dan berikan persetujuan.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Permintaan Material Baru',
            'message' => 'Permintaan material dengan kode ' . $this->permintaan->kode_permintaan . ' memerlukan persetujuan Anda.',
            'permintaan_id' => $this->permintaan->id,
            'type' => 'material',
            'url' => '/permintaan/material/' . $this->permintaan->id
        ];
    }
}
