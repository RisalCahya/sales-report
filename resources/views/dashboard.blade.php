@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Welcome Header -->
        <div class="app-soft-card mb-8 rounded-[28px] px-6 py-7 sm:px-8">
            <span class="inline-flex items-center rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-sky-700">Sales Visit Hub</span>
            <h1 class="app-accent-title mt-4 text-4xl font-extrabold leading-tight">
                Selamat datang, {{ Auth::user()->name }}!
            </h1>
            <p class="mt-3 max-w-2xl text-slate-600">
                @if(Auth::user()->role === 'sales')
                    Kelola laporan kunjungan harian Anda di sini
                @else
                    Dashboard Admin - Monitor laporan kunjungan tim
                @endif
            </p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            @if(Auth::user()->role === 'sales')
                <!-- Laporan Hari Ini -->
                <div class="app-soft-card rounded-3xl p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Laporan Hari Ini</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">
                                {{ $stats['reportsTodayCount'] }}
                            </p>
                        </div>
                        <svg class="w-12 h-12 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                </div>

                <!-- Total Kunjungan Hari Ini -->
                <div class="app-soft-card rounded-3xl p-6 border-l-4 border-emerald-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Kunjungan Hari Ini</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">
                                {{ $stats['visitsTodayCount'] }}
                            </p>
                        </div>
                        <svg class="w-12 h-12 text-green-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Total Laporan -->
                <div class="app-soft-card rounded-3xl p-6 border-l-4 border-fuchsia-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Total Laporan</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">
                                {{ $stats['totalReportsCount'] }}
                            </p>
                        </div>
                        <svg class="w-12 h-12 text-purple-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            @else
                <!-- Total Sales -->
                <div class="app-soft-card rounded-3xl p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Total Sales</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">
                                {{ $stats['totalSalesCount'] }}
                            </p>
                        </div>
                        <svg class="w-12 h-12 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-2a6 6 0 0112 0v2zm0 0h6v-2a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Laporan Hari Ini -->
                <div class="app-soft-card rounded-3xl p-6 border-l-4 border-emerald-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Laporan Hari Ini</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">
                                {{ $stats['reportsTodayCount'] }}
                            </p>
                        </div>
                        <svg class="w-12 h-12 text-green-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                </div>

                <!-- Total Laporan -->
                <div class="app-soft-card rounded-3xl p-6 border-l-4 border-fuchsia-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Total Laporan</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">
                                {{ $stats['totalReportsCount'] }}
                            </p>
                        </div>
                        <svg class="w-12 h-12 text-purple-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="mb-8">
            @if(Auth::user()->role === 'sales')
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                    <a href="{{ route('reports.create') }}" class="app-primary-gradient inline-flex w-full sm:w-auto justify-center items-center px-6 py-3 text-white font-semibold text-lg rounded-2xl transition-colors shadow-sm shadow-sky-200/70">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Buat Laporan Baru
                    </a>
                    <a href="{{ route('reports.index') }}" class="inline-flex w-full sm:w-auto justify-center items-center px-6 py-3 bg-white/80 text-slate-800 font-semibold text-lg rounded-2xl border border-sky-100 hover:bg-white transition-colors shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Lihat Semua Laporan
                    </a>
                </div>
            @else
                <a href="{{ route('reports.index') }}" class="inline-flex items-center px-6 py-3 bg-white/80 text-slate-800 font-semibold text-lg rounded-2xl border border-sky-100 hover:bg-white transition-colors shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Lihat Semua Laporan
                </a>
            @endif
        </div>

        <!-- Recent Reports -->
        <div class="app-soft-card rounded-[28px] p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Laporan Terbaru</h2>
            @if($recentReports->count() > 0)
                <div class="space-y-3">
                    @foreach($recentReports as $report)
                        <div class="flex items-center justify-between gap-3 p-4 border border-sky-100 rounded-2xl bg-white/75 hover:bg-white transition-colors">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $report->user->name ?? 'Akun Sales Tidak Aktif' }}</p>
                                <p class="text-sm text-gray-600">{{ $report->tanggal->format('d M Y') }} • {{ $report->created_at->setTimezone('Asia/Jakarta')->format('H:i') }} • {{ $report->details_count }} kunjungan</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('reports.show', $report) }}" class="inline-flex items-center px-3 py-2 bg-blue-100 text-blue-700 text-sm font-semibold rounded-lg hover:bg-blue-200 transition-colors">
                                    Lihat
                                </a>
                                <form method="POST" action="{{ route('reports.destroy', $report) }}" onsubmit="return confirm('Yakin ingin menghapus laporan ini? Data kunjungan dan foto akan ikut terhapus.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-100 text-red-700 text-sm font-semibold rounded-lg hover:bg-red-200 transition-colors">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-8">Tidak ada laporan terbaru</p>
            @endif
        </div>
    </div>
</div>
@endsection
