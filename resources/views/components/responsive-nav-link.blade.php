@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full rounded-lg px-3 py-2 text-start text-base font-semibold text-slate-950 bg-slate-100 transition duration-150 ease-in-out'
            : 'block w-full rounded-lg px-3 py-2 text-start text-base font-semibold text-slate-600 hover:bg-slate-100 hover:text-slate-950 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
