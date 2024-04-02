<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('auth.Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('auth.Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- User Type -->
        <div class="mt-4">
            <x-input-label for="user_type" :value="__('auth.RegisterAs')"/>
            <x-select-input name="user_type" id="user_type" class="form-control" :options="['private' => __('user_types.private'), 'business' => __('user_types.business')]" />
        </div>

        <!-- Address Fields -->
        <div class="mt-4">
            <x-input-label for="street" :value="__('auth.Street')" />
            <x-text-input id="street" class="block mt-1 w-full" type="text" name="street" :value="old('street')" required autofocus />
            <x-input-error :messages="$errors->get('street')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="house_number" :value="__('auth.HouseNumber')" />
            <x-text-input id="house_number" class="block mt-1 w-full" type="text" name="house_number" :value="old('house_number')" required autofocus />
            <x-input-error :messages="$errors->get('house_number')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="city" :value="__('auth.City')" />
            <x-text-input id="city" class="block mt-1 w-full" type="text" name="city" :value="old('city')" required autofocus />
            <x-input-error :messages="$errors->get('city')" class="mt-2" />
        </div>

        <p>Register</p>

        <div class="mt-4">
            <x-input-label for="zip_code" :value="__('auth.ZipCode')" />
            <x-text-input id="zip_code" class="block mt-1 w-full" type="text" name="zip_code" :value="old('zip_code')" required autofocus />
            <x-input-error :messages="$errors->get('zip_code')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('auth.Password')" />

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
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('auth.AlreadyRegistered') }}
            </a>

            <x-primary-button class="ms-4" id="register-as-button">
                {{ __('auth.RegisterAs') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
