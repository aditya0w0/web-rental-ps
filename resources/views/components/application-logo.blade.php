@php
    $src = null;
    $exts = ['svg','png','jpg','jpeg','webp'];
    foreach ($exts as $ext) {
        $direct = public_path("images/logo.$ext");
        if (file_exists($direct)) { $src = asset('images/logo.'.$ext); break; }
    }
    if (!$src && is_dir(public_path('images/logo'))) {
        $matches = glob(public_path('images/logo').'/*.{svg,png,jpg,jpeg,webp}', GLOB_BRACE);
        if (!empty($matches)) {
            $rel = str_replace(public_path().'\\', '', str_replace(public_path().'/', '', $matches[0]));
            $src = asset($rel);
        }
    }
@endphp
@if($src)
    <img src="{{ $src }}" alt="PlayHub" {{ $attributes->merge(['class' => 'h-12 w-auto']) }} />
@else
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" {{ $attributes }}>
  <g fill="currentColor">
    <rect x="8" y="22" width="48" height="20" rx="10"/>
    <circle cx="22" cy="32" r="3"/>
    <circle cx="42" cy="32" r="3"/>
    <path d="M14 36l-4 6M50 36l4 6" stroke="currentColor" stroke-width="2" fill="none"/>
  </g>
</svg>
@endif
