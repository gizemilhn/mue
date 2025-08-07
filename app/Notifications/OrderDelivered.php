<?php
namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OrderDelivered extends Notification
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
            ->subject('Siparişiniz Teslim Edildi')
            ->greeting("Merhaba {$notifiable->name},")
            ->line("Siparişiniz teslim edildi. Sipariş numaranız: #{$this->order->id}")
            ->line('Bizi tercih ettiğiniz için teşekkür eder, iyi günlerde kullanmanızı dileriz!');
    }
}
