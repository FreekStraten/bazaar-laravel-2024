<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        {{-- Naam --}}
        <div>
            <x-input-label for="name" :value="__('auth.Name')" />
            <x-text-input id="name" name="name" type="text"
                          class="block mt-1 w-full"
                          :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        {{-- E-mail --}}
        <div>
            <x-input-label for="email" :value="__('auth.Email')" />
            <x-text-input id="email" name="email" type="email"
                          class="block mt-1 w-full"
                          :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Gebruikerstype --}}
        <div>
            <x-input-label for="role_id" :value="__('auth.RegisterAs')" />
            <x-select-input id="role_id" name="role_id"
                            :options="[
                                '3' => __('user_types.private'),
                                '1' => __('user_types.business')
                            ]"
                            :selected="old('role_id')"
                            placeholder="{{ __('auth.RegisterAs') }}"
                            class="block mt-1 w-full" />
            <x-input-error :messages="$errors->get('role_id')" class="mt-2" />
        </div>

        {{-- Adres: straat --}}
        <div>
            <x-input-label for="street" :value="__('auth.Street')" />
            <x-text-input id="street" name="street" type="text"
                          class="block mt-1 w-full"
                          :value="old('street')" required />
            <x-input-error :messages="$errors->get('street')" class="mt-2" />
        </div>

        {{-- Huisnummer --}}
        <div>
            <x-input-label for="house_number" :value="__('auth.HouseNumber')" />
            <x-text-input id="house_number" name="house_number" type="text"
                          class="block mt-1 w-full"
                          :value="old('house_number')" required />
            <x-input-error :messages="$errors->get('house_number')" class="mt-2" />
        </div>

        {{-- Stad --}}
        <div>
            <x-input-label for="city" :value="__('auth.City')" />
            <x-text-input id="city" name="city" type="text"
                          class="block mt-1 w-full"
                          :value="old('city')" required />
            <x-input-error :messages="$errors->get('city')" class="mt-2" />
        </div>

        {{-- Postcode --}}
        <div>
            <x-input-label for="zip_code" :value="__('auth.ZipCode')" />
            <x-text-input id="zip_code" name="zip_code" type="text"
                          class="block mt-1 w-full"
                          :value="old('zip_code')" required />
            <x-input-error :messages="$errors->get('zip_code')" class="mt-2" />
        </div>

        {{-- Wachtwoord --}}
        <div>
            <x-input-label for="password" :value="__('auth.Password')" />
            <x-text-input id="password" name="password" type="password"
                          class="block mt-1 w-full"
                          required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Bevestig wachtwoord --}}
        <div>
            <x-input-label for="password_confirmation" :value="__('auth.ConfirmPassword')" />
            <x-text-input id="password_confirmation" name="password_confirmation" type="password"
                          class="block mt-1 w-full"
                          required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        {{-- Acties --}}
        <div class="flex items-center justify-between pt-2">
            <a href="{{ route('login') }}"
               class="underline text-sm text-slate-600 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-300">
                {{ __('auth.AlreadyRegistered') }}
            </a>

            <x-primary-button id="register-as-button">
                {{ __('auth.Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
