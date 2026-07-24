<table>
    <thead>
        <tr><th colspan="4">Accessory Sales</th></tr>
        <tr><th>Order ID</th><th>Date</th><th>Customer</th><th>Total</th></tr>
    </thead>
    <tbody>
        @forelse($sales as $sale)
            <tr>
                <td>{{ $sale->order_number }}</td>
                <td>{{ $sale->created_at?->format('d M Y') }}</td>
                <td>{{ $sale->user?->name ?? '-' }}</td>
                <td>{{ $sale->total_price }}</td>
            </tr>
        @empty
            <tr><td colspan="4">No sales data available.</td></tr>
        @endforelse
    </tbody>
</table>

<table>
    <thead>
        <tr><th colspan="5">Console Rentals</th></tr>
        <tr><th>Rental ID</th><th>Start Date</th><th>End Date</th><th>Customer</th><th>Total</th></tr>
    </thead>
    <tbody>
        @forelse($rentals as $rental)
            <tr>
                <td>{{ $rental->id }}</td>
                <td>{{ $rental->start_time?->format('d M Y') }}</td>
                <td>{{ $rental->end_time?->format('d M Y') }}</td>
                <td>{{ $rental->user?->name ?? '-' }}</td>
                <td>{{ $rental->total_price }}</td>
            </tr>
        @empty
            <tr><td colspan="5">No rental data available.</td></tr>
        @endforelse
    </tbody>
</table>
