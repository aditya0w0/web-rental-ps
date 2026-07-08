@php
    $user = auth()->user();
    $isAdmin = $user && (method_exists($user, 'isAdmin') ? $user->isAdmin() : (($user->role ?? 'user') === 'admin'));
    $isOwner = $user && (method_exists($user, 'isOwner') ? $user->isOwner() : (($user->role ?? 'user') === 'owner'));
    $homeRoute = $isAdmin ? route('admin.dashboard') : (auth()->check() ? route('dashboard') : route('home'));
@endphp

<nav x-data="{ open: false }" class="sticky top-0 z-40 border-b border-slate-200 bg-white/95 text-slate-900 backdrop-blur">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between gap-4">
            <div class="flex min-w-0 items-center">
                <a href="{{ $homeRoute }}" class="flex items-center rounded-lg">
                    <x-application-logo class="block h-12 w-auto max-w-[11rem]" />
                </a>

                <div class="hidden sm:ms-8 sm:flex sm:items-center sm:gap-1">
                    @auth
                        @if($isAdmin)
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard') || request()->routeIs('dashboard')">
                            Admin
                        </x-nav-link>
                        <x-nav-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')">
                            Orders
                        </x-nav-link>
                        <x-nav-link :href="route('admin.rentals.index')" :active="request()->routeIs('admin.rentals.*')">
                            Rentals
                        </x-nav-link>
                        <x-nav-link :href="route('admin.playstation-types.index')" :active="request()->routeIs('admin.playstation-types.*') || request()->routeIs('admin.playstation-units.*') || request()->routeIs('admin.accessories.*')">
                            Inventory
                        </x-nav-link>
                        <x-nav-link :href="route('admin.article-comments.index')" :active="request()->routeIs('admin.article-comments.*')">
                            Comments
                        </x-nav-link>
                        @if($isOwner)
                        <x-nav-link :href="route('admin.admin-users.index')" :active="request()->routeIs('admin.admin-users.*')">
                            Employees
                        </x-nav-link>
                        @endif
                        <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
                            Reports
                        </x-nav-link>
                        @else
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            Dashboard
                        </x-nav-link>
                        <x-nav-link :href="route('products.playstation')" :active="request()->routeIs('products.playstation') || request()->routeIs('rent.*')">
                            Rent PS
                        </x-nav-link>
                        <x-nav-link :href="route('products.accessories')" :active="request()->routeIs('products.accessories')">
                            Accessories
                        </x-nav-link>
                        <x-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')">
                            Accessory Orders
                        </x-nav-link>
                        <x-nav-link :href="route('user.rentals.index')" :active="request()->routeIs('user.rentals.*') || request()->routeIs('rentals.*')">
                            Rentals
                        </x-nav-link>
                        <x-nav-link :href="route('transaction.track')" :active="request()->routeIs('transaction.track')">
                            Track
                        </x-nav-link>
                        @endif
                    @else
                        <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                            Home
                        </x-nav-link>
                        <x-nav-link :href="route('products.playstation')" :active="request()->routeIs('products.playstation')">
                            PlayStation
                        </x-nav-link>
                        <x-nav-link :href="route('products.accessories')" :active="request()->routeIs('products.accessories')">
                            Accessories
                        </x-nav-link>
                        <x-nav-link :href="route('transaction.track')" :active="request()->routeIs('transaction.track')">
                            Track
                        </x-nav-link>
                    @endauth
                </div>
            </div>

            <div class="hidden shrink-0 sm:flex sm:items-center sm:gap-3">
                @auth
                    @if(!$isAdmin)
                        @php $cart = $user?->cart()->with('items')->first(); $cartCount = $cart ? $cart->items->sum('quantity') : 0; @endphp
                        <a href="{{ route('cart.index') }}" class="relative inline-flex h-10 w-10 items-center justify-center rounded-lg text-slate-700 hover:bg-slate-100" aria-label="Cart">
                            <i class="fas fa-cart-shopping" aria-hidden="true"></i>
                            @if($cartCount > 0)
                                <span class="absolute -right-1 -top-1 inline-flex min-w-5 items-center justify-center rounded-full bg-sky-600 px-1.5 text-xs font-semibold text-white">{{ $cartCount }}</span>
                            @endif
                        </a>
                    @endif

                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                                <span>{{ $user?->name }}</span>
                                <i class="fas fa-chevron-down text-xs" aria-hidden="true"></i>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            @if($isAdmin)
                                <x-dropdown-link :href="route('admin.dashboard')">Admin Dashboard</x-dropdown-link>
                                @if($isOwner)
                                    <x-dropdown-link :href="route('admin.admin-users.index')">Admin Employees</x-dropdown-link>
                                @endif
                                <x-dropdown-link :href="route('admin.sessions.active')">Active Sessions</x-dropdown-link>
                            @else
                                <x-dropdown-link :href="route('dashboard')">Dashboard</x-dropdown-link>
                                <x-dropdown-link :href="route('profile.edit')">Profile</x-dropdown-link>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    Log Out
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="btn btn-secondary py-2 text-sm">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-primary py-2 text-sm">Register</a>
                @endauth
            </div>

            <div class="flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex h-10 w-10 items-center justify-center rounded-lg text-slate-700 hover:bg-slate-100" aria-label="Toggle navigation">
                    <i class="fas" :class="open ? 'fa-xmark' : 'fa-bars'" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden border-t border-slate-200 bg-white sm:hidden">
        <div class="space-y-1 px-4 py-3">
            @auth
                @if($isAdmin)
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard') || request()->routeIs('dashboard')">Admin</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')">Orders</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.rentals.index')" :active="request()->routeIs('admin.rentals.*')">Rentals</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.playstation-types.index')" :active="request()->routeIs('admin.playstation-types.*')">PlayStation Types</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.playstation-units.index')" :active="request()->routeIs('admin.playstation-units.*')">PlayStation Units</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.accessories.index')" :active="request()->routeIs('admin.accessories.*')">Accessories</x-responsive-nav-link>
                @if($isOwner)
                <x-responsive-nav-link :href="route('admin.admin-users.index')" :active="request()->routeIs('admin.admin-users.*')">Employees</x-responsive-nav-link>
                @endif
                <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">Reports</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.shipping.index')" :active="request()->routeIs('admin.shipping.*')">Shipping</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.article-comments.index')" :active="request()->routeIs('admin.article-comments.*')">Article Comments</x-responsive-nav-link>
                @else
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('products.playstation')" :active="request()->routeIs('products.playstation') || request()->routeIs('rent.*')">Rent PS</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('products.accessories')" :active="request()->routeIs('products.accessories')">Accessories</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')">Accessory Orders</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('user.rentals.index')" :active="request()->routeIs('user.rentals.*') || request()->routeIs('rentals.*')">Rentals</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('cart.index')">Cart</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('profile.edit')">Profile</x-responsive-nav-link>
                @endif
            @else
                <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">Home</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('products.playstation')" :active="request()->routeIs('products.playstation')">PlayStation</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('products.accessories')" :active="request()->routeIs('products.accessories')">Accessories</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('transaction.track')" :active="request()->routeIs('transaction.track')">Track Order</x-responsive-nav-link>
            @endauth
        </div>

        <div class="border-t border-slate-200 px-4 py-3">
            @auth
                <div class="mb-3">
                    <div class="font-semibold text-slate-950">{{ $user?->name }}</div>
                    <div class="text-sm text-slate-500">{{ $user?->email }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        Log Out
                    </x-responsive-nav-link>
                </form>
            @else
                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('login') }}" class="btn btn-secondary text-sm">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-primary text-sm">Register</a>
                </div>
            @endauth
        </div>
    </div>
</nav>
