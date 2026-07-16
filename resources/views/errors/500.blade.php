<x-layouts.app>
    <x-slot:title>Kesalahan Server</x-slot:title>
    <div class="flex flex-col items-center justify-center min-h-[60vh] text-center">
        <h1 class="text-6xl font-bold text-slate-200 mb-4">500</h1>
        <h2 class="text-xl font-semibold text-slate-900 mb-2">Kesalahan Server</h2>
        <p class="text-slate-500 mb-8">Terjadi kesalahan pada server. Silakan coba lagi nanti.</p>
        <a href="{{ route('dashboard') }}" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition shadow-sm">Kembali ke Dashboard</a>
    </div>
</x-layouts.app>
