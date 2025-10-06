<nav class="sticky top-0 z-[60] bg-white border-b border-slate-200" x-data>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center gap-6">
                <a href="{{ url('/') }}" class="flex items-center gap-2 group">
                    <x-application-logo class="block h-6 w-auto text-slate-700 group-hover:text-slate-900 transition" />
                    <span class="sr-only">{{ config('app.name') }}</span>
                </a>

                <div class="hidden sm:flex items-center gap-2">
                    <a href="{{ route('ads.index') }}"
                       class="px-2 py-1 rounded-md text-sm text-slate-700 hover:text-slate-900 hover:bg-slate-100 transition">
                        All Ads
                    </a>
                    <a href="{{ route('ads.user-rented-ads') }}"
                       class="px-2 py-1 rounded-md text-sm text-slate-700 hover:text-slate-900 hover:bg-slate-100 transition">
                        Rented Ads
                    </a>

                    <div class="relative">
                        <button type="button"
                                class="inline-flex items-center gap-1 px-2 py-1 rounded-md text-sm text-slate-700 hover:text-slate-900 hover:bg-slate-100 transition"
                                onclick="const m=this.nextElementSibling; m.classList.toggle('hidden')">
                            Language
                            <svg class="w-4 h-4 opacity-70" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div class="hidden absolute mt-2 w-40 rounded-lg border border-slate-200 bg-white shadow-xl p-1 z-[80]">
                            <a href="{{ route('locale','en') }}" class="block px-3 py-2 text-sm rounded-md text-slate-700 hover:bg-slate-100">English</a>
                            <a href="{{ route('locale','nl') }}" class="block px-3 py-2 text-sm rounded-md text-slate-700 hover:bg-slate-100">Nederlands</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2">
                @auth
                    @php $favCount = auth()->user()->favorites()->count(); @endphp
                    {{-- Alpine store initiëren --}}
                    <div x-init="Alpine.store('fav', { count: {{ $favCount }} })"></div>

                    {{-- Favorietenknop --}}
                    <a href="{{ route('favorites.index') }}" class="relative inline-flex items-center p-2 rounded-md hover:bg-rose-50 transition" title="Favorieten">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-rose-500" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 6 4 4 6.5 4c1.74 0 3.41 1.01 4.13 2.44h.74C13.09 5.01 14.76 4 16.5 4 19 4 21 6 21 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>

                        <template x-if="$store.fav.count > 0">
                            <span class="absolute -top-0.5 -right-0.5 flex items-center justify-center
                                         w-4 h-4 text-[10px] font-bold text-white bg-rose-500 rounded-full ring-2 ring-white"
                                  x-text="$store.fav.count"></span>
                        </template>
                        <span class="sr-only">Favorieten</span>
                    </a>

                    {{-- Profiel --}}
                    <div class="relative">
                        <button type="button"
                                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md text-sm text-slate-800 bg-slate-100 hover:bg-slate-200 transition"
                                onclick="const m=this.nextElementSibling; m.classList.toggle('hidden')">
                            {{ Auth::user()->name }}
                            <svg class="w-4 h-4 opacity-70" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div class="hidden absolute right-0 mt-2 w-48 rounded-lg border border-slate-200 bg-white shadow-xl p-1 z-[80]">
                            <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-sm rounded-md text-slate-700 hover:bg-slate-100">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="w-full text-left px-3 py-2 text-sm rounded-md text-slate-700 hover:bg-slate-100">Log out</button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>
