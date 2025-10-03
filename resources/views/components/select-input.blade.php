@props([
    'name' => null,
    'options' => [],          // ['value' => 'Label', ...]
    'placeholder' => 'Select…',
    'selected' => null,
])

@php
    $selected = old($name, $selected);
@endphp

<div
    x-data="{
        open: false,
        value: @js((string)($selected ?? '')),
        label: '',
        opts: @js($options),
        keys: Object.keys(@js($options)),
        setByValue(v) { this.value = v; this.label = this.opts[v] ?? @js($placeholder); },
        init() { this.setByValue(this.value); },
        move(dir) {
            const i = this.keys.indexOf(this.value);
            let ni = i === -1 ? 0 : i + dir;
            if (ni < 0) ni = this.keys.length - 1;
            if (ni >= this.keys.length) ni = 0;
            this.setByValue(this.keys[ni]);
            this.$nextTick(() => this.$refs.btn.focus());
        }
    }"
    x-init="init()"
    class="relative"
>
    <input type="hidden" name="{{ $name }}" :value="value">

    {{-- BUTTON (nu relative + padding) --}}
    <button type="button" x-ref="btn"
            @click="open = !open"
            @keydown.arrow-down.prevent="open = true; move(1)"
            @keydown.arrow-up.prevent="open = true; move(-1)"
            @keydown.enter.prevent="open = false"
            class="relative block w-full rounded-md border border-slate-300 bg-white px-3 pr-9 py-2.5
               text-left text-slate-900 shadow-sm focus:outline-none focus:ring-2
               focus:ring-slate-300 focus:border-slate-400 transition"
    >
        <span x-text="label || @js($placeholder)" class="truncate"></span>

        {{-- Chevron nu absoluut t.o.v. de knop --}}
        <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-500"
             viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd"
                  d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                  clip-rule="evenodd" />
        </svg>
    </button>

    {{-- DROPDOWN --}}
    <div x-show="open" x-transition
         @click.outside="open = false"
         @keydown.escape.prevent="open = false"
         class="absolute z-20 mt-2 w-full rounded-md border border-slate-200 bg-white shadow-lg overflow-hidden"
    >
        <ul role="listbox" class="max-h-64 overflow-auto py-1">
            @foreach($options as $key => $label)
                @php $k = (string)$key; @endphp
                <li role="option"
                    @click="setByValue(@js($k)); open = false"
                    class="cursor-pointer px-3 py-2 text-sm text-slate-900 hover:bg-slate-50"
                    :class="{ 'bg-slate-100 font-medium': value === @js($k) }"
                >
                    {{ $label }}
                </li>
            @endforeach
        </ul>
    </div>
</div>
