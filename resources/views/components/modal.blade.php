@props([
    'id' => null,            // verplicht als je via events wilt openen/sluiten
    'show' => false,         // initiale open state (meestal false)
    'title' => null,         // optionele titel
    'maxWidth' => 'md',      // sm|md|lg|xl
    'z' => 'z-[90]',         // overlay z-index (navbar is z-[60], dus dit moet hoger zijn)
    'closeOnBackdrop' => true, // klik op achtergrond sluit modal
])

@php
    $maxMap = [
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
    ];
    $maxClass = $maxMap[$maxWidth] ?? $maxMap['md'];
@endphp

<div
    x-data="{ open: @js($show) }"
    {{-- open/close via window events met id --}}
    x-on:open-modal.window="if ($event.detail === '{{ $id }}') open = true"
    x-on:close-modal.window="if ($event.detail === '{{ $id }}') open = false"
>
    <div
        x-show="open"
        x-transition
        class="fixed inset-0 {{ $z }} flex items-center justify-center bg-black/40 backdrop-blur-sm"
        @keydown.escape.window="open = false"
        @click.self="{{ $closeOnBackdrop ? 'open = false' : '' }}"
        style="display: none;"
    >
        <div class="bg-white rounded-2xl p-6 shadow-xl w-[92%] {{ $maxClass }} relative border border-slate-200">
            {{-- Close --}}
            <button
                type="button"
                class="absolute top-3 right-3 p-1 rounded hover:bg-slate-100"
                @click="open = false"
                aria-label="Close"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            @if($title)
                <h3 class="text-sm font-semibold text-slate-900 mb-3">{{ $title }}</h3>
            @endif

            {{-- Body --}}
            <div>
                {{ $slot }}
            </div>

            {{-- Footer slot (optioneel) --}}
            @isset($footer)
                <div class="mt-4">
                    {{ $footer }}
                </div>
            @endisset
        </div>
    </div>
</div>
