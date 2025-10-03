<button {{ $attributes->merge([
    'type' => 'button',
    'class' =>
        'inline-flex items-center justify-center gap-2
         rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm text-slate-800
         hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300'
]) }}>
    {{ $slot }}
</button>
