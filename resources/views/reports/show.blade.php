@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Detail Laporan</h1>
                    <div class="mt-3 space-y-1">
                        <p class="text-sm text-gray-600">
                            <strong>Sales:</strong> {{ $report->user->name ?? 'Akun Sales Tidak Aktif' }}
                        </p>
                        <p class="text-sm text-gray-600">
                            <strong>Tanggal:</strong> {{ $report->tanggal->format('l, d F Y') }} • <strong>Jam:</strong> {{ $report->created_at->setTimezone('Asia/Jakarta')->format('H:i') }}
                        </p>
                        <p class="text-sm text-gray-600">
                            <strong>Jumlah Kunjungan:</strong> {{ $report->details_count }} outlet
                        </p>
                    </div>
                </div>
                <a href="{{ route('reports.index') }}" class="inline-flex w-full sm:w-auto justify-center items-center px-4 py-3 sm:py-2 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Kunjungan List -->
        @if($details->count() > 0)
            <div class="space-y-6">
                @foreach($details as $detail)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                        <!-- Card Header -->
                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 border-b border-blue-200 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">{{ $detail->outlet }}</h3>
                                    <p class="mt-1 text-sm text-gray-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ $detail->alamat }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-blue-600">{{ $details->firstItem() + $loop->index }}/{{ $report->details_count }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $detail->created_at->setTimezone('Asia/Jakarta')->format('H:i') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-6">
                            <!-- PIC -->
                            <div class="mb-6 pb-6 border-b border-gray-200">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Person In Charge (PIC)
                                </label>
                                <p class="text-gray-900 text-lg">{{ $detail->pic }}</p>
                            </div>

                            <!-- Keterangan -->
                            @if($detail->keterangan)
                                <div class="mb-6 pb-6 border-b border-gray-200">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                        </svg>
                                        Keterangan
                                    </label>
                                    <p class="text-gray-700 whitespace-pre-wrap">{{ $detail->keterangan }}</p>
                                </div>
                            @endif

                            <!-- GPS Info -->
                            @if($detail->latitude && $detail->longitude)
                                <div class="mb-6 pb-6 border-b border-gray-200 p-4 bg-blue-50 rounded-lg">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Koordinat GPS
                                    </label>
                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <p class="text-gray-600">Latitude</p>
                                            <p class="font-mono text-gray-900">{{ $detail->latitude }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-600">Longitude</p>
                                            <p class="font-mono text-gray-900">{{ $detail->longitude }}</p>
                                        </div>
                                    </div>
                                    <a href="https://maps.google.com/?q={{ $detail->latitude }},{{ $detail->longitude }}" target="_blank" class="mt-3 inline-flex w-full sm:w-auto justify-center items-center px-3 py-3 sm:py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                        Buka di Google Maps
                                    </a>
                                </div>
                            @endif

                            <!-- Foto -->
                            @if($detail->foto_path)
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Foto Bukti
                                    </label>
                                    <div class="relative">
                                        <img src="{{ url('storage/' . ltrim($detail->foto_path, '/')) }}" alt="Foto {{ $detail->outlet }}" loading="lazy" decoding="async" class="w-full rounded-lg shadow-sm border border-gray-200 max-h-96 object-contain bg-gray-100">
                                        <div class="mt-2 text-xs text-gray-500">
                                            Diambil pada: {{ $detail->captured_at_label ?? $detail->created_at->setTimezone('Asia/Jakarta')->format('d/m/Y, H:i:s') }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $details->links() }}
            </div>
        @else
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <p class="text-gray-500 text-lg">Belum ada kunjungan yang tercatat</p>
            </div>
        @endif

        <!-- Footer -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <p class="text-xs text-gray-500 text-center">
                Laporan dibuat: {{ $report->created_at->setTimezone('Asia/Jakarta')->format('d M Y H:i:s') }}
            </p>
        </div>
    </div>
</div>
@endsection
