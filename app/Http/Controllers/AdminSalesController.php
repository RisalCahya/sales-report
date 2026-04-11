<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminSalesController extends Controller
{
    /**
     * Display sales management page for admin.
     */
    public function index(): View
    {
        $this->ensureAdmin();

        $salesUsers = User::where('role', 'sales')
            ->latest()
            ->paginate(10);

        return view('admin.sales.index', compact('salesUsers'));
    }

    /**
     * Create a new sales account by admin.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->ensureAdmin();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30', 'regex:/^[0-9+\-\s()]+$/', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'sales',
            'is_active' => true,
            // Admin-created accounts are considered active immediately.
            'email_verified_at' => now(),
            'verification_preference' => 'email',
        ]);

        return redirect()->route('admin.sales.index')
            ->with('success', 'Akun sales berhasil dibuat.');
    }

    /**
     * Toggle sales account status (active/inactive) without deleting data.
     */
    public function toggleStatus(User $sales): RedirectResponse
    {
        $this->ensureAdmin();

        if ($sales->role !== 'sales') {
            return redirect()->route('admin.sales.index')
                ->with('error', 'Hanya akun sales yang dapat diubah statusnya dari halaman ini.');
        }

        $sales->is_active = ! $sales->is_active;
        $sales->save();

        return redirect()->route('admin.sales.index')
            ->with('success', $sales->is_active
                ? 'Akun sales berhasil diaktifkan kembali.'
                : 'Akun sales berhasil dinonaktifkan. Data laporan tetap aman.');
    }

    /**
     * Reset sales password by admin without deleting account/data.
     */
    public function resetPassword(User $sales): RedirectResponse
    {
        $this->ensureAdmin();

        if ($sales->role !== 'sales') {
            return redirect()->route('admin.sales.index')
                ->with('error', 'Hanya akun sales yang dapat direset passwordnya.');
        }

        $temporaryPassword = Str::password(10, true, true, false, false);
        $sales->password = Hash::make($temporaryPassword);
        $sales->save();

        return redirect()->route('admin.sales.index')
            ->with('success', 'Password sementara untuk ' . $sales->name . ' adalah: ' . $temporaryPassword);
    }

    /**
     * Ensure only admin can access this controller.
     */
    private function ensureAdmin(): void
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403);
        }
    }
}
