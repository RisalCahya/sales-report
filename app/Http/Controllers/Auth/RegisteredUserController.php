<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'verification_preference' => ['required', 'in:email,phone'],
            'phone' => ['nullable', 'string', 'max:30', 'regex:/^[0-9+\-\s()]+$/', 'unique:users,phone'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if ($request->verification_preference === 'phone' && empty($request->phone)) {
            throw ValidationException::withMessages([
                'phone' => 'Nomor HP wajib diisi jika memilih opsi verifikasi nomor.',
            ]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'sales',
            'verification_preference' => $request->verification_preference,
        ]);

        event(new Registered($user));

        Auth::login($user);

        if ($request->verification_preference === 'phone') {
            return redirect(route('verification.notice', absolute: false))
                ->with('status', 'Verifikasi nomor (OTP) belum aktif. Sementara gunakan verifikasi email yang sudah dikirimkan ke inbox Anda.');
        }

        return redirect(route('verification.notice', absolute: false));
    }
}
