@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Manajemen Sales</h1>
            <p class="mt-2 text-sm text-gray-600">Buat akun sales baru langsung dari dashboard admin.</p>
        </div>

        @if($message = Session::get('success'))
            <div class="p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
                {{ $message }}
            </div>
        @endif

        @if($message = Session::get('error'))
            <div class="p-4 bg-red-50 border border-red-200 rounded-lg text-red-700">
                {{ $message }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Tambah Sales Baru</h2>
            <form method="POST" action="{{ route('admin.sales.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor HP (Opsional)</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="08xxxxxxxxxx">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="md:col-span-2">
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                        Simpan Akun Sales
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <h2 class="text-xl font-semibold text-gray-900">Daftar Sales</h2>
                <div class="text-sm text-gray-600">
                    <p><span class="font-semibold text-gray-800">Jumlah sales:</span> {{ $salesUsers->total() }}</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Nama</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Email</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Nomor HP</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Dibuat</th>
                            <th class="px-4 py-2 text-right text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($salesUsers as $sales)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $sales->name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $sales->email }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $sales->phone ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm">
                                    @if($sales->is_active)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Aktif</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-200 text-gray-700">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $sales->created_at->format('d M Y H:i') }}</td>
                                <td class="px-4 py-3 text-right">
                                    <form method="POST" action="{{ route('admin.sales.reset-password', $sales) }}" onsubmit="return confirm('Reset password untuk akun ini? Password sementara akan dibuat otomatis.');" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-800 text-sm font-semibold rounded-lg hover:bg-blue-200 transition-colors mr-2">
                                            Reset Password
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.sales.toggle-status', $sales) }}" onsubmit="return confirm('Yakin ingin {{ $sales->is_active ? 'menonaktifkan' : 'mengaktifkan' }} akun sales ini?');" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 {{ $sales->is_active ? 'bg-amber-100 text-amber-800 hover:bg-amber-200' : 'bg-green-100 text-green-800 hover:bg-green-200' }} text-sm font-semibold rounded-lg transition-colors">
                                            {{ $sales->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500">Belum ada akun sales.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $salesUsers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
