<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Contact;

class NewContactFormNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $contact;

    /**
     * Create a new notification instance.
     *
     * @param Contact $contact
     */
    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Yeni İletişim Formu: ' . $this->contact->name)
            ->greeting('Merhaba!')
            ->line('Yeni bir iletişim formu gönderildi:')
            ->line('Ad: ' . $this->contact->name)
            ->line('E-posta: ' . $this->contact->email)
            ->line('Mesaj: ' . $this->contact->message)
            ->action('Yanıtla', 'mailto:' . $this->contact->email)
            ->line('Teşekkür ederiz!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'contact_id' => $this->contact->id,
            'name' => $this->contact->name,
            'email' => $this->contact->email
        ];
    }
}
