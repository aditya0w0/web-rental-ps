<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\OrderIssue;

class NewOrderIssueSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    public OrderIssue $issue;

    public function __construct(OrderIssue $issue)
    {
        $this->issue = $issue;
    }

    public function via($notifiable)
    {
        return ['mail','database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Keluhan Order Baru')
            ->line('Sebuah keluhan baru telah diajukan.')
            ->line('Order: #' . optional($this->issue->order)->order_number)
            ->line('Tipe: ' . ucfirst($this->issue->type))
            ->action('Lihat', url('/admin/order-issues/' . $this->issue->id));
    }

    public function toArray($notifiable)
    {
        return [
            'issue_id' => $this->issue->id,
            'order_id' => optional($this->issue->order)->id,
            'order_number' => optional($this->issue->order)->order_number,
            'type' => $this->issue->type,
        ];
    }
}