@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Laporan Kunjungan</h1>
                    <p class="mt-2 text-sm text-gray-600">
                        @if(Auth::user()->role === 'sales')
                            Daftar laporan kunjungan Anda
                        @else
                            Daftar semua laporan kunjungan
                        @endif
                    </p>
                </div>
                @if(Auth::user()->role === 'sales')
                    <a href="{{ route('reports.create') }}" class="inline-flex w-full sm:w-auto justify-center items-center px-4 py-3 sm:py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Buat Laporan
                    </a>
                @endif
            </div>
        </div>

        <!-- Messages -->
        @if($message = Session::get('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <p class="text-green-700">{{ $message }}</p>
                </div>
            </div>
        @endif

        @if($message = Session::get('info'))
            <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-blue-700">{{ $message }}</p>
                </div>
            </div>
        @endif

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="tanggal_dari" class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                    <input type="date" id="tanggal_dari" name="tanggal_dari" value="{{ request('tanggal_dari') }}" class="w-full px-3 py-3 sm:py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="tanggal_sampai" class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                    <input type="date" id="tanggal_sampai" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" class="w-full px-3 py-3 sm:py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                @if(Auth::user()->role === 'admin')
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Sales</label>
                        <select id="user_id" name="user_id" class="w-full px-3 py-3 sm:py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua Sales</option>
                            @foreach(\App\Models\User::where('role', 'sales')->get() as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="flex items-end">
                    <button type="submit" class="w-full px-4 py-3 sm:py-2 bg-gray-100 text-gray-900 font-semibold rounded-lg hover:bg-gray-200 transition-colors">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Reports List -->
        <div class="space-y-4">
            @forelse($reports as $report)
                <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2 flex-wrap">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $report->user->name ?? 'Akun Sales Tidak Aktif' }}
                                </h3>
                                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                                    {{ $report->tanggal->format('d M Y') }}
                                </span>
                                <span class="inline-block px-3 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded-full">
                                    {{ $report->created_at->setTimezone('Asia/Jakarta')->format('H:i') }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                                {{ $report->details_count ?? $report->details->count() }} kunjungan
                            </p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                            <a href="{{ route('reports.show', $report) }}" class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-3 sm:py-2 bg-blue-100 text-blue-700 font-semibold rounded-lg hover:bg-blue-200 transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Lihat Detail
                            </a>
                            <form method="POST" action="{{ route('reports.destroy', $report) }}" onsubmit="return confirm('Yakin ingin menghapus laporan ini? Data kunjungan dan foto akan ikut terhapus.');" class="w-full sm:w-auto">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-3 sm:py-2 bg-red-100 text-red-700 font-semibold rounded-lg hover:bg-red-200 transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-7 0h8"></path>
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow-sm p-8 text-center">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-gray-500 text-lg">Belum ada laporan</p>
                    @if(Auth::user()->role === 'sales')
                        <a href="{{ route('reports.create') }}" class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                            Buat Laporan Pertama
                        </a>
                    @endif
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $reports->links() }}
        </div>
    </div>
</div>
@endsection
