<table>
    <thead>
        <tr>
            <th colspan="4">Sales Report</th>
        </tr>
        @if($startDate && $endDate)
            <tr>
                <th colspan="4">Date Range: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</th>
            </tr>
        @endif
        <tr>
            <th>Order ID</th>
            <th>Date</th>
            <th>Customer</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @forelse($sales as $sale)
            <tr>
                <td>{{ $sale->order_number }}</td>
                <td>{{ $sale->created_at->format('d M Y') }}</td>
                <td>{{ $sale->user->name }}</td>
                <td>{{ $sale->total_price }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4">No sales data available.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<table>
    <thead>
        <tr>
            <th colspan="5">Rental Report</th>
        </tr>
        @if($startDate && $endDate)
            <tr>
                <th colspan="5">Date Range: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</th>
            </tr>
        @endif
        <tr>
            <th>Rental ID</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Customer</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @forelse($rentals as $rental)
            <tr>
                <td>{{ $rental->id }}</td>
                <td>{{ $rental->start_date->format('d M Y') }}</td>
                <td>{{ $rental->end_date->format('d M Y') }}</td>
                <td>{{ $rental->user->name }}</td>
                <td>{{ $rental->total_price }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5">No rental data available.</td>
            </tr>
        @endforelse
    </tbody>
</table>