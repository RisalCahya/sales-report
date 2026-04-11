@extends('layouts.app')

@section('content')
<div class="py-10">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 p-6 text-white shadow-lg">
            <div class="absolute right-0 top-0 h-32 w-32 translate-x-8 -translate-y-8 rounded-full bg-cyan-400/20 blur-2xl"></div>
            <div class="relative">
                <p class="text-xs uppercase tracking-[0.2em] text-cyan-200">Account Center</p>
                <h1 class="mt-2 text-2xl font-bold">Profil Akun</h1>
                <p class="mt-1 text-sm text-slate-200">Kelola keamanan dan informasi akun Anda.</p>
                <div class="mt-4 text-sm text-slate-100">
                    <p><span class="font-semibold">Nama:</span> {{ auth()->user()->name }}</p>
                    <p class="mt-1"><span class="font-semibold">Email:</span> {{ auth()->user()->email }}</p>
                </div>
                <span class="mt-3 inline-flex rounded-full bg-white/15 px-3 py-1 text-xs font-semibold text-cyan-100">
                    Role: {{ strtoupper(auth()->user()->role) }}
                </span>
            </div>
        </div>

        @if(auth()->user()->role === 'sales')
            <div class="rounded-2xl border border-blue-200 bg-blue-50 p-5 shadow-sm">
                <div class="flex items-start gap-3">
                    <svg class="mt-0.5 h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm text-blue-900">
                        Akun Anda dibuat oleh admin. Disarankan langsung mengganti password awal agar akun lebih aman.
                    </p>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-5 sm:p-8 shadow-sm">
                <div class="max-w-2xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        @else
            <div class="rounded-2xl border border-gray-200 bg-white p-5 sm:p-8 shadow-sm">
                <div class="max-w-2xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-5 sm:p-8 shadow-sm">
                <div class="max-w-2xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-5 sm:p-8 shadow-sm">
                <div class="max-w-2xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
