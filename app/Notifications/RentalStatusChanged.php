<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Rental;

class RentalStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public Rental $rental;

    public function __construct(Rental $rental)
    {
        $this->rental = $rental;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Rental Status Updated: #' . $this->rental->id)
            ->line('Status rental Anda diperbarui menjadi: ' . ucfirst($this->rental->status))
            ->action('Lihat Rental', url('/admin/rentals/' . $this->rental->id));
    }

    public function toArray($notifiable)
    {
        return [
            'rental_id' => $this->rental->id,
            'status' => $this->rental->status,
            'message' => 'Status rental #' . $this->rental->id . ' diperbarui menjadi ' . $this->rental->status,
        ];
    }
}