<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\Transaction;
use App\Models\Order;
use App\Models\PlaystationUnit;
use App\Models\Accessory;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Auto-cancel pending payments older than 1 hour and restore stock/unit
        $now = \Carbon\Carbon::now();
        $expiredOrders = \App\Models\Order::where('status','pending')
            ->whereNull('payment_proof')
            ->where('created_at','<=',$now->copy()->subHour())
            ->with(['items.accessory','user'])->get();
        foreach ($expiredOrders as $order) {
            foreach ($order->items as $item) {
                if ($item->accessory) {
                    $item->accessory->increment('stock', (int) $item->quantity);
                }
            }
            $order->update(['status'=>'failed','rejection_reason'=>'Auto-cancelled: payment timeout','payment_date'=>null]);
            if ($order->user) {
                $order->user->notify(new \App\Notifications\OrderStatusChanged($order));
            }
        }

        $expiredRentals = \App\Models\Rental::where('status','pending')
            ->whereNull('payment_proof')
            ->where('created_at','<=',$now->copy()->subHour())
            ->with(['unit','user'])->get();
        foreach ($expiredRentals as $rental) {
            if ($rental->unit) {
                $rental->unit->update(['status' => 'available']);
            }
            $rental->update(['status'=>'cancelled','rejection_reason'=>'Auto-cancelled: payment timeout','payment_date'=>null]);
            if ($rental->user) {
                $rental->user->notify(new \App\Notifications\RentalStatusChanged($rental));
            }
        }

        $pendingPayments = (
            (int) Transaction::where('payment_status', 'pending')->count()
            + (int) Order::where('status', 'pending')->count()
            + (int) Rental::where('status', 'pending')->count()
        );

        $stats = [
            'total_rentals' => Rental::count(),
            'active_rentals' => Rental::where('status', 'active')->count(),
            'total_transactions' => Transaction::count(),
            'pending_transactions' => $pendingPayments,
            'available_units' => PlaystationUnit::where('status', 'available')->count(),
            'total_accessories' => Accessory::sum('stock'),
            'users_total' => User::count(),
            'users_logged_in' => (int) DB::table('sessions')->whereNotNull('user_id')->count(),
            'users_this_month' => User::whereYear('created_at', Carbon::now()->year)->whereMonth('created_at', Carbon::now()->month)->count(),
        ];

        // Monthly rental statistics
        $monthlyRentals = Rental::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Monthly transaction statistics (revenue)
        $monthlyTransactions = Transaction::selectRaw('MONTH(created_at) as month, SUM(total_amount) as total')
            ->whereYear('created_at', Carbon::now()->year)
            ->where('payment_status', 'paid')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Monthly orders (accessories) count
        $monthlyOrders = Order::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Build arrays for charts (months 1..12)
        $labels = collect(range(1,12))->map(function($m){ return str_pad($m,2,'0',STR_PAD_LEFT); });
        $rentalsSeries = $labels->map(function($m) use ($monthlyRentals){
            return (int) optional($monthlyRentals->firstWhere('month',(int)$m))->count ?? 0;
        });
        $ordersSeries = $labels->map(function($m) use ($monthlyOrders){
            return (int) optional($monthlyOrders->firstWhere('month',(int)$m))->count ?? 0;
        });
        $revenueSeries = $labels->map(function($m) use ($monthlyTransactions){
            return (int) optional($monthlyTransactions->firstWhere('month',(int)$m))->total ?? 0;
        });

        $ordersRevenue = Order::whereYear('created_at', Carbon::now()->year)
            ->where(function($q){
                $q->where('status','paid')->orWhere('fulfillment_status','completed');
            })->sum('total_price');
        $rentalsRevenue = Rental::whereYear('created_at', Carbon::now()->year)
            ->where('status','completed')->sum('total_price');
        $transactionsRevenue = (float) $monthlyTransactions->sum('total');
        $totalRevenue = (int) ($transactionsRevenue + $ordersRevenue + $rentalsRevenue);

        $lateFeePerHour = (int) config('service.late_fee_per_hour');
        $now = Carbon::now();
        $lateRentals = Rental::whereIn('status',['active','confirmed'])
            ->whereNotNull('end_time')
            ->where('end_time','<',$now)
            ->with(['user','type'])
            ->get();
        $lateFeeTotal = $lateRentals->sum(function ($r) use ($now, $lateFeePerHour) {
            $hours = max(1, (int) ceil(optional($r->end_time)->diffInMinutes($now)/60));
            return $hours * $lateFeePerHour;
        });

        // Recent rentals
        $recentRentals = Rental::with(['user', 'type'])
            ->latest()
            ->limit(10)
            ->get();

        // Recent transactions
        $recentTransactions = Transaction::with(['user'])
            ->latest()
            ->limit(10)
            ->get();

        // Recent orders (accessories)
        $recentOrders = Order::with('user')
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'monthlyRentals', 'monthlyTransactions', 'monthlyOrders', 'labels', 'rentalsSeries', 'ordersSeries', 'revenueSeries', 'totalRevenue', 'recentRentals', 'recentTransactions', 'recentOrders', 'lateRentals', 'lateFeeTotal'));
    }

    public function activeSessions()
    {
        $sessions = DB::table('sessions')
            ->whereNotNull('user_id')
            ->join('users','sessions.user_id','=','users.id')
            ->orderByDesc('last_activity')
            ->select([ 'sessions.id', 'sessions.user_id', 'sessions.ip_address', 'sessions.user_agent', 'sessions.last_activity', 'users.name', 'users.email', 'users.role' ])
            ->get();

        $count = (int) $sessions->count();
        return view('admin.active-sessions', compact('sessions','count'));
    }
}
