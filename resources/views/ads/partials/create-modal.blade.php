{{-- Shared modal component, open via: dispatchEvent('open-modal', { detail: 'create-ad' }) --}}
<x-modal id="create-ad" :title="__('Create Ad')" maxWidth="lg">
    <form method="POST" action="{{ route('ads.store') }}" class="space-y-4" enctype="multipart/form-data">
        @csrf

        <div>
            <label class="block text-sm text-slate-700 mb-1">{{ __('Title') }}</label>
            <input type="text" name="title" class="w-full rounded-md border-slate-300" required>
            @error('title') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-slate-700 mb-1">{{ __('Price') }}</label>
                <input type="number" step="0.01" name="price" class="w-full rounded-md border-slate-300" required>
                @error('price') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div x-data="{ rental: @js(old('is_rental') ? true : false) }" class="h-full flex flex-col items-end justify-center pr-3">
                <label for="is_rental" class="block text-sm text-slate-700 mb-1 text-right" x-text="rental ? '{{ __('ads.rental') }}' : '{{ __('ads.sale') }}'"></label>
                <label for="is_rental" class="inline-flex items-center cursor-pointer select-none">
                    {{-- Altijd een waarde posten: 0 standaard + 1 wanneer aangevinkt --}}
                    <input type="hidden" name="is_rental" value="0">
                    <input id="is_rental" type="checkbox" name="is_rental" value="1"
                           class="sr-only peer" x-model="rental" @checked(old('is_rental'))>
                    <div class="relative w-10 h-6 rounded-full bg-slate-200 transition-colors peer-checked:bg-emerald-600 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-emerald-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition-all peer-checked:after:translate-x-4"></div>
                    <span class="sr-only" x-text="rental ? '{{ __('ads.rental') }}' : '{{ __('ads.sale') }}'"></span>
                </label>
            </div>
        </div>

        {{-- Address fields (vereist door controller-validatie) --}}
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-slate-700 mb-1">{{ __('auth.Street') }}</label>
                <input type="text" name="street" class="w-full rounded-md border-slate-300" value="{{ old('street') }}" required>
                @error('street') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm text-slate-700 mb-1">{{ __('auth.HouseNumber') }}</label>
                <input type="text" name="house_number" class="w-full rounded-md border-slate-300" value="{{ old('house_number') }}" required>
                @error('house_number') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm text-slate-700 mb-1">{{ __('auth.City') }}</label>
                <input type="text" name="city" class="w-full rounded-md border-slate-300" value="{{ old('city') }}" required>
                @error('city') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm text-slate-700 mb-1">{{ __('auth.ZipCode') }}</label>
                <input type="text" name="zip_code" class="w-full rounded-md border-slate-300" value="{{ old('zip_code') }}" required>
                @error('zip_code') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm text-slate-700 mb-1">{{ __('Description') }}</label>
            <textarea name="description" rows="3" class="w-full rounded-md border-slate-300"></textarea>
            @error('description') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm text-slate-700 mb-1">{{ __('Image') }}</label>
            <input type="file" name="image" accept="image/*"
                   class="block w-full text-sm text-slate-800 file:mr-3 file:rounded-md file:border file:border-slate-300 file:bg-white file:px-3 file:py-1.5 file:text-sm file:font-medium hover:file:bg-slate-50">
            @error('image') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="pt-2 flex justify-end gap-2">
            <button type="button"
                    class="rounded-md border border-slate-300 px-3 py-2 text-sm hover:bg-slate-100"
                    @click="window.dispatchEvent(new CustomEvent('close-modal', { detail: 'create-ad' }))">
                {{ __('Cancel') }}
            </button>
            <button type="submit"
                    class="rounded-md bg-emerald-600 px-3 py-2 text-sm text-white hover:bg-emerald-700">
                {{ __('Save') }}
            </button>
        </div>
    </form>
</x-modal>
