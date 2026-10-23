@php
    $initials = strtoupper(mb_substr($admin->username ?? 'admin', 0, 2));
    $notifications = $notifications ?? collect();
@endphp

<header class="sticky top-0 z-30 border-b border-slate-200 bg-white/90 backdrop-blur">
    <div class="flex h-16 items-center gap-3 px-4 sm:gap-4 sm:px-6 lg:px-8">
        <button type="button" data-sidebar-toggle
            class="grid size-10 shrink-0 place-items-center rounded-xl border border-slate-200 bg-white text-slate-600 transition-colors hover:bg-slate-50 lg:hidden">
            <x-admin.icon name="bars-3" class="size-5" />
            <span class="sr-only">Buka menu</span>
        </button>

        <div class="min-w-0">
            <h1 class="truncate text-base font-semibold text-slate-900">@yield('page-title', 'Dashboard')</h1>
            <p class="hidden truncate text-xs text-slate-500 sm:block">@yield('page-subtitle', 'Ringkasan operasional hari ini')</p>
        </div>

        <div class="ml-auto flex items-center gap-2 sm:gap-3">
            <div class="relative hidden md:block">
                <x-admin.icon name="search"
                    class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-slate-400" />
                <input type="search" name="q" placeholder="Cari pemain, tim, booking..."
                    class="w-64 rounded-xl border border-slate-200 bg-slate-50 py-2 pl-9 pr-3 text-sm text-slate-700 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100" />
            </div>

            <div class="relative" data-dropdown>
                <button type="button" data-dropdown-trigger aria-expanded="false"
                    class="relative grid size-10 place-items-center rounded-xl border border-slate-200 bg-white text-slate-600 transition-colors hover:bg-slate-50">
                    <x-admin.icon name="bell" class="size-5" />
                    @if ($notifications->isNotEmpty())
                        <span class="absolute right-2 top-2 size-2 rounded-full bg-blue-600 ring-2 ring-white"></span>
                    @endif
                    <span class="sr-only">Notifikasi</span>
                </button>

                <div data-dropdown-menu
                    class="absolute right-0 z-40 mt-2 hidden w-80 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xl shadow-slate-900/5">
                    <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3">
                        <p class="text-sm font-semibold text-slate-800">Notifikasi</p>
                        <span class="rounded-full bg-blue-50 px-2 py-0.5 text-[11px] font-medium text-blue-600">
                            {{ $notifications->count() }} baru
                        </span>
                    </div>

                    <ul class="max-h-72 divide-y divide-slate-50 overflow-y-auto">
                        @forelse ($notifications->take(4) as $item)
                            <li class="flex gap-3 px-4 py-3 transition-colors hover:bg-slate-50">
                                <span class="mt-0.5 grid size-8 shrink-0 place-items-center rounded-lg bg-blue-50 text-blue-600">
                                    <x-admin.icon name="calendar-days" class="size-4" />
                                </span>
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-medium text-slate-800">
                                        Booking {{ $item->team ?: 'Tanpa tim' }}
                                    </p>
                                    <p class="truncate text-xs text-slate-500">
                                        {{ $item->lapangan }} &middot; {{ $item->jam_booking }}
                                    </p>
                                </div>
                            </li>
                        @empty
                            <li class="px-4 py-6 text-center text-sm text-slate-400">Belum ada notifikasi</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="relative" data-dropdown>
                <button type="button" data-dropdown-trigger aria-expanded="false"
                    class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white py-1.5 pl-1.5 pr-2 transition-colors hover:bg-slate-50 sm:gap-3 sm:pr-3">
                    <span class="grid size-8 shrink-0 place-items-center rounded-lg bg-blue-600 text-xs font-semibold text-white">
                        {{ $initials }}
                    </span>

                    <span class="hidden text-left sm:block">
                        <span class="block text-sm font-medium leading-tight text-slate-800">{{ $admin->username }}</span>
                        <span class="block text-xs capitalize leading-tight text-slate-500">{{ $admin->role }}</span>
                    </span>

                    <x-admin.icon name="chevron-down" class="size-4 shrink-0 text-slate-400" />
                </button>

                <div data-dropdown-menu
                    class="absolute right-0 z-40 mt-2 hidden w-56 overflow-hidden rounded-xl border border-slate-200 bg-white p-1.5 shadow-xl shadow-slate-900/5">
                    <div class="border-b border-slate-100 px-3 pb-2 pt-1">
                        <p class="text-sm font-medium text-slate-800">{{ $admin->username }}</p>
                        <p class="text-xs capitalize text-slate-500">{{ $admin->role }}</p>
                    </div>

                    <a href="{{ route('admin.pengaturan') }}"
                        class="mt-1 flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-slate-700 transition-colors hover:bg-slate-50">
                        <x-admin.icon name="cog-6-tooth" class="size-4 text-slate-400" />
                        Pengaturan
                    </a>

                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit"
                            class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-rose-600 transition-colors hover:bg-rose-50">
                            <x-admin.icon name="arrow-right-start-on-rectangle" class="size-4" />
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
