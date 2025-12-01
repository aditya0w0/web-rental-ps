<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\OrderIssue;

class OrderIssueUpdated extends Notification implements ShouldQueue
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
            ->subject('Update Keluhan Order')
            ->line('Keluhan Anda telah diperbarui oleh admin.')
            ->line('Status: ' . ucfirst($this->issue->status))
            ->line('Respon Admin: ' . ($this->issue->admin_response ?? '-'))
            ->action('Lihat Order', url('/orders/' . optional($this->issue->order)->id));
    }

    public function toArray($notifiable)
    {
        return [
            'issue_id' => $this->issue->id,
            'order_id' => optional($this->issue->order)->id,
            'order_number' => optional($this->issue->order)->order_number,
            'status' => $this->issue->status,
            'admin_response' => $this->issue->admin_response,
        ];
    }
}