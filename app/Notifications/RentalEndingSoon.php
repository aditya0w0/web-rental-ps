<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Rental;

class RentalEndingSoon extends Notification implements ShouldQueue
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
            ->subject('Pengingat: Rental akan berakhir')
            ->line('Rental Anda akan berakhir pada ' . optional($this->rental->end_time)->format('d M Y H:i'))
            ->line('Denda keterlambatan Rp ' . number_format((int) config('service.late_fee_per_hour'),0,',','.'). ' per jam.')
            ->action('Lihat Detail', url('/my-rentals/' . $this->rental->id));
    }

    public function toArray($notifiable)
    {
        return [
            'rental_id' => $this->rental->id,
            'end_time' => optional($this->rental->end_time)->toDateTimeString(),
            'message' => 'Rental akan berakhir. Mohon kembalikan tepat waktu.',
        ];
    }
}