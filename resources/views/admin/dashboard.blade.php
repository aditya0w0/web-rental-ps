@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-800">Admin Dashboard</h1>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Logout</button>
                </form>
            </div>
            <p class="text-gray-600">Selamat datang, {{ auth()->user()->name }}.</p>
            <p class="text-gray-500">Pantau statistik, kelola rental dan pesanan, serta akses laporan dalam satu tempat.</p>
            <div class="flex justify-end mt-4">
                <div id="liveClock" class="px-6 py-2 rounded-full shadow-md font-semibold tracking-wide text-base md:text-lg" style="background:linear-gradient(90deg,#ff9aa2 0%, #f3d295 100%);color:#ffffff;border:1px solid rgba(255,255,255,.6)">--</div>
            </div>
        </div>

    <!-- Charts -->
    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-lg font-bold">Grafik Bulanan</h2>
            <div class="text-sm text-gray-600">Tahun {{ now()->year }}</div>
        </div>
        <div class="mb-2">
            <div class="grid grid-cols-1">
                <canvas id="chartCounts" height="220"></canvas>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
        <script>
        (function(){
            var labels = @json($labels);
            var rentals = @json($rentalsSeries);
            var orders = @json($ordersSeries);
            var revenue = @json($revenueSeries);
            function updateClock(){
                var d = new Date();
                var s = d.toLocaleString('id-ID',{weekday:'long', day:'2-digit', month:'long', year:'numeric', hour:'2-digit', minute:'2-digit', second:'2-digit'});
                var el = document.getElementById('liveClock'); if(el){ el.textContent = s; }
            }
            updateClock(); setInterval(updateClock, 1000);
            var maxVal = Math.max.apply(null, rentals.concat(orders));
            new Chart(document.getElementById('chartCounts'), {
                type: 'line',
                data: { labels: labels, datasets: [
                    { label: 'Rental', data: rentals, borderColor: '#9b72cf', backgroundColor: 'rgba(155,114,207,.15)', tension:.25, pointRadius:4, pointBackgroundColor:'#9b72cf' },
                    { label: 'Order', data: orders, borderColor: '#ff9aa2', backgroundColor: 'rgba(255,154,162,.15)', tension:.25, pointRadius:4, pointBackgroundColor:'#ff9aa2' }
                ]}, options: { responsive: true, maintainAspectRatio:false, plugins:{ legend:{position:'bottom'} }, scales:{ y:{ beginAtZero:true, ticks:{ stepSize:1 }, suggestedMax: maxVal + 2 } } }
            });
        })();
        </script>
    </div>

    <!-- Highlighted Summary -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="p-6 rounded-lg text-white" style="background: linear-gradient(90deg,#ff9aa2 0%, #f3d295 100%)">
            <div class="flex items-center gap-3 mb-2">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-full" style="background:linear-gradient(135deg,#ffe3ea,#ffd1dc)">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ff5f85"><path d="M12 3c4 0 7 3 7 7s-3 7-7 7-7-3-7-7 3-7 7-7z" stroke-width="2"/><path d="M9 12h6M10 15h4" stroke-width="2"/></svg>
                </span>
                <div class="text-sm">Total Pendapatan</div>
            </div>
            <div class="text-2xl font-bold">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</div>
        </div>
        <div class="p-6 rounded-lg text-white" style="background: linear-gradient(90deg,#9b72cf 0%, #ff9aa2 100%)">
            <div class="flex items-center gap-3 mb-2">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-full" style="background:linear-gradient(135deg,#ffe3ea,#ffd1dc)">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ff5f85"><circle cx="9" cy="20" r="2"/><circle cx="17" cy="20" r="2"/><path d="M3 3h2l3 12h10l3-8H8" stroke-width="2"/></svg>
                </span>
                <div class="text-sm">Order per Bulan ({{ now()->year }})</div>
            </div>
            <div class="text-2xl font-bold">{{ array_sum($ordersSeries->toArray()) }}</div>
        </div>
        <div class="p-6 rounded-lg text-white" style="background: linear-gradient(90deg,#ff9aa2 0%, #f3b5a8 100%)">
            <div class="flex items-center gap-3 mb-2">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-full" style="background:linear-gradient(135deg,#ffe3ea,#ffd1dc)">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ff5f85"><rect x="5" y="9" width="14" height="8" rx="4" stroke-width="2"/><circle cx="9" cy="13" r="1" stroke="#ff5f85"/><circle cx="15" cy="13" r="1" stroke="#ff5f85"/></svg>
                </span>
                <div class="text-sm">Rental per Bulan ({{ now()->year }})</div>
            </div>
            <div class="text-2xl font-bold">{{ array_sum($rentalsSeries->toArray()) }}</div>
        </div>
        <div class="p-6 rounded-lg text-white" style="background: linear-gradient(90deg,#9b72cf 0%, #f3d295 100%)">
            <div class="flex items-center gap-3 mb-2">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-full" style="background:linear-gradient(135deg,#ffe3ea,#ffd1dc)">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ff5f85"><path d="M4 7h16M4 12h10" stroke-width="2"/><circle cx="17" cy="12" r="2" stroke-width="2"/></svg>
                </span>
                <div class="text-sm">Active Rentals</div>
            </div>
            <div class="text-2xl font-bold">{{ $stats['active_rentals'] ?? 0 }}</div>
        </div>
        <div class="p-6 rounded-lg text-white" style="background: linear-gradient(90deg,#f3d295 0%, #ff9aa2 100%)">
            <div class="flex items-center gap-3 mb-2">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-full" style="background:linear-gradient(135deg,#ffe3ea,#ffd1dc)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ff5f85"><circle cx="12" cy="12" r="9" stroke-width="2"/><path d="M12 7v6" stroke-width="2"/><circle cx="12" cy="17" r="1"/></svg></span>
                <div class="text-sm">Estimasi Denda Keterlambatan</div>
            </div>
            <div class="text-2xl font-bold">Rp {{ number_format($lateFeeTotal ?? 0, 0, ',', '.') }}</div>
        </div>
        <div class="p-6 rounded-lg text-white" style="background: linear-gradient(90deg,#f3b5a8 0%, #ff9aa2 100%)">
            <div class="flex items-center gap-3 mb-2">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-full" style="background:linear-gradient(135deg,#ffe3ea,#ffd1dc)"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ff5f85"><path d="M12 7v5l3 2" stroke-width="2"/><circle cx="12" cy="12" r="9" stroke-width="2"/></svg></span>
                <div class="text-sm">Pending Payments</div>
            </div>
            <div class="text-2xl font-bold">{{ $stats['pending_transactions'] ?? 0 }}</div>
        </div>
        <div class="p-6 rounded-lg text-white" style="background: linear-gradient(90deg,#9b72cf 0%, #f3b5a8 100%)">
            <div class="flex items-center gap-3 mb-2">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-full" style="background:linear-gradient(135deg,#ffe3ea,#ffd1dc)"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ff5f85"><rect x="4" y="6" width="16" height="12" rx="2" stroke-width="2"/><path d="M8 10h8" stroke-width="2"/></svg></span>
                <div class="text-sm">Available Units</div>
            </div>
            <div class="text-2xl font-bold">{{ $stats['available_units'] ?? 0 }}</div>
        </div>
        <div class="p-6 rounded-lg text-white" style="background: linear-gradient(90deg,#ff9aa2 0%, #9b72cf 100%)">
            <div class="flex items-center gap-3 mb-2">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-full" style="background:linear-gradient(135deg,#ffe3ea,#ffd1dc)"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ff5f85"><path d="M6 8h12l-2 8H8z" stroke-width="2"/><circle cx="9" cy="20" r="2"/><circle cx="17" cy="20" r="2"/></svg></span>
                <div class="text-sm">Accessories In Stock</div>
            </div>
            <div class="text-2xl font-bold">{{ $stats['total_accessories'] ?? 0 }}</div>
        </div>
        
    </div>

    <!-- Stats Cards removed; merged into highlighted summary -->

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
        <a href="{{ route('admin.playstation-types.index') }}" class="block p-6 bg-green-50 rounded-lg hover:bg-green-100 transition">
            <div class="flex items-center gap-3 mb-2">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full" style="background:linear-gradient(135deg,#ffe3ea,#ffd1dc)">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ff5f85"><path d="M7 9h10M6 12h12M7 15h10" stroke-width="2"/></svg>
                </span>
                <h4 class="font-bold text-emerald-700">PlayStation Types</h4>
            </div>
            <p class="text-sm text-gray-600">Kelola jenis PS (PS4, PS5, dll)</p>
        </a>
        <a href="{{ route('admin.playstation-units.index') }}" class="block p-6 bg-green-50 rounded-lg hover:bg-green-100 transition">
            <div class="flex items-center gap-3 mb-2">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full" style="background:linear-gradient(135deg,#ffe3ea,#ffd1dc)">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ff5f85"><rect x="4" y="7" width="16" height="10" rx="2" stroke-width="2"/><path d="M8 11h8" stroke-width="2"/></svg>
                </span>
                <h4 class="font-bold text-emerald-700">PlayStation Units</h4>
            </div>
            <p class="text-sm text-gray-600">Kelola unit PS (PS5-01, PS4-02, dll)</p>
        </a>
        <a href="{{ route('admin.accessories.index') }}" class="block p-6 bg-green-50 rounded-lg hover:bg-green-100 transition">
            <div class="flex items-center gap-3 mb-2">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full" style="background:linear-gradient(135deg,#ffe3ea,#ffd1dc)">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ff5f85"><path d="M9 7l6 6" stroke-width="2"/><path d="M7 13l4 4" stroke-width="2"/><circle cx="9" cy="7" r="2"/><circle cx="13" cy="17" r="2"/></svg>
                </span>
                <h4 class="font-bold text-emerald-700">Accessories</h4>
            </div>
            <p class="text-sm text-gray-600">Kelola aksesoris (stick, charger, dll)</p>
        </a>
        <a href="{{ route('admin.rentals.index') }}" class="block p-6 bg-green-50 rounded-lg hover:bg-green-100 transition">
            <div class="flex items-center gap-3 mb-2">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full" style="background:linear-gradient(135deg,#ffe3ea,#ffd1dc)">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ff5f85"><circle cx="12" cy="12" r="9" stroke-width="2"/><path d="M12 7v5l3 2" stroke-width="2"/></svg>
                </span>
                <h4 class="font-bold text-emerald-700">Rentals</h4>
            </div>
            <p class="text-sm text-gray-600">Lihat & kelola rental aktif</p>
        </a>
        <a href="{{ route('admin.orders.index') }}" class="block p-6 bg-green-50 rounded-lg hover:bg-green-100 transition">
            <div class="flex items-center gap-3 mb-2">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full" style="background:linear-gradient(135deg,#ffe3ea,#ffd1dc)">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ff5f85"><circle cx="9" cy="20" r="2"/><circle cx="17" cy="20" r="2"/><path d="M3 3h2l3 12h10l3-8H8" stroke-width="2"/></svg>
                </span>
                <h4 class="font-bold text-emerald-700">Orders</h4>
            </div>
            <p class="text-sm text-gray-600">Lihat & kelola pesanan aksesoris</p>
        </a>
        <a href="{{ route('reports.index') }}" class="block p-6 bg-green-50 rounded-lg hover:bg-green-100 transition">
            <div class="flex items-center gap-3 mb-2">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full" style="background:linear-gradient(135deg,#ffe3ea,#ffd1dc)">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ff5f85"><path d="M6 16h12" stroke-width="2"/><rect x="6" y="8" width="3" height="8" rx="1"/><rect x="11" y="11" width="3" height="5" rx="1"/><rect x="16" y="10" width="3" height="6" rx="1"/></svg>
                </span>
                <h4 class="font-bold text-emerald-700">Reports</h4>
            </div>
            <p class="text-sm text-gray-600">Laporan pendapatan (PDF/Excel)</p>
        </a>
        <a href="{{ url('/track') }}" class="block p-6 bg-green-50 rounded-lg hover:bg-green-100 transition">
            <div class="flex items-center gap-3 mb-2">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full" style="background:linear-gradient(135deg,#ffe3ea,#ffd1dc)">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ff5f85"><path d="M12 3a7 7 0 0 1 7 7c0 5-7 11-7 11s-7-6-7-11a7 7 0 0 1 7-7z" stroke-width="2"/></svg>
                </span>
                <h4 class="font-bold text-emerald-700">Track Order</h4>
            </div>
            <p class="text-sm text-gray-600">Cek status order pelanggan</p>
        </a>
        <a href="{{ route('admin.articles.index') }}" class="block p-6 bg-green-50 rounded-lg hover:bg-green-100 transition">
            <div class="flex items-center gap-3 mb-2">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full" style="background:linear-gradient(135deg,#ffe3ea,#ffd1dc)">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ff5f85"><rect x="5" y="4" width="14" height="16" rx="2" stroke-width="2"/><path d="M8 8h8M8 12h8M8 16h6" stroke-width="2"/></svg>
                </span>
                <h4 class="font-bold text-emerald-700">Artikel</h4>
            </div>
            <p class="text-sm text-gray-600">Kelola artikel beranda</p>
        </a>
        <a href="{{ route('admin.sessions.active') }}" class="block p-6 bg-green-50 rounded-lg hover:bg-green-100 transition">
            <div class="flex items-center gap-3 mb-2">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full" style="background:linear-gradient(135deg,#fff4e1,#fde6b2)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f59e0b"><path d="M5 12h14" stroke-width="2"/><path d="M12 5v14" stroke-width="2"/></svg></span>
                <h4 class="font-bold text-emerald-700">User Active Sessions</h4>
            </div>
            <div class="text-2xl font-bold">{{ $stats['users_logged_in'] ?? 0 }}</div>
            <p class="text-sm text-gray-600">Jumlah sesi login aktif saat ini</p>
        </a>
    </div>

    <!-- Rental Terlambat -->
    <div class="bg-white p-6 rounded-lg shadow mb-8">
        <h2 class="text-lg font-bold mb-3">Rental Terlambat</h2>
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left text-gray-600">
                    <th class="py-2">Pelanggan</th>
                    <th class="py-2">Tipe</th>
                    <th class="py-2">Selesai</th>
                    <th class="py-2">Jam Telat</th>
                    <th class="py-2">Estimasi Denda</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lateRentals as $r)
                @php $hours = max(1, (int) ceil(optional($r->end_time)->diffInMinutes(now())/60)); $fee = $hours * (int) config('service.late_fee_per_hour'); @endphp
                <tr class="border-t">
                    <td class="py-2">{{ optional($r->user)->name }}</td>
                    <td class="py-2">{{ optional($r->type)->name ?? '-' }}</td>
                    <td class="py-2">{{ optional($r->end_time)->format('d M Y H:i') }}</td>
                    <td class="py-2">{{ $hours }}</td>
                    <td class="py-2">Rp {{ number_format($fee,0,',','.') }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-6 text-center text-gray-500">Tidak ada rental terlambat.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-10">
        <!-- Recent Rentals -->
        <div class="bg-white shadow rounded-lg">
            <div class="p-6 border-b">
                <h3 class="text-lg font-bold">Rental Terbaru</h3>
            </div>
            <div class="p-6">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-600">
                            <th class="py-2">Pelanggan</th>
                            <th class="py-2">Tipe PS</th>
                            <th class="py-2">Mulai</th>
                            <th class="py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentRentals as $r)
                            <tr class="border-t">
                                <td class="py-2">{{ $r->user->name ?? '-' }}</td>
                                <td class="py-2">{{ $r->type->name ?? '-' }}</td>
                                <td class="py-2">{{ $r->start_time?->format('d M Y H:i') }}</td>
                                <td class="py-2">
                                    @php $s = strtolower($r->status ?? ''); $badge = in_array($s,['completed','paid','delivered']) ? 'badge-success' : (in_array($s,['pending','processing','active']) ? 'badge-warning' : 'badge-danger'); @endphp
                                    <span class="badge {{ $badge }}">{{ ucfirst($r->status) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-gray-500">Belum ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Removed Recent Transactions panel -->
        <!-- Recent Orders -->
        <div class="bg-white shadow rounded-lg">
            <div class="p-6 border-b">
                <h3 class="text-lg font-bold">Orders Terbaru</h3>
            </div>
            <div class="p-6">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-600">
                            <th class="py-2">Order #</th>
                            <th class="py-2">Pelanggan</th>
                            <th class="py-2">Tanggal</th>
                            <th class="py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders ?? [] as $o)
                            <tr class="border-t">
                                <td class="py-2"><a href="{{ route('orders.show', $o) }}" class="link">{{ $o->order_number ?? $o->id }}</a></td>
                                <td class="py-2">{{ $o->user->name ?? '-' }}</td>
                                <td class="py-2">{{ $o->created_at?->format('d M Y H:i') }}</td>
                                <td class="py-2">
                                    @php $os = strtolower($o->status ?? 'pending'); $badge = in_array($os,['completed','delivered','shipped']) ? 'badge-success' : (in_array($os,['pending','processing']) ? 'badge-warning' : 'badge-danger'); @endphp
                                    <span class="badge {{ $badge }}">{{ ucfirst($o->status ?? 'pending') }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-gray-500">Belum ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="bg-white shadow rounded-lg p-6 mt-8">
        <h2 class="text-lg font-bold mb-3">Notifikasi</h2>
        @php $notifs = auth()->user()->unreadNotifications ?? collect(); @endphp
        @if($notifs->count())
            <ul class="space-y-2">
                @foreach($notifs->take(10) as $n)
                    <li class="flex items-center justify-between border rounded px-3 py-2">
                        <div class="text-sm">
                            <div class="font-semibold">{{ $n->data['message'] ?? ($n->data['type'] ?? class_basename($n->type)) }}</div>
                            @if(isset($n->data['order_number']))
                                <div class="text-gray-600">Order #{{ $n->data['order_number'] }}</div>
                            @endif
                        </div>
                        @if(isset($n->data['issue_id']))
                            <a href="{{ route('admin.order-issues.show', $n->data['issue_id']) }}" class="px-3 py-1 rounded bg-indigo-600 text-white">Lihat</a>
                        @endif
                    </li>
                @endforeach
            </ul>
        @else
            <div class="text-gray-500 text-sm">Tidak ada notifikasi baru.</div>
        @endif
    </div>
</div>
@endsection
