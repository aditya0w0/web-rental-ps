<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;

class OrderStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Order Status Updated: ' . $this->order->order_number)
                    ->line('The status of your order has been updated.')
                    ->line('New Status: ' . ucfirst($this->order->status))
                    ->action('View Order', url('/orders/' . $this->order->id))
                    ->line('Thank you for your purchase!');
    }

    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'status' => $this->order->status,
            'message' => 'The status of your order ' . $this->order->order_number . ' has been updated to ' . $this->order->status . '.'
        ];
    }
}