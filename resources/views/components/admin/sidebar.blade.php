<div class="fixed top-0 left-0 z-50 flex h-screen w-20 flex-col justify-between border-e bg-white shadow-lg">
    <div class="flex-grow">
        {{-- Logo Section --}}
        <div class="flex h-20 items-center justify-center border-b border-gray-100">
            <span class="grid h-12 w-12 place-content-center rounded-xl bg-white shadow-sm">
                <img src="{{ asset('images/logo_company.png') }}" class="h-10 w-12" alt="Company Logo">
            </span>
        </div>

        <div class="px-2">
            {{-- Dashboard --}}
            <div class="py-4">
                <x-admin.nav-link href="{{ route('admin.dashboard') }}" icon="dashboard" label="Dashboard" type="primary"
                    :active="request()->routeIs('admin.dashboard')" />
            </div>

            {{-- Main Navigation --}}
            <ul class="space-y-2 border-t border-gray-100 pt-4">
                @if (Auth::user()->role != 'pemilik')

                    {{-- Superadmin Only Section --}}
                    @if (Auth::user()->role == 'superadmin')
                        <li>
                            <x-admin.nav-link href="{{ route('admin.admin') }}" icon="admin_panel_settings"
                                label="Admin" type="warning" :active="request()->routeIs('admin.admin')" />
                        </li>
                    @endif

                    {{-- Users Section --}}
                    <li>
                        <x-admin.nav-link href="{{ route('admin.users') }}" icon="people" label="Users" type="success"
                            :active="request()->routeIs('admin.users')" />
                    </li>

                    {{-- Field Management --}}
                    <li>
                        <x-admin.nav-link href="{{ route('admin.lapangan') }}" icon="sports_soccer" label="Lapangan"
                            type="info" :active="request()->routeIs('admin.lapangan')" />
                    </li>

                    {{-- Booking Confirmation --}}
                    <li>
                        <x-admin.nav-link href="{{ route('admin.konfirmasi') }}" icon="event" label="Konfirmasi"
                            type="secondary" :active="request()->routeIs('admin.konfirmasi')" />
                    </li>
                    <li>
                        <x-admin.nav-link href="{{ route('admin.historyTable') }}" icon="table_chart"
                            label="historyTable" type="secondary" :active="request()->routeIs('admin.historyTable')" />
                    </li>

                @endif
                {{-- Transaction Reports Section --}}
                <li x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative">
                    <button
                        class="group flex w-full items-center justify-center rounded-xl px-3 py-3 transition-all duration-200
                    {{ request()->routeIs('admin.transaksi.*') ? 'bg-purple-50 text-purple-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <div class="relative">
                            <span class="material-icons text-2xl">list_alt</span>
                            @if (request()->routeIs('admin.transaksi.*'))
                                <span class="absolute right-0 top-0 h-2 w-2 rounded-full bg-purple-500"></span>
                            @endif
                        </div>
                    </button>


                    {{-- Dropdown Menu --}}
                    <div x-show="open" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        class="absolute z-50 left-full ml-4 top-0 mt-1 w-12 space-y-1 rounded-lg bg-white shadow-md pt-2">
                        <x-admin.nav-link href="{{ route('admin.transaksi.all') }}" icon="list_alt"
                            label="Semua Transaksi" type="purple" :active="request()->routeIs('admin.transaksi.all')" />
                        <x-admin.nav-link href="{{ route('admin.transaksi.daily') }}" icon="today" label="Harian"
                            type="purple" :active="request()->routeIs('admin.transaksi.daily')" />
                        <x-admin.nav-link href="{{ route('admin.transaksi.monthly') }}" icon="event" label="Bulanan"
                            type="purple" :active="request()->routeIs('admin.transaksi.monthly')" />
                        <x-admin.nav-link href="{{ route('admin.transaksi.yearly') }}" icon="calendar_today"
                            label="Tahunan" type="purple" :active="request()->routeIs('admin.transaksi.yearly')" />
                    </div>
                </li>
            </ul>
        </div>
    </div>

    {{-- Logout Section --}}
    <div class="sticky inset-x-0 bottom-0 border-t border-gray-100 bg-white p-3">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="group flex w-full items-center justify-center rounded-xl bg-red-50 px-3 py-3 text-red-600 transition-colors hover:bg-red-100">
                <span class="material-icons text-2xl">logout</span>
                <span
                    class="invisible absolute start-full top-1/2 ms-4 -translate-y-1/2 rounded-lg bg-gray-900 px-3 py-2 text-xs font-medium text-white group-hover:visible">
                    Logout
                </span>
            </button>
        </form>
    </div>
</div>
