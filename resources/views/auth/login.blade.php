<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Heading -->
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-slate-900">{{ __('auth.Login') }}</h1>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('auth.Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required
                          autofocus autocomplete="username" class="block mt-1 w-full" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('auth.Password')" />
            <x-text-input id="password" type="password" name="password" required
                          autocomplete="current-password" class="block mt-1 w-full" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember -->
        <div class="block">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" name="remember"
                       class="rounded border-slate-300 text-slate-900 shadow-sm focus:ring-slate-300">
                <span class="ms-2 text-sm text-slate-600">{{ __('auth.RememberMe') }}</span>
            </label>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between pt-2">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                   class="underline text-sm text-slate-600 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-300">
                    {{ __('auth.ForgotPassword') }}
                </a>
            @endif

            <x-primary-button>
                {{ __('auth.Login') }}
            </x-primary-button>
        </div>
    </form>

    <!-- Register CTA -->
    <div class="mt-6 text-center text-sm text-slate-600">
        {{ __('No account yet?') }}
        <a href="{{ route('register') }}" class="font-medium text-slate-900 hover:underline">
            {{ __('auth.Register') }}
        </a>
    </div>
</x-guest-layout>
