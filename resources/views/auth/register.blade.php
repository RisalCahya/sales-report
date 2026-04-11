<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Verification Preference -->
        <div class="mt-4">
            <x-input-label for="verification_preference" value="Metode Verifikasi" />
            <select id="verification_preference" name="verification_preference" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                <option value="email" {{ old('verification_preference', 'email') === 'email' ? 'selected' : '' }}>Email (otomatis)</option>
                <option value="phone" {{ old('verification_preference') === 'phone' ? 'selected' : '' }}>Nomor HP / WhatsApp (persiapan OTP)</option>
            </select>
            <x-input-error :messages="$errors->get('verification_preference')" class="mt-2" />
            <p class="mt-1 text-xs text-gray-500">
                Saat ini verifikasi aktif melalui email. Opsi nomor HP disiapkan agar data sales lengkap untuk integrasi OTP/SMS/WhatsApp berikutnya.
            </p>
        </div>

        <!-- Phone -->
        <div class="mt-4" id="phoneInputWrap">
            <x-input-label for="phone" value="Nomor HP" />
            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" autocomplete="tel" placeholder="Contoh: 081234567890" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const preferenceEl = document.getElementById('verification_preference');
            const phoneWrap = document.getElementById('phoneInputWrap');
            const phoneInput = document.getElementById('phone');

            function togglePhoneField() {
                const usePhone = preferenceEl.value === 'phone';
                phoneInput.required = usePhone;
                phoneWrap.classList.toggle('opacity-70', !usePhone);
            }

            preferenceEl.addEventListener('change', togglePhoneField);
            togglePhoneField();
        });
    </script>
</x-guest-layout>
