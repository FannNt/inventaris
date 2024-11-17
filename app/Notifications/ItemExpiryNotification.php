<?php

namespace App\Notifications;

use App\Livewire\Items;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ItemExpiryNotification extends Notification
{
    use Queueable;

    protected $item;
    /**
     * Create a new notification instance.
     */
    public function __construct(Items $item)
    {
        $this->item = $item;
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
            ->subject('Item Expiry Warning')
            ->line('The following item has expired or is about to expire:')
            ->line('Item Name: ' . $this->item->name)
            ->line('Expiry Date: ' . $this->item->expiry_date)
            ->line('Current Stock: ' . $this->item->stock)
            ->action('View Item', url('/admin/items/' . $this->item->id))
            ->line('Please take necessary action.');
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
