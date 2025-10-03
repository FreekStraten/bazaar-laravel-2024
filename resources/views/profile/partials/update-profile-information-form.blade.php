<section>
    <header>
        <h2 class="text-lg font-medium text-slate-900">
            {{ __('auth.ProfileInformation') }}
        </h2>

        <p class="mt-1 text-sm text-slate-600">
            {{ __("auth.UpdateProfile") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('auth.name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                          :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('auth.email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                          :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div>
                <p class="text-sm mt-2 text-slate-600">
                    {{ __('auth.email_unverified') }}
                    <button form="send-verification"
                            class="underline text-sm text-slate-900 hover:text-slate-700 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                        {{ __('auth.resend_verification_email') }}
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 font-medium text-sm text-green-700">
                        {{ __('auth.verification_link_sent') }}
                    </p>
                @endif
            </div>
        @endif

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('auth.Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p class="text-sm text-green-700">{{ __('auth.Saved') }}</p>
            @endif
        </div>
    </form>
</section>
