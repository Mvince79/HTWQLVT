<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewIncidentReported extends Notification
{
    use Queueable;
    public $incident;

    /**
     * Create a new notification instance.
     */
    public function __construct($incident)
    {
        $this->incident = $incident;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->error()
            ->subject('CẢNH BÁO: Sự cố vận hành mới')
            ->line('Hệ thống vừa ghi nhận một sự cố mới từ tài xế.')
            ->line('Loại sự cố: ' . $this->incident->type)
            ->line('Ghi chú: ' . $this->incident->note)
            ->action('Xem chi tiết sự cố', url('/admin/incidents/' . $this->incident->id))
            ->line('Vui lòng xử lý kịp thời!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
