<?php
namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OrderShipped extends Notification
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
            ->subject('Siparişiniz Kargoya Verildi')
            ->greeting("Merhaba {$notifiable->name},")
            ->line("Siparişiniz kargoya verildi. Sipariş numaranız: #{$this->order->id}")
            ->action('Siparişi Takip Et', route('user.order.details', $this->order->id))
            ->line('En kısa sürede teslim edilecektir. Teşekkürler!');
    }
}
