<nav class="sticky top-0 z-[60] bg-white border-b border-slate-200">
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
                        <!-- ZET HOOG: z-[80] -->
                        <div class="hidden absolute mt-2 w-40 rounded-lg border border-slate-200 bg-white shadow-xl p-1 z-[80]">
                            <a href="{{ route('locale','en') }}" class="block px-3 py-2 text-sm rounded-md text-slate-700 hover:bg-slate-100">English</a>
                            <a href="{{ route('locale','nl') }}" class="block px-3 py-2 text-sm rounded-md text-slate-700 hover:bg-slate-100">Nederlands</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2">
                @auth

                    <a href="{{ route('favorites.index') }}"
                       class="px-2 py-1 rounded-md text-sm text-slate-700 hover:text-slate-900 hover:bg-slate-100 transition">
                        {{ __('Favorites') }}
                    </a>

                    <div class="relative">
                        <button type="button"
                                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md text-sm text-slate-800 bg-slate-100 hover:bg-slate-200 transition"
                                onclick="const m=this.nextElementSibling; m.classList.toggle('hidden')">
                            {{ Auth::user()->name }}
                            <svg class="w-4 h-4 opacity-70" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <!-- ZET HOOG: z-[80] -->
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
