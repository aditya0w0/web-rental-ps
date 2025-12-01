<nav x-data="{ open: false }" class="border-b text-white relative" style="background: linear-gradient(90deg,#ff9aa2 0%, #fad0c4 45%, #9b72cf 100%)">
    <style>
        .brand-chrome{font-family:'Orbitron','Figtree',sans-serif;letter-spacing:.2px;background:linear-gradient(90deg,#fff 0%,#d9d9d9 15%,#9b9b9b 30%,#ffffff 50%,#bfbfbf 70%,#ffffff 100%);-webkit-background-clip:text;background-clip:text;color:transparent;text-shadow:0 0 10px rgba(255,255,255,.35),0 2px 4px rgba(0,0,0,.25);background-size:200% 100%;animation:chromeShift 6s linear infinite}
        @keyframes chromeShift{0%{background-position:0% 0}100%{background-position:100% 0}}
        .nav-stars{position:absolute;inset:0;pointer-events:none}
        .nav-stars span{position:absolute;color:rgba(255,255,255,.9);filter:drop-shadow(0 0 6px rgba(255,255,255,.6));animation:twinkle 2.2s ease-in-out infinite}
        .nav-stars span.heart{color:#ffd1dc}
        @keyframes twinkle{0%,100%{opacity:.2;transform:scale(.9) translateY(0)}50%{opacity:1;transform:scale(1.2) translateY(-2px)}}
    </style>
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                @auth
                @php $user = Auth::user(); $showSidebar = $user && !($user->isAdmin()); @endphp
                @if($showSidebar)
                <div class="flex items-center me-3">
                    <x-dropdown align="left" width="56">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center p-2 rounded-md text-white/90 hover:bg-white/10" aria-label="Menu">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M4 7h16M4 12h16M4 17h16" stroke-width="2"/>
                                </svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <div class="px-2 py-2">
                                <x-user-sidebar theme="light" />
                            </div>
                        </x-slot>
                    </x-dropdown>
                </div>
                @endif
                @endauth
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <x-application-logo class="block h-12 w-auto fill-current text-white" />
                        <span class="brand-chrome text-lg">PlayHub</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6" style="gap:16px">
                @auth
                    @php $user = Auth::user(); $isAdmin = $user && method_exists($user,'isAdmin') ? $user->isAdmin() : (($user->role ?? 'user')==='admin'); @endphp
                    @if(!$isAdmin)
                    @php $cart = $user?->cart()->with('items')->first(); $cartCount = $cart ? $cart->items->sum('quantity') : 0; @endphp
                    <a href="{{ route('cart.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-white/90 hover:text-white hover:bg-white/10 transition">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="mr-2">
                            <circle cx="9" cy="20" r="2"/>
                            <circle cx="17" cy="20" r="2"/>
                            <path d="M3 3h2l3 12h10l3-8H8" stroke-width="2"/>
                        </svg>
                        My Cart
                        @if($cartCount > 0)
                            <span class="ml-2 inline-flex items-center justify-center min-w-[20px] h-5 px-1 rounded-full bg-white/20 text-white text-xs">{{ $cartCount }}</span>
                        @endif
                    </a>
                    @endif
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-transparent hover:text-white focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()?->name }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center" style="gap: 12px">
                        <a href="{{ route('login') }}" class="btn btn-ghost">{{ __('Login') }}</a>
                        <a href="{{ route('register') }}" class="btn btn-ghost">{{ __('Register') }}</a>
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-white hover:bg-white/10 focus:outline-none focus:bg-white/10 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <div class="nav-stars">
        <span style="left:6%;top:20%;font-size:10px">★</span>
        <span style="left:12%;top:60%;font-size:8px;animation-duration:2.8s">★</span>
        <span style="left:18%;top:35%;font-size:9px;animation-duration:2.4s">★</span>
        <span class="heart" style="left:24%;top:50%;font-size:8px;animation-duration:3.1s">♥</span>
        <span style="left:30%;top:25%;font-size:10px;animation-duration:2.6s">★</span>
        <span style="left:36%;top:55%;font-size:8px;animation-duration:2.9s">★</span>
        <span style="left:62%;top:40%;font-size:9px;animation-duration:2.5s">★</span>
        <span class="heart" style="left:70%;top:65%;font-size:8px;animation-duration:3s">♥</span>
        <span style="left:78%;top:30%;font-size:10px;animation-duration:2.7s">★</span>
        <span style="left:86%;top:55%;font-size:8px;animation-duration:2.3s">★</span>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden text-white" style="background: linear-gradient(90deg,#ff9aa2 0%, #fad0c4 50%, #9b72cf 100%)">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            @auth
            @php $user = Auth::user(); $isAdmin = $user && method_exists($user,'isAdmin') ? $user->isAdmin() : (($user->role ?? 'user')==='admin'); @endphp
            @if(!$isAdmin)
            <a href="{{ route('cart.index') }}" class="block px-3 py-2 text-sm rounded hover:bg-white/10">My Cart</a>
            @endif
            <a href="{{ route('orders.index') }}" class="block px-3 py-2 text-sm rounded hover:bg-white/10">Orders</a>
            <a href="{{ route('user.rentals.index') }}" class="block px-3 py-2 text-sm rounded hover:bg-white/10">Rentals</a>
            <a href="{{ route('transaction.track') }}" class="block px-3 py-2 text-sm rounded hover:bg-white/10">Track Order</a>
            <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-sm rounded hover:bg-white/10">Profile</a>
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t" style="border-color:#9b72cf">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-white">{{ Auth::user()?->name }}</div>
                    <div class="font-medium text-sm text-white/80">{{ Auth::user()?->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="px-4">
                    <a href="{{ route('login') }}" class="block px-3 py-2 text-sm rounded hover:bg-white/10">{{ __('Login') }}</a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 text-sm rounded hover:bg-white/10">{{ __('Register') }}</a>
                </div>
            @endauth
        </div>
    </div>
</nav>
