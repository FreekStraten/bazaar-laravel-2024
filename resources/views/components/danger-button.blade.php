<button {{ $attributes->merge([
    'type' => 'button',
    'class' =>
        'inline-flex items-center justify-center gap-2
         rounded-lg bg-red-600 px-4 py-2 text-white text-sm font-medium
         hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300'
]) }}>
    {{ $slot }}
</button>
