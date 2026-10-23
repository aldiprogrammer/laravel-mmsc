@php
    $primaryMenu = [
        ['label' => 'Dashboard', 'icon' => 'dashboard', 'route' => 'admin.dashboard'],
        ['label' => 'Data User', 'icon' => 'user-group', 'route' => 'admin.data-user'],
        ['label' => 'Pemain', 'icon' => 'user-group', 'route' => 'admin.pemain'],
        [
            'label' => 'Member Karir',
            'icon' => 'shield-check',
            'route' => 'admin.karir.team-rank',
            'children' => [
                ['label' => 'Team Rank Team', 'route' => 'admin.karir.team-rank', 'match' => 'admin.karir.team-rank*'],
                ['label' => 'Open Member Karir Team', 'route' => 'admin.karir.open.index', 'match' => 'admin.karir.open.*'],
                ['label' => 'Open Member Karir Solo', 'route' => 'admin.karir.open-solo', 'match' => 'admin.karir.open-solo*'],
                ['label' => 'Data Member Karir Team', 'route' => 'admin.karir.member', 'match' => 'admin.karir.member*'],
            ],
        ],
        ['label' => 'Lapangan', 'icon' => 'map-pin', 'route' => 'admin.lapangan'],
        ['label' => 'Wasit', 'icon' => 'shield-check', 'route' => 'admin.wasit'],
        ['label' => 'Rompi', 'icon' => 'user-group', 'route' => 'admin.rompi'],
        ['label' => 'Bola', 'icon' => 'trophy', 'route' => 'admin.bola'],
        ['label' => 'Fotografer', 'icon' => 'user-group', 'route' => 'admin.fotografer'],
        ['label' => 'Videografer', 'icon' => 'user-group', 'route' => 'admin.videografer'],
        ['label' => 'Data Booking', 'icon' => 'calendar-days', 'route' => 'admin.booking'],
        ['label' => 'Pertandingan', 'icon' => 'trophy', 'route' => 'admin.pertandingan'],
        ['label' => 'Pembayaran', 'icon' => 'credit-card', 'route' => 'admin.pembayaran'],
        ['label' => 'Laporan', 'icon' => 'chart-bar', 'route' => 'admin.laporan'],
    ];

    $secondaryMenu = [
        ['label' => 'Pengaturan', 'icon' => 'cog-6-tooth', 'route' => 'admin.pengaturan'],
    ];
@endphp

<div id="admin-sidebar"
    class="fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col bg-blue-950 transition-transform duration-200 ease-out lg:translate-x-0">
    <div class="flex h-16 shrink-0 items-center gap-3 border-b border-white/10 px-5">
        <span class="grid size-9 shrink-0 place-items-center rounded-xl bg-blue-600 text-white shadow-lg shadow-blue-900/50">
            <x-admin.icon name="trophy" class="size-5" />
        </span>

        <div class="min-w-0">
            <p class="truncate text-sm font-semibold text-white">{{ config('app.name', 'MMSC') }}</p>
            <p class="truncate text-xs text-blue-200/60">Panel Admin</p>
        </div>

        <button type="button" data-sidebar-close class="ml-auto rounded-lg p-1.5 text-blue-200/70 hover:bg-white/10 hover:text-white lg:hidden">
            <x-admin.icon name="x-mark" class="size-5" />
            <span class="sr-only">Tutup menu</span>
        </button>
    </div>

    <nav class="custom-scrollbar flex-1 overflow-y-auto px-3 py-5">
        <p class="px-3 pb-2 text-[11px] font-semibold uppercase tracking-wider text-blue-300/50">Menu Utama</p>

        <ul class="space-y-1">
            @foreach ($primaryMenu as $item)
                @php
                    $children = $item['children'] ?? [];
                    $childPatterns = array_map(fn ($child) => $child['match'] ?? $child['route'].'*', $children);
                    $isActive = request()->routeIs($item['route'].'*', ...$childPatterns);
                @endphp

                <li>
                    <a href="{{ route($item['route']) }}"
                        @if ($isActive) aria-current="page" @endif
                        class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-colors {{ $isActive ? 'bg-blue-600 text-white shadow-lg shadow-blue-950/40' : 'text-blue-100/70 hover:bg-white/5 hover:text-white' }}">
                        <x-admin.icon :name="$item['icon']"
                            class="size-5 shrink-0 {{ $isActive ? 'text-white' : 'text-blue-300/70 group-hover:text-blue-200' }}" />
                        <span class="truncate">{{ $item['label'] }}</span>

                        @if ($isActive)
                            <span class="ml-auto size-1.5 rounded-full bg-white"></span>
                        @endif
                    </a>

                    @if (! empty($children))
                        <ul class="mt-1 space-y-1 border-l border-white/10 pl-4">
                            @foreach ($children as $child)
                                @php
                                    $childActive = request()->routeIs($child['match'] ?? $child['route'].'*');
                                @endphp

                                <li>
                                    <a href="{{ route($child['route']) }}"
                                        @if ($childActive) aria-current="page" @endif
                                        class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm transition-colors {{ $childActive ? 'text-white' : 'text-blue-100/60 hover:bg-white/5 hover:text-white' }}">
                                        <span class="size-1.5 rounded-full {{ $childActive ? 'bg-blue-400' : 'bg-blue-300/40' }}"></span>
                                        <span class="truncate">{{ $child['label'] }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>

        <p class="px-3 pb-2 pt-6 text-[11px] font-semibold uppercase tracking-wider text-blue-300/50">Lainnya</p>

        <ul class="space-y-1">
            @foreach ($secondaryMenu as $item)
                @php
                    $isActive = request()->routeIs($item['route']);
                @endphp

                <li>
                    <a href="{{ route($item['route']) }}"
                        @if ($isActive) aria-current="page" @endif
                        class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-colors {{ $isActive ? 'bg-blue-600 text-white shadow-lg shadow-blue-950/40' : 'text-blue-100/70 hover:bg-white/5 hover:text-white' }}">
                        <x-admin.icon :name="$item['icon']"
                            class="size-5 shrink-0 {{ $isActive ? 'text-white' : 'text-blue-300/70 group-hover:text-blue-200' }}" />
                        <span class="truncate">{{ $item['label'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>

    <div class="shrink-0 border-t border-white/10 p-4">
        <div class="rounded-xl bg-white/5 p-3">
            <div class="flex items-center gap-2 text-blue-200">
                <span class="relative flex size-2">
                    <span class="absolute inline-flex size-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex size-2 rounded-full bg-emerald-500"></span>
                </span>
                <span class="text-xs font-medium">Server aktif</span>
            </div>
            <p class="mt-1 text-[11px] leading-relaxed text-blue-200/50">
                {{ now()->translatedFormat('l, d F Y') }} &middot; {{ now()->format('H:i') }} WIB
            </p>
        </div>
    </div>
</div>

<div id="admin-overlay" class="fixed inset-0 z-40 hidden bg-slate-900/60 backdrop-blur-sm lg:hidden"></div>
