<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sales and Rental Report</title>
    <style>
        body { font-family: sans-serif; color: #0f172a; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        th, td { border: 1px solid #dbe3ef; padding: 8px; text-align: left; }
        th { background-color: #f1f5f9; font-size: 12px; text-transform: uppercase; }
        h1, h2 { margin-bottom: 12px; }
        .date-range { margin-bottom: 20px; color: #475569; }
    </style>
</head>
<body>
    <h1>Sales and Rental Report</h1>
    @if($startDate || $endDate)
        <div class="date-range">
            Period:
            {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d M Y') : 'All time' }}
            -
            {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d M Y') : 'Today' }}
        </div>
    @endif

    <h2>Accessory Sales</h2>
    <table>
        <thead><tr><th>Order ID</th><th>Date</th><th>Customer</th><th>Total</th></tr></thead>
        <tbody>
            @forelse($sales as $sale)
                <tr>
                    <td>{{ $sale->order_number }}</td>
                    <td>{{ $sale->created_at?->format('d M Y') }}</td>
                    <td>{{ $sale->user?->name ?? '-' }}</td>
                    <td>Rp {{ number_format($sale->total_price, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="4" style="text-align:center;">No sales data available.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Console Rentals</h2>
    <table>
        <thead><tr><th>Rental ID</th><th>Start Date</th><th>End Date</th><th>Customer</th><th>Total</th></tr></thead>
        <tbody>
            @forelse($rentals as $rental)
                <tr>
                    <td>{{ $rental->id }}</td>
                    <td>{{ $rental->start_time?->format('d M Y') }}</td>
                    <td>{{ $rental->end_time?->format('d M Y') }}</td>
                    <td>{{ $rental->user?->name ?? '-' }}</td>
                    <td>Rp {{ number_format($rental->total_price, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align:center;">No rental data available.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
