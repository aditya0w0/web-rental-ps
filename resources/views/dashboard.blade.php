<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <h3 class="text-lg font-bold mb-4">Welcome, {{ auth()->user()->name }}!</h3>
                    <p class="text-gray-600 mb-6">Kelola sistem rental PlayStation di sini.</p>

                    <!-- MENU ADMIN -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        
                        <a href="{{ route('admin.playstation-types.index') }}" class="block p-6 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                            <h4 class="font-bold text-blue-700">PlayStation Types</h4>
                            <p class="text-sm text-gray-600">Kelola jenis PS (PS4, PS5, dll)</p>
                        </a>

                        <a href="{{ route('admin.playstation-units.index') }}" class="block p-6 bg-green-50 rounded-lg hover:bg-green-100 transition">
                            <h4 class="font-bold text-green-700">PlayStation Units</h4>
                            <p class="text-sm text-gray-600">Kelola unit PS (PS5-01, PS4-02, dll)</p>
                        </a>

                        <a href="{{ route('admin.accessories.index') }}" class="block p-6 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
                            <h4 class="font-bold text-purple-700">Accessories</h4>
                            <p class="text-sm text-gray-600">Kelola aksesoris (stick, charger, dll)</p>
                        </a>

                        <a href="{{ route('admin.rentals.index') }}" class="block p-6 bg-red-50 rounded-lg hover:bg-red-100 transition">
                            <h4 class="font-bold text-red-700">Rentals</h4>
                            <p class="text-sm text-gray-600">Lihat & kelola rental aktif</p>
                        </a>

                        <a href="{{ route('reports.index') }}" class="block p-6 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition">
                            <h4 class="font-bold text-yellow-700">Reports</h4>
                            <p class="text-sm text-gray-600">Laporan pendapatan (PDF/Excel)</p>
                        </a>

                        <a href="{{ url('/track') }}" class="block p-6 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">
                            <h4 class="font-bold text-indigo-700">Track Order</h4>
                            <p class="text-sm text-gray-600">Cek status order pelanggan</p>
                        </a>

                    </div>

                    <!-- LOGOUT -->
                    <div class="mt-8">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-red-600 hover:text-red-800">
                                Logout
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>