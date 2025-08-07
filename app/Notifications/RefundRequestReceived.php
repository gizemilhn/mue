<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\ReturnRequest;

class RefundRequestReceived extends Notification
{
    use Queueable;

    protected $returnRequest;

    public function __construct(ReturnRequest $returnRequest)
    {
        $this->returnRequest = $returnRequest;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('İade Talebiniz Alındı')
            ->greeting('Merhaba ' . $notifiable->name)
            ->line('İade talebiniz başarıyla alınmıştır.')
            ->line('Sipariş Kodu: #' . $this->returnRequest->order->id)
            ->line('En kısa sürede tarafınıza dönüş yapılacaktır.')
            ->line('Teşekkür ederiz.');
    }
}
