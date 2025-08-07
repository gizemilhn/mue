<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\ReturnRequest;

class RefundRequestCompleted extends Notification
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
            ->subject('İade Talebiniz Kabul Edildi')
            ->greeting('Merhaba ' . $notifiable->name)
            ->line('İade talebiniz kabul edilmiştir.İade için gerekli bilgiler:')
            ->line('Sipariş Kodu: #' . $this->returnRequest->order->id)
            ->line('Kargo Firması: ' . $this->returnRequest->cargo_company)
            ->line('İade Kodu: ' . $this->returnRequest->return_code)
            ->line('Bu bilgilere hesabınızda sipariş durumunuzun altında bulunan aide talep kısmından da ulaşabilirsiniz.İade kargonuzu belirtilen kargo firmasının herhangi bir şubesinden size verilen kodla gönderebilirsiniz.')
            ->line('Teşekkür ederiz.');

    }
}
