<button {{ $attributes->merge([
    'type' => 'submit',
    'class' =>
        'inline-flex items-center justify-center gap-2
         rounded-lg bg-slate-900 px-4 py-2 text-white text-sm font-medium
         hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-300'
]) }}>
    {{ $slot }}
</button>
