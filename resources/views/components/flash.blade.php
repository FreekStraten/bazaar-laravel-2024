@props([
    // Keys to read from the session. Keep 'status' for backward compatibility.
    'keys' => ['error', 'success', 'status', 'warning', 'info'],
    // Auto dismiss in milliseconds; set to 0 to disable
    'timeout' => 4000,
])

@php
    $messages = collect($keys)
        ->mapWithKeys(fn($k) => [$k => session($k)])
        ->filter();

    $styles = [
        'success' => 'border-emerald-200 bg-emerald-50 text-emerald-800',
        'status'  => 'border-emerald-200 bg-emerald-50 text-emerald-800',
        'error'   => 'border-red-200 bg-red-50 text-red-800',
        'warning' => 'border-amber-200 bg-amber-50 text-amber-800',
        'info'    => 'border-sky-200 bg-sky-50 text-sky-800',
    ];
@endphp

@if ($messages->isNotEmpty())
    <div class="space-y-2">
        @foreach($messages as $type => $msg)
            <div
                x-data="{ show: true }"
                x-show="show"
                @if($timeout && $timeout > 0)
                    x-init="setTimeout(() => show = false, {{ (int) $timeout }})"
                @endif
                class="mb-2 rounded-md border px-3 py-2 text-sm {{ $styles[$type] ?? 'border-slate-200 bg-slate-50 text-slate-800' }}"
                role="alert" aria-live="polite"
            >
                <div class="flex items-start gap-2">
                    <div class="flex-1">{!! is_string($msg) ? e($msg) : e((string) $msg) !!}</div>
                    <button type="button" class="shrink-0 rounded p-1 hover:bg-white/30" @click="show=false" aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4 opacity-70">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        @endforeach
    </div>
@endif

