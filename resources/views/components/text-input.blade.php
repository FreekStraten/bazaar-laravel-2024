@props(['disabled' => false])

<input
    {{ $disabled ? 'disabled' : '' }}
    {!! $attributes->merge([
        'class' =>
            'mt-1 block w-full rounded-lg
             border border-slate-300 bg-white
             text-slate-900 placeholder-slate-400
             focus:outline-none focus:ring-2 focus:ring-slate-300 focus:border-slate-400
             disabled:bg-slate-100 disabled:text-slate-500 disabled:cursor-not-allowed'
    ]) !!}
>
