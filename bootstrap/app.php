<?php

use Illuminate\Foundation\Application;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function (Schedule $schedule) {
        $schedule->call(function () {
            $now = now();

            $orders = \App\Models\Order::where('status', 'pending')
                ->whereNull('payment_proof')
                ->where('created_at', '<=', $now->copy()->subHour())
                ->with(['items.accessory', 'user'])
                ->get();

            foreach ($orders as $order) {
                foreach ($order->items as $item) {
                    if ($item->accessory) {
                        $item->accessory->increment('stock', (int) $item->quantity);
                    }
                }
                $order->update([
                    'status' => 'failed',
                    'rejection_reason' => 'Auto-cancelled: payment timeout',
                    'payment_date' => null,
                ]);
                if ($order->user) {
                    $order->user->notify(new \App\Notifications\OrderStatusChanged($order));
                }
            }

            $rentals = \App\Models\Rental::where('status', 'pending')
                ->whereNull('payment_proof')
                ->where('created_at', '<=', $now->copy()->subHour())
                ->with(['unit', 'user'])
                ->get();

            foreach ($rentals as $rental) {
                if ($rental->unit) {
                    $rental->unit->update(['status' => 'available']);
                }
                $rental->update([
                    'status' => 'cancelled',
                    'rejection_reason' => 'Auto-cancelled: payment timeout',
                    'payment_date' => null,
                ]);
                if ($rental->user) {
                    $rental->user->notify(new \App\Notifications\RentalStatusChanged($rental));
                }
            }
        })->everyFiveMinutes();
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            // Tambahkan alias 'admin' agar route menggunakan middleware 'admin' berfungsi
            'admin' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
