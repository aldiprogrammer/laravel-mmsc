@extends('layouts.admin')

@section('title', 'Detail Team Rank Team')
@section('page-title', 'Detail Team')
@section('page-subtitle', trim($team->nama_team))

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <nav class="flex flex-wrap items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('admin.karir.team-rank') }}" class="font-medium text-blue-600 hover:text-blue-700">Member Karir</a>
            <x-admin.icon name="chevron-right" class="size-3.5 text-slate-300" />
            <a href="{{ route('admin.karir.team-rank') }}" class="font-medium text-blue-600 hover:text-blue-700">Team Rank Team</a>
            <x-admin.icon name="chevron-right" class="size-3.5 text-slate-300" />
            <span class="text-slate-500">{{ trim($team->nama_team) }}</span>
        </nav>

        <a href="{{ route('admin.karir.team-rank') }}"
            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition-colors hover:bg-slate-50">
            <x-admin.icon name="chevron-right" class="size-4 rotate-180" />
            Kembali
        </a>
    </div>

    <section class="mb-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center">
            @if ($team->logo_team)
                <img src="{{ $team->logo_team }}" alt="{{ $team->nama_team }}" width="80" height="80"
                    class="size-20 max-h-20 max-w-20 shrink-0 rounded-2xl object-contain ring-1 ring-slate-200">
            @else
                <span class="grid size-20 shrink-0 place-items-center rounded-2xl bg-blue-50 text-blue-600">
                    <x-admin.icon name="shield-check" class="size-9" />
                </span>
            @endif

            <div class="min-w-0 flex-1">
                <div class="flex flex-wrap items-center gap-2">
                    <h2 class="text-lg font-semibold text-slate-900">{{ trim($team->nama_team) }}</h2>
                    @if ($team->isAktif())
                        <x-admin.badge tone="emerald">Aktif</x-admin.badge>
                    @else
                        <x-admin.badge tone="rose">Tidak Aktif</x-admin.badge>
                    @endif
                </div>

                <dl class="mt-3 grid gap-x-8 gap-y-2 text-sm sm:grid-cols-2 lg:grid-cols-3">
                    <div class="flex gap-2">
                        <dt class="text-slate-400">Kode</dt>
                        <dd class="font-medium text-slate-700">{{ $team->kode }}</dd>
                    </div>
                    <div class="flex gap-2">
                        <dt class="text-slate-400">ID User</dt>
                        <dd class="font-medium text-slate-700">{{ $team->id_user }}</dd>
                    </div>
                    <div class="flex gap-2">
                        <dt class="text-slate-400">Pool</dt>
                        <dd class="font-medium text-slate-700">{{ $team->pool ?: '-' }}</dd>
                    </div>
                    <div class="flex gap-2">
                        <dt class="text-slate-400">Total Pemain</dt>
                        <dd class="font-medium text-slate-700">{{ $pemainList->count() }}</dd>
                    </div>
                    <div class="flex gap-2">
                        <dt class="text-slate-400">Tambah Pemain</dt>
                        <dd class="font-medium text-slate-700">{{ $team->tambah_pemain ? 'Ya' : 'Tidak' }}</dd>
                    </div>
                    <div class="flex gap-2">
                        <dt class="text-slate-400">Terdaftar</dt>
                        <dd class="font-medium text-slate-700">
                            {{ \Illuminate\Support\Carbon::parse($team->date)->translatedFormat('d M Y, H:i') }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </section>

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
            <div>
                <h3 class="text-sm font-semibold text-slate-900">Daftar Pemain</h3>
                <p class="text-xs text-slate-500">Pemain dengan kode_team {{ $team->kode }}</p>
            </div>

            <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                {{ $pemainList->count() }} pemain
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[680px] text-left text-sm">
                <thead class="bg-slate-50 text-[11px] uppercase tracking-wide text-slate-500">
                    <tr>
                        <th scope="col" class="px-5 py-3 font-medium">Pemain</th>
                        <th scope="col" class="px-5 py-3 font-medium">Posisi</th>
                        <th scope="col" class="px-5 py-3 font-medium">NIK</th>
                        <th scope="col" class="px-5 py-3 text-center font-medium">No. Punggung</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($pemainList as $pemain)
                        <tr class="transition-colors hover:bg-slate-50">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    @if ($pemain->foto)
                                        <img src="{{ $pemain->foto }}" alt="{{ $pemain->nama }}"
                                            class="size-9 shrink-0 rounded-lg object-cover ring-1 ring-slate-200">
                                    @else
                                        <span class="grid size-9 shrink-0 place-items-center rounded-lg bg-blue-50 text-xs font-semibold text-blue-600">
                                            {{ strtoupper(mb_substr($pemain->nama, 0, 1)) }}
                                        </span>
                                    @endif

                                    <div class="min-w-0">
                                        <p class="font-medium text-slate-800">{{ $pemain->nama }}</p>
                                        @if ($pemain->nickname)
                                            <p class="truncate text-xs text-slate-400">{{ $pemain->nickname }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3">
                                <x-admin.badge tone="indigo">{{ $pemain->posisi ?: 'Tidak diketahui' }}</x-admin.badge>
                            </td>
                            <td class="px-5 py-3 font-mono text-xs text-slate-500">{{ $pemain->nik ?: '-' }}</td>
                            <td class="px-5 py-3 text-center font-medium text-slate-700">{{ $pemain->no_punggung ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-8 text-center text-sm text-slate-400">
                                Belum ada pemain pada team ini
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
