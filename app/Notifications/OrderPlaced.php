<?php
namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OrderPlaced extends Notification
{
    use Queueable;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Siparişiniz Alındı')
            ->greeting("Merhaba {$notifiable->name},")
            ->line("Siparişiniz başarıyla alındı. Sipariş numaranız: #{$this->order->id}")
            ->action('Siparişi Görüntüle', route('user.order.details', $this->order->id))
            ->line('Bizi tercih ettiğiniz için teşekkür ederiz!');
    }
}
