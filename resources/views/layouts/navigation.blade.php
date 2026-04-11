<nav x-data="{ open: false }" class="app-glass-nav sticky top-0 z-40 border-b border-white/50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 lg:-my-px lg:ms-10 lg:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.index') || request()->routeIs('reports.show')">
                        {{ __('Laporan') }}
                    </x-nav-link>
                    @if(Auth::user()->role === 'admin')
                        <x-nav-link :href="route('admin.sales.index')" :active="request()->routeIs('admin.sales.*')">
                            {{ __('Kelola Sales') }}
                        </x-nav-link>
                    @endif
                    @if(Auth::user()->role === 'sales')
                        <x-nav-link :href="route('reports.create')" :active="request()->routeIs('reports.create')">
                            {{ __('Buat Laporan') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden lg:flex lg:items-center lg:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center lg:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden lg:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.index') || request()->routeIs('reports.show')">
                {{ __('Laporan') }}
            </x-responsive-nav-link>
            @if(Auth::user()->role === 'admin')
                <x-responsive-nav-link :href="route('admin.sales.index')" :active="request()->routeIs('admin.sales.*')">
                    {{ __('Kelola Sales') }}
                </x-responsive-nav-link>
            @endif
            @if(Auth::user()->role === 'sales')
                <x-responsive-nav-link :href="route('reports.create')" :active="request()->routeIs('reports.create')">
                    {{ __('Buat Laporan') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

<!-- Mobile Bottom Navigation -->
<div class="lg:hidden fixed bottom-0 inset-x-0 z-50 border-t border-white/50 bg-white/85 backdrop-blur-xl" style="position: fixed; left: 0; right: 0; bottom: 0; z-index: 55; box-shadow: 0 -12px 30px rgba(15, 23, 42, 0.08);">
    <div class="grid {{ Auth::user()->role === 'sales' ? 'grid-cols-4' : 'grid-cols-4' }}">
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center py-2.5 {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-600' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span class="mt-1 text-[11px] font-semibold">Dashboard</span>
        </a>

        <a href="{{ route('reports.index') }}" class="flex flex-col items-center justify-center py-2.5 {{ request()->routeIs('reports.index') || request()->routeIs('reports.show') ? 'text-blue-600' : 'text-gray-600' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <span class="mt-1 text-[11px] font-semibold">Laporan</span>
        </a>

        @if(Auth::user()->role === 'sales')
            <a href="{{ route('reports.create') }}" class="flex flex-col items-center justify-center py-2.5 {{ request()->routeIs('reports.create') ? 'text-blue-600' : 'text-gray-600' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span class="mt-1 text-[11px] font-semibold">Buat</span>
            </a>
        @else
            <a href="{{ route('admin.sales.index') }}" class="flex flex-col items-center justify-center py-2.5 {{ request()->routeIs('admin.sales.*') ? 'text-blue-600' : 'text-gray-600' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5V4H2v16h5m10 0v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5m10 0H7"></path>
                </svg>
                <span class="mt-1 text-[11px] font-semibold">Sales</span>
            </a>
        @endif

        <a href="{{ route('profile.edit') }}" class="flex flex-col items-center justify-center py-2.5 {{ request()->routeIs('profile.*') ? 'text-blue-600' : 'text-gray-600' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="mt-1 text-[11px] font-semibold">Profil</span>
        </a>
    </div>
</div>
