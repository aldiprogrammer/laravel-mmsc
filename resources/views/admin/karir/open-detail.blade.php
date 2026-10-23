@extends('layouts.admin')

@section('title', 'Detail Member Karir Team')
@section('page-title', 'Detail Member Karir Team')
@section('page-subtitle', $kodeMain)

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <nav class="flex flex-wrap items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('admin.karir.open.index') }}" class="font-medium text-blue-600 hover:text-blue-700">Open Member Karir Team</a>
            <x-admin.icon name="chevron-right" class="size-3.5 text-slate-300" />
            <span class="text-slate-500">{{ $kodeMain }}</span>
        </nav>

        <a href="{{ route('admin.karir.open.index') }}"
            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition-colors hover:bg-slate-50">
            <x-admin.icon name="chevron-right" class="size-4 rotate-180" />
            Kembali
        </a>
    </div>

    @if ($game)
        <section class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex flex-wrap items-center gap-2">
                <h2 class="font-mono text-base font-semibold text-slate-900">{{ $game->kode_main }}</h2>
                @if ($game->isAktif())
                    <x-admin.badge tone="emerald">Aktif</x-admin.badge>
                @else
                    <x-admin.badge tone="rose">Tidak Aktif</x-admin.badge>
                @endif
            </div>

            <dl class="mt-4 grid gap-x-8 gap-y-2 text-sm sm:grid-cols-2 lg:grid-cols-3">
                <div class="flex gap-2">
                    <dt class="text-slate-400">Tanggal</dt>
                    <dd class="font-medium text-slate-700">{{ $game->tgl_main }}</dd>
                </div>
                <div class="flex gap-2">
                    <dt class="text-slate-400">Jam Main</dt>
                    <dd class="font-medium text-slate-700">{{ $game->jam_main }}</dd>
                </div>
                <div class="flex gap-2">
                    <dt class="text-slate-400">Jml Team</dt>
                    <dd class="font-medium text-slate-700">{{ $game->jml_team }}</dd>
                </div>
                <div class="flex gap-2">
                    <dt class="text-slate-400">Lapangan</dt>
                    <dd class="font-medium text-slate-700">{{ $game->lapangan }}</dd>
                </div>
                <div class="flex gap-2">
                    <dt class="text-slate-400">Open Sesi</dt>
                    <dd class="font-medium text-slate-700">{{ $game->open_sesi }}</dd>
                </div>
                <div class="flex gap-2">
                    <dt class="text-slate-400">Harga Sesi 1</dt>
                    <dd class="font-medium text-slate-700">Rp {{ number_format((float) $game->harga, 0, ',', '.') }}</dd>
                </div>
                <div class="flex gap-2">
                    <dt class="text-slate-400">Harga Sesi 2</dt>
                    <dd class="font-medium text-slate-700">Rp {{ number_format((float) ($game->harga2 !== '' ? $game->harga2 : $game->harga), 0, ',', '.') }}</dd>
                </div>
            </dl>
        </section>
    @endif

    <div class="grid gap-6 xl:grid-cols-2">
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <div>
                    <h3 class="text-sm font-semibold text-slate-900">Sesi 1</h3>
                    <p class="text-xs text-slate-500">Team pada sesi pertama</p>
                </div>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">{{ $team->count() }} team</span>
            </div>

            <ul class="divide-y divide-slate-100">
                @forelse ($team as $row)
                    <li class="flex items-center gap-3 px-5 py-3">
                        <span class="grid size-9 shrink-0 place-items-center rounded-lg bg-blue-50 text-xs font-semibold text-blue-600">
                            {{ strtoupper(mb_substr($row->team, 0, 1)) }}
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate font-medium text-slate-800">{{ $row->team }}</p>
                            <p class="truncate text-xs text-slate-400">vs {{ $row->lawan ?: 'Belum ditentukan' }}</p>
                        </div>
                    </li>
                @empty
                    <li class="px-5 py-8 text-center text-sm text-slate-400">Belum ada team pada sesi ini</li>
                @endforelse
            </ul>
        </section>

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <div>
                    <h3 class="text-sm font-semibold text-slate-900">Sesi 2</h3>
                    <p class="text-xs text-slate-500">Team pada sesi kedua</p>
                </div>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">{{ $lawan->count() }} team</span>
            </div>

            <ul class="divide-y divide-slate-100">
                @forelse ($lawan as $row)
                    <li class="flex items-center gap-3 px-5 py-3">
                        <span class="grid size-9 shrink-0 place-items-center rounded-lg bg-indigo-50 text-xs font-semibold text-indigo-600">
                            {{ strtoupper(mb_substr($row->team, 0, 1)) }}
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate font-medium text-slate-800">{{ $row->team }}</p>
                            <p class="truncate text-xs text-slate-400">vs {{ $row->lawan ?: 'Belum ditentukan' }}</p>
                        </div>
                    </li>
                @empty
                    <li class="px-5 py-8 text-center text-sm text-slate-400">Belum ada team pada sesi ini</li>
                @endforelse
            </ul>
        </section>
    </div>
@endsection
