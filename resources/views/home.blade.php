@extends('layouts.app')

@section('title', 'Beranda - PlayHub')

@section('content')
<!-- Hero Section -->
<style>
@import url('https://fonts.googleapis.com/css2?family=Unbounded:wght@800&family=Space+Grotesk:wght@400;600&family=Manrope:wght@500&family=Orbitron:wght@900&display=swap');
.hero-wrap{position:relative;padding:56px 0;background:#0b0d12}
.hero-overlay{position:absolute;inset:0;pointer-events:none;z-index:0;background:
radial-gradient(800px 260px at 85% 20%, rgba(252,182,193,.35), transparent 62%),
radial-gradient(600px 220px at 15% 85%, rgba(168,85,247,.30), transparent 65%),
linear-gradient(135deg,#13151b 0%, #171922 40%, #1a1020 100%)}
.hero-grid{display:grid;grid-template-columns:1fr;gap:24px;align-items:center}
@media(min-width:1024px){.hero-grid{grid-template-columns:1.1fr .9fr}}
.title-anim{font-weight:800;line-height:1.1;margin:0 0 18px;background:linear-gradient(90deg,#ff9aa2,#fad0c4,#c471ed,#a18cd1);background-size:300% 300%;-webkit-background-clip:text;background-clip:text;color:transparent;animation:gradMove 8s linear infinite;font-size:42px}
@keyframes gradMove{0%{background-position:0% 50%}50%{background-position:100% 50%}100%{background-position:0% 50%}}
.btn-grad{display:inline-block;padding:12px 22px;border-radius:10px;background:linear-gradient(90deg,#ff7aa8,#f3b5a8,#9b72cf);color:#fff;text-decoration:none;font-weight:600;box-shadow:0 8px 24px rgba(0,0,0,.35)}
.btn-outline{display:inline-block;padding:12px 22px;border-radius:10px;border:1px solid rgba(255,255,255,.25);color:#fff;text-decoration:none;font-weight:600;background:rgba(255,255,255,.05)}
.media{position:relative;width:360px;height:240px;border-radius:16px;overflow:hidden;box-shadow:0 10px 30px rgba(0,0,0,.45)}
.media-small{position:absolute;right:-28px;bottom:-18px;width:220px;height:140px;border-radius:12px;overflow:hidden;box-shadow:0 8px 24px rgba(0,0,0,.35);border:1px solid rgba(255,255,255,.15)}
.blob{position:absolute;width:360px;height:260px;left:-40px;top:-30px;border-radius:50%;background:radial-gradient(closest-side, rgba(250,200,210,.45), rgba(195,113,237,.35), rgba(161,140,209,.25) 70%, transparent 72%);filter:blur(26px);opacity:.8;animation:blobMove 10s ease-in-out infinite alternate}
@keyframes blobMove{0%{transform:translate(0,0)}100%{transform:translate(20px,10px)}}
.blob2{position:absolute;width:300px;height:220px;right:-30px;top:10px;border-radius:55% 45% 60% 40%/40% 60% 45% 55%;background:radial-gradient(closest-side, rgba(253,206,223,.35), rgba(168,85,247,.30), rgba(161,140,209,.22) 70%, transparent 72%);filter:blur(24px);opacity:.7;animation:blobMove2 12s ease-in-out infinite alternate}
@keyframes blobMove2{0%{transform:translate(0,0)}100%{transform:translate(-18px,12px)}}
.gradient-move{position:absolute;inset:0;pointer-events:none;z-index:0;background:linear-gradient(90deg,rgba(255,154,162,.07),rgba(250,208,196,.05),rgba(155,114,207,.07));animation:drift 12s linear infinite}
@keyframes drift{0%{transform:translateX(-10px)}50%{transform:translateX(10px)}100%{transform:translateX(-10px)}}
.hero-container{max-width:1120px;margin:0 auto;padding:0 16px}
.hero-text{color:#d6d6dc;font-size:18px;margin-bottom:22px}
.headline{font-family:'Unbounded','Orbitron',system-ui; font-weight:800; font-size:48px; letter-spacing:.5px; -webkit-text-stroke:2px rgba(255,255,255,.85); text-stroke:2px rgba(255,255,255,.85); text-shadow:0 0 22px rgba(255,120,160,.35); color:#ffedf3}
.tagline{font-family:'Space Grotesk','Manrope',system-ui; font-weight:600; font-size:12px; letter-spacing:2px; color:#ff9aa2; margin-bottom:8px}
.subline{font-family:'Space Grotesk','Manrope',system-ui; font-weight:500; font-size:18px; color:#eaeaf1}
.price-big{font-size:120%; font-weight:700}
.no-wrap{white-space:nowrap}
.ps5-mini-left{position:absolute;left:-280px;top:10px;width:260px;height:420px;border-radius:16px;overflow:hidden;border:none;box-shadow:none;z-index:1;pointer-events:none;mix-blend-mode:screen;opacity:.75;filter:brightness(.92) saturate(.92);-webkit-mask-image:linear-gradient(to right, transparent 0, black 36px, black calc(100% - 36px), transparent 100%);mask-image:linear-gradient(to right, transparent 0, black 36px, black calc(100% - 36px), transparent 100%)}
@media(max-width:768px){.ps5-mini-left{display:none}}
/* Animated overlays (no build) */
.beams{position:absolute;inset:-10px 0 0 0;pointer-events:none;z-index:0;background:linear-gradient(115deg, rgba(255,154,162,.14), rgba(250,208,196,.1) 35%, rgba(155,114,207,.14));filter:blur(14px);animation:beamsMove 6s ease-in-out infinite alternate;mix-blend-mode:screen}
@keyframes beamsMove{0%{transform:translateX(-25px)}100%{transform:translateX(25px)}}
</style>
<section class="hero-wrap">
    <div class="hero-overlay"></div>
    <div class="gradient-move"></div>
    <div class="beams"></div>
    <div class="hero-container">
        <div class="hero-grid">
            <div style="position:relative;z-index:2;color:#fff">
                @php
                    $ps5Left = null;
                    $folders = [public_path('images/ps5'), public_path('images/ps'), public_path('images')];
                    foreach ($folders as $f) {
                        if (is_dir($f)) {
                            $c = glob($f.'/ps5*.{jpg,jpeg,png,webp}', GLOB_BRACE);
                            if (!empty($c)) {
                                $rel = str_replace(public_path().'\\', '', str_replace(public_path().'/', '', $c[0]));
                                $ps5Left = asset($rel); break;
                            }
                        }
                    }
                    if (!$ps5Left) { $ps5Left = 'https://images.unsplash.com/photo-1606813907291-76c3f0e6b453?q=80&w=1600&auto=format&fit=crop'; }
                @endphp
                <div class="ps5-mini-left"><img src="{{ $ps5Left }}" alt="ps5" style="width:100%;height:100%;object-fit:cover"/></div>
                <div class="tagline">OPEN 24 JAM</div>
                <h1 class="headline">PLAYHUB – SEWA PS MURAH</h1>
                <p class="subline">Rental PS5 & PS4 • Stick DualSense Edge • Aksesoris Original Ready <span class="no-wrap"> <span class="price-big"></span></span></p>
                <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:32px;margin-bottom:20px">
                    <a href="{{ route('products.playstation') }}" class="btn-grad">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" style="margin-right:8px;vertical-align:middle">
                            <rect x="5" y="9" width="14" height="8" rx="4" stroke-width="2"/>
                            <circle cx="9" cy="13" r="1" fill="#fff"/>
                            <circle cx="15" cy="13" r="1" fill="#fff"/>
                        </svg>
                        Rental sekarang
                    </a>
                    <a href="{{ route('products.accessories') }}" class="btn-outline">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" style="margin-right:8px;vertical-align:middle"><circle cx="9" cy="20" r="2"/><circle cx="17" cy="20" r="2"/><path d="M3 3h2l3 12h10l3-8H8" stroke="#fff" stroke-width="2"/></svg>
                        Belanja sekarang
                    </a>
                </div>
            </div>
            <div style="position:relative;z-index:1">
                <div class="blob"></div>
                <div class="blob2"></div>
                @php
                    $ps5Img = null;
                    $ps5Candidates = glob(public_path('images/ps5*.{jpg,jpeg,png,webp}'), GLOB_BRACE);
                    if (!empty($ps5Candidates)) {
                        $rel = str_replace(public_path().'\\', '', str_replace(public_path().'/', '', $ps5Candidates[0]));
                        $ps5Img = asset($rel);
                    } else {
                        $ps5Img = 'https://images.unsplash.com/photo-1606813907291-76c3f0e6b453?q=80&w=1600&auto=format&fit=crop';
                    }
                @endphp
                @php
                    $defaults = [
                        'https://images.unsplash.com/photo-1600962815726-457c3cf3adb5?q=80&w=1600&auto=format&fit=crop',
                        'https://images.unsplash.com/photo-1606813907291-76c3f0e6b453?q=80&w=1600&auto=format&fit=crop',
                        'https://images.unsplash.com/photo-1542751110-97427bbecf20?q=80&w=1600&auto=format&fit=crop',
                    ];
                    $locals = [];
                    $dirs = [public_path('images/carousel'), public_path('images/caraousel')];
                    $found = [];
                    foreach ($dirs as $d) {
                        if (is_dir($d)) {
                            $found = glob($d.'/*.{jpg,jpeg,png,webp}', GLOB_BRACE);
                            if (!empty($found)) break;
                        }
                    }
                    if (!empty($found)) {
                        foreach ($found as $fp) {
                            $rel = str_replace(public_path().'\\', '', str_replace(public_path().'/', '', $fp));
                            $locals[] = asset($rel);
                        }
                    } else {
                        for ($i=1; $i<=3; $i++) {
                            $path = public_path("images/hero-$i.jpg");
                            $locals[] = file_exists($path) ? asset("images/hero-$i.jpg") : $defaults[$i-1];
                        }
                    }
                @endphp
                <div class="media" id="heroMainWrap" style="width:480px;height:300px"><img id="heroMain" src="{{ $locals[0] ?? $defaults[0] }}" alt="main" style="width:100%;height:100%;object-fit:cover" /><div style="position:absolute;inset:0;background:linear-gradient(135deg,rgba(0,0,0,.25),rgba(0,0,0,.1),transparent)"></div></div>
                <div class="media-small"><img id="heroSecondary" src="{{ $locals[1] ?? $defaults[1] }}" alt="secondary" style="width:100%;height:100%;object-fit:cover" /></div>
                <script>
                (function(){
                    var imgs = @json($locals);
                    if(!Array.isArray(imgs) || !imgs.length){ imgs = @json($defaults); }
                    var i=0, main=document.getElementById('heroMain'), sec=document.getElementById('heroSecondary'), wrap=document.getElementById('heroMainWrap');
                    function fitSize(src){
                        var t=new Image(); t.onload=function(){
                            var ratio=t.naturalWidth/t.naturalHeight;
                            if(ratio<1){ // portrait
                                wrap.style.width='380px'; wrap.style.height='480px';
                            } else { // landscape
                                wrap.style.width='560px'; wrap.style.height='320px';
                            }
                        }; t.src=src;
                    }
                    fitSize(main.src);
                    setInterval(function(){ i=(i+1)%imgs.length; var next=imgs[i]; main.src=next; if(sec){ sec.src=imgs[(i+1)%imgs.length]; } fitSize(next); }, 3500);
                })();
                </script>
            </div>
        </div>
    </div>
                
</section>

<!-- PlayStation Types Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Pilih PlayStation Favoritmu
            </h2>
            <p class="text-xl text-gray-600">
                Tersedia berbagai jenis PlayStation dengan harga terjangkau
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($playstationTypes as $type)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden card-hover">
                    <div class="h-48 bg-gray-200 flex items-center justify-center">
                        @if($type->image)
                            <img src="{{ asset('storage/' . $type->image) }}" alt="{{ $type->name }}" class="h-full w-full object-cover">
                        @else
                            <i class="fas fa-gamepad text-6xl text-gray-400"></i>
                        @endif
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $type->name }}</h3>
                        <p class="text-gray-600 mb-4">{{ Str::limit($type->description, 100) }}</p>
                        <div class="space-y-2 mb-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Per Jam:</span>
                                <span class="font-semibold text-purple-600">Rp {{ number_format($type->rental_price_per_hour) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Per Hari:</span>
                                <span class="font-semibold text-purple-600">Rp {{ number_format($type->rental_price_per_day) }}</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">
                                Tersedia: <span class="font-semibold text-green-600">{{ $type->available_units }} unit</span>
                            </span>
                            <a href="{{ route('products.playstation') }}" class="bg-purple-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-purple-700 transition duration-300">
                                Rental
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="text-center mt-12">
            <a href="{{ route('products.playstation') }}" class="btn btn-primary px-8">
                Lihat Semua PlayStation
            </a>
        </div>
    </div>
</section>

<!-- Accessories Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Aksesoris Gaming
            </h2>
            <p class="text-xl text-gray-600">
                Lengkapi pengalaman gamingmu dengan aksesoris berkualitas
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($accessories as $accessory)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden card-hover">
                    <div class="h-48 bg-gray-200 flex items-center justify-center">
                        @if($accessory->image)
                            <img src="{{ asset('storage/' . $accessory->image) }}" alt="{{ $accessory->name }}" class="h-full w-full object-cover">
                        @else
                            <i class="fas fa-headphones text-6xl text-gray-400"></i>
                        @endif
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $accessory->name }}</h3>
                        <p class="text-sm text-gray-600 mb-2">{{ $accessory->brand }}</p>
                        <p class="text-gray-600 mb-4">{{ Str::limit($accessory->description, 80) }}</p>
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-2xl font-bold text-emerald-600">Rp {{ number_format($accessory->price) }}</span>
                            <span class="text-sm text-gray-500">Stok: {{ $accessory->stock }}</span>
                        </div>
                        <form action="{{ route('cart.add') }}" method="POST" class="flex gap-2">
                            @csrf
                            <input type="hidden" name="accessory_id" value="{{ $accessory->id }}">
                            <input type="number" name="quantity" value="1" min="1" max="{{ $accessory->stock }}" 
                                   class="w-16 px-2 py-1 border border-gray-300 rounded-md text-sm">
                            <button type="submit" class="flex-1 btn btn-primary">
                                <i class="fas fa-cart-plus mr-1"></i>Keranjang
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="text-center mt-12">
            <a href="{{ route('products.accessories') }}" class="btn btn-primary px-8">
                Lihat Semua Aksesoris
            </a>
        </div>
    </div>
</section>

<!-- Articles Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">Artikel</h2>
            <p class="text-gray-600">Tips dan info seputar PlayStation & gaming</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($articles ?? [] as $a)
                <article class="bg-white rounded-lg shadow overflow-hidden">
                    @if(!empty($a->image))
                        <img src="{{ str_starts_with($a->image, 'http') ? $a->image : asset('storage/'.$a->image) }}" alt="{{ $a->title }}" class="h-40 w-full object-cover">
                    @endif
                    <div class="p-5">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $a->title }}</h3>
                        <p class="text-sm text-gray-600 mb-3">{{ $a->excerpt ?? Str::limit(strip_tags($a->body), 120) }}</p>
                        <div class="text-xs text-gray-500 mb-4">{{ $a->author_name ?? 'PlayHub' }} • {{ optional($a->published_at)->format('d M Y') }}</div>
                        <a href="{{ route('articles.show', $a->slug) }}" class="text-purple-600 font-medium">Baca selengkapnya</a>
                    </div>
                </article>
            @empty
                <div class="col-span-3 text-center text-gray-500">Belum ada artikel</div>
            @endforelse
        </div>
    </div>
 </section>

<!-- Features Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Kenapa Memilih PlayHub?
            </h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="mx-auto h-16 w-16 bg-emerald-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-dollar-sign text-2xl text-emerald-600"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Harga Terjangkau</h3>
                <p class="text-gray-600">Dapatkan harga rental dan aksesoris dengan harga yang kompetitif dan bersahabat.</p>
            </div>
            
            <div class="text-center">
                <div class="mx-auto h-16 w-16 bg-emerald-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-shipping-fast text-2xl text-emerald-600"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Pengiriman Cepat</h3>
                <p class="text-gray-600">Layanan antar-jemput PlayStation untuk memudahkan aktivitas gaming Anda.</p>
            </div>
            
            <div class="text-center">
                <div class="mx-auto h-16 w-16 bg-emerald-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-headset text-2xl text-emerald-600"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Support 24/7</h3>
                <p class="text-gray-600">Tim support kami siap membantu kapan pun Anda membutuhkan bantuan.</p>
            </div>
        </div>
    </div>
</section>
@endsection