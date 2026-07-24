<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\OrderStatusChanged;
use App\Models\Order;

class NotificationController extends Controller
{
    public function sendTestNotification()
    {
        $user = User::first(); // Example: get the first user
        if (!$user) {
            return response('User not found.', 404);
        }

        $order = Order::where('user_id', $user->id)->first(); // Example: get the first order for the user

        if ($order) {
            $user->notify(new OrderStatusChanged($order));
            return 'Notification sent!';
        }

        return response('Order not found.', 404);
    }
}
