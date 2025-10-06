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
            <div class="flex items-center gap-2">
                <input id="is_rental" type="checkbox" name="is_rental" value="1" class="rounded border-slate-300">
                <label for="is_rental" class="text-sm text-slate-700">{{ __('Is rental') }}</label>
            </div>
        </div>

        <div>
            <label class="block text-sm text-slate-700 mb-1">{{ __('Description') }}</label>
            <textarea name="description" rows="3" class="w-full rounded-md border-slate-300"></textarea>
            @error('description') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm text-slate-700 mb-1">{{ __('Image') }}</label>
            <input type="file" name="image" accept="image/*" class="w-full text-sm">
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
