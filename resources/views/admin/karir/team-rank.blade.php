@extends('layouts.admin')

@section('title', 'Team Rank Team')
@section('page-title', 'Member Karir')
@section('page-subtitle', 'Team Rank Team')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <nav class="flex items-center gap-2 text-sm text-slate-500">
            <span class="font-medium text-slate-700">Member Karir</span>
            <x-admin.icon name="chevron-right" class="size-3.5 text-slate-300" />
            <span class="text-slate-500">Team Rank Team</span>
        </nav>

        <div class="flex flex-wrap items-center gap-2">
            <form method="GET" action="{{ route('admin.karir.team-rank') }}" class="relative">
                @if ($status)
                    <input type="hidden" name="status" value="{{ $status }}">
                @endif

                <x-admin.icon name="search"
                    class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-slate-400" />
                <input type="search" name="q" value="{{ $search }}" placeholder="Cari nama team..."
                    class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-9 pr-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100 sm:w-64" />
            </form>
        </div>
    </div>

    <div class="mb-6 flex flex-wrap items-center gap-2" data-filter>
        @php
            $tabs = [
                'all' => ['label' => 'Semua', 'count' => $totalAktif + $totalNonaktif],
                'aktif' => ['label' => 'Aktif', 'count' => $totalAktif],
                'nonaktif' => ['label' => 'Tidak Aktif', 'count' => $totalNonaktif],
            ];
        @endphp

        @foreach ($tabs as $key => $tab)
            @php
                $value = $key === 'all' ? null : $key;
                $isActive = $status === $value;
            @endphp

            <a href="{{ route('admin.karir.team-rank', array_filter(['status' => $value, 'q' => $search])) }}"
                class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-medium shadow-sm transition-colors {{ $isActive ? 'bg-blue-600 text-white' : 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50' }}">
                {{ $tab['label'] }}
                <span class="rounded-full px-1.5 text-[11px] {{ $isActive ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500' }}">
                    {{ $tab['count'] }}
                </span>
            </a>
        @endforeach
    </div>

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
            <div>
                <h3 class="text-sm font-semibold text-slate-900">Daftar Team Rank Team</h3>
                <p class="text-xs text-slate-500">Data diambil dari tabel tbl_teamrankteam</p>
            </div>

            <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                {{ $teams->total() }} team
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] text-left text-sm">
                <thead class="bg-slate-50 text-[11px] uppercase tracking-wide text-slate-500">
                    <tr>
                        <th scope="col" class="px-5 py-3 font-medium">Team</th>
                        <th scope="col" class="px-5 py-3 font-medium">Kode</th>
                        <th scope="col" class="px-5 py-3 font-medium">Pool</th>
                        <th scope="col" class="px-5 py-3 text-center font-medium">Status</th>
                        <th scope="col" class="px-5 py-3 text-right font-medium">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($teams as $team)
                        <tr class="transition-colors hover:bg-slate-50">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    @if ($team->logo_team)
                                        <img src="{{ $team->logo_team }}" alt="{{ $team->nama_team }}"
                                            class="size-10 shrink-0 rounded-xl object-cover ring-1 ring-slate-200">
                                    @else
                                        <span class="grid size-10 shrink-0 place-items-center rounded-xl bg-blue-50 text-blue-600">
                                            <x-admin.icon name="shield-check" class="size-5" />
                                        </span>
                                    @endif

                                    <div class="min-w-0">
                                        <p class="font-medium text-slate-800">{{ trim($team->nama_team) }}</p>
                                        <p class="truncate text-xs text-slate-400">{{ $team->id_user }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3 font-mono text-xs text-slate-500">{{ $team->kode }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $team->pool ?: '-' }}</td>
                            <td class="px-5 py-3 text-center">
                                @if ($team->isAktif())
                                    <x-admin.badge tone="emerald">Aktif</x-admin.badge>
                                @else
                                    <x-admin.badge tone="rose">Tidak Aktif</x-admin.badge>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-right">
                                <a href="{{ route('admin.karir.team-rank.detail', $team) }}"
                                    class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-blue-600 shadow-sm transition-colors hover:bg-blue-50">
                                    Detail
                                    <x-admin.icon name="chevron-right" class="size-3.5" />
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-sm text-slate-400">
                                Belum ada data team
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($teams->hasPages())
            <div class="border-t border-slate-100 px-5 py-4">
                {{ $teams->links() }}
            </div>
        @endif
    </section>
@endsection
