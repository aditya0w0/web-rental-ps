@extends('layouts.app')

@section('title', 'System Flowchart — PNG Export')

@section('content')
<div class="container mx-auto px-4 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-8">
        <aside class="hidden lg:block bg-white/10 rounded-lg p-4 border border-white/10 h-fit">
            <div class="text-sm text-white/80">Flowchart gabungan User + Admin</div>
        </aside>
        <div>
            <h1 class="text-3xl font-bold mb-4">Flowchart Sistem</h1>
            <p class="text-gray-600 mb-6">Per langkah ditampilkan sebagai tabel, dihubungkan dengan panah. Gunakan tombol di bawah untuk mengunduh versi PNG.</p>

            <div class="flex flex-wrap items-center gap-3 mb-6">
                <a href="{{ asset('flowcharts/system-flowchart.svg') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded">Lihat SVG</a>
                <button id="btn-generate" class="px-4 py-2 bg-purple-600 text-white rounded">Download PNG</button>
            </div>

            <div class="bg-white shadow rounded-lg p-4 mb-6">
                <img id="svg-preview" src="{{ asset('flowcharts/system-flowchart.svg') }}" alt="System Flowchart" class="w-full h-auto"/>
            </div>

            <div id="png-result" class="bg-white shadow rounded-lg p-4 hidden">
                <h2 class="text-lg font-semibold mb-3">Preview PNG</h2>
                <img id="png-img" alt="PNG Preview" class="w-full h-auto"/>
            </div>

            <canvas id="canvas" class="hidden"></canvas>
        </div>
    </div>
</div>

<script>
document.getElementById('btn-generate').addEventListener('click', async function () {
    const svgUrl = "{{ asset('flowcharts/system-flowchart.svg') }}";
    const res = await fetch(svgUrl);
    const svgText = await res.text();
    const blob = new Blob([svgText], { type: 'image/svg+xml' });
    const url = URL.createObjectURL(blob);
    const img = new Image();
    img.onload = function () {
        const w = img.naturalWidth || 1700;
        const h = img.naturalHeight || 1800;
        const canvas = document.getElementById('canvas');
        canvas.width = w;
        canvas.height = h;
        const ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, w, h);
        ctx.drawImage(img, 0, 0);
        URL.revokeObjectURL(url);
        canvas.toBlob(function (pngBlob) {
            const pngUrl = URL.createObjectURL(pngBlob);
            const a = document.createElement('a');
            a.href = pngUrl;
            a.download = 'system-flowchart.png';
            document.body.appendChild(a);
            a.click();
            a.remove();
            const pngImg = document.getElementById('png-img');
            pngImg.src = pngUrl;
            document.getElementById('png-result').classList.remove('hidden');
        }, 'image/png');
    };
    img.src = url;
});
</script>
@endsection