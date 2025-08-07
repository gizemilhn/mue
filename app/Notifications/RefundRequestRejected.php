<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\ReturnRequest;

class RefundRequestRejected extends Notification
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
            ->subject('İade Talebiniz Red Edildi')
            ->greeting('Merhaba ' . $notifiable->name)
            ->line('Sipariş Kodu: #' . $this->returnRequest->order->id)
            ->line('İade talebinizin reddedildiğini üzülerek size bildiriyoruz.Eğer bir hata olduğunu düşünüyorsanız bize ulaşın: 0850 XXX XXXX')
            ->line('Red sebebi:' .$this->returnRequest->rejection_reason)
            ->line('Teşekkür ederiz.');

    }
}
