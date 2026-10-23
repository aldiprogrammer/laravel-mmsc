@extends('layouts.admin')

@section('title', 'Pertandingan Rank Team')
@section('page-title', 'Pertandingan')
@section('page-subtitle', 'Pengaturan point rank team')

@section('content')
    <div class="mb-6 flex items-center gap-2 text-sm text-slate-500">
        <span class="font-medium text-slate-700">Member Karir</span>
        <x-admin.icon name="chevron-right" class="size-3.5 text-slate-300" />
        <a href="{{ route('admin.karir.open.index') }}" class="hover:text-blue-600">Open Member Karir Team</a>
        <x-admin.icon name="chevron-right" class="size-3.5 text-slate-300" />
        <span>Pertandingan</span>
    </div>

    @if ($teams->isEmpty())
        <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
            Belum ada team dengan pembayaran lunas untuk jadwal ini.
        </div>
    @endif

    @if (session('success'))
        <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
            <p class="font-semibold">Data belum dapat disimpan:</p>
            <ul class="mt-1 list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-5 py-4">
            <h2 class="text-base font-semibold text-slate-900">Pertandingan Rank Team</h2>
            <p class="mt-1 text-sm text-slate-500">Pilih tanggal dan team home serta away untuk jadwal ini.</p>
        </div>

        <div class="grid gap-4 border-b border-slate-100 bg-slate-50/70 px-5 py-4 sm:grid-cols-3">
            <div><p class="text-xs font-medium uppercase tracking-wide text-slate-400">Kode Main</p><p class="mt-1 font-mono text-sm text-slate-700">{{ $game->kode_main }}</p></div>
            <div><p class="text-xs font-medium uppercase tracking-wide text-slate-400">Lapangan</p><p class="mt-1 text-sm text-slate-700">{{ $game->lapangan }}</p></div>
            <div><p class="text-xs font-medium uppercase tracking-wide text-slate-400">Jam Main</p><p class="mt-1 text-sm text-slate-700">{{ $game->jam_main }} WIB</p></div>
        </div>

        @if ($isMatchSelected)
            <div class="p-5 sm:p-7">
                <div class="mb-5 flex flex-col items-center justify-between gap-4 rounded-2xl bg-blue-950 p-6 text-white sm:flex-row">
                    <div class="flex-1 text-center sm:text-left"><p class="text-xs uppercase tracking-wider text-blue-200">Home</p><h3 class="mt-1 text-xl font-semibold">{{ $teamHome->team }}</h3></div>
                    <div class="flex items-center gap-4"><span class="text-4xl font-bold tabular-nums">{{ $resultSaved ? $savedResult->score_team : $goalsHome->count() }}</span><span class="text-sm font-medium text-blue-200">VS</span><span class="text-4xl font-bold tabular-nums">{{ $resultSaved ? $savedResult->score_lawan : $goalsAway->count() }}</span></div>
                    <div class="flex-1 text-center sm:text-right"><p class="text-xs uppercase tracking-wider text-blue-200">Away</p><h3 class="mt-1 text-xl font-semibold">{{ $teamAway->team }}</h3></div>
                </div>
                <div class="flex flex-col gap-3 text-sm text-slate-600 sm:flex-row sm:items-center sm:justify-between">
                    <p>Tanggal main: <span class="font-medium text-slate-800">{{ $tglMain }}</span></p>
                    <a href="{{ route('admin.karir.open.point', ['kode_main' => $kodeMain]) }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2.5 font-medium text-slate-700 transition-colors hover:bg-slate-50">Pilih ulang team</a>
                </div>

                <div class="mt-6 grid gap-5 xl:grid-cols-2">
                    @foreach ([['code' => 'A', 'team' => $teamHome, 'players' => $playersHome, 'goals' => $goalsHome, 'teamCode' => $selectedTeamA], ['code' => 'B', 'team' => $teamAway, 'players' => $playersAway, 'goals' => $goalsAway, 'teamCode' => $selectedTeamB]] as $side)
                        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
                            <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3">
                                <div><h3 class="text-sm font-semibold text-slate-900">{{ $side['team']->team }}</h3><p class="text-xs text-slate-500">{{ $side['code'] === 'A' ? 'Team Home' : 'Team Away' }}</p></div>
                                <x-admin.badge tone="indigo">{{ $side['goals']->count() }} gol</x-admin.badge>
                            </div>

                            @if (! $resultSaved)
                            <form method="POST" action="{{ route('admin.karir.open.point.add-goal') }}" class="space-y-3 border-b border-slate-100 bg-slate-50/70 p-4">
                                @csrf
                                <input type="hidden" name="kode_main" value="{{ $kodeMain }}">
                                <input type="hidden" name="kode" value="{{ $kode }}">
                                <input type="hidden" name="teamA" value="{{ $selectedTeamA }}">
                                <input type="hidden" name="teamB" value="{{ $selectedTeamB }}">
                                <input type="hidden" name="tglmain" value="{{ $tglMain }}">
                                <input type="hidden" name="goalteam" value="{{ $side['code'] }}">

                                @if ($side['players']->isEmpty())
                                    <p class="rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-xs text-amber-800">Data pemain untuk team ini belum tersedia. Gol bunuh diri masih dapat dicatat.</p>
                                @endif

                                <div class="grid gap-3 sm:grid-cols-3">
                                    <div class="sm:col-span-1">
                                        <label for="goal-{{ $side['code'] }}" class="mb-1 block text-xs font-medium text-slate-700">Pencetak Gol</label>
                                        <select id="goal-{{ $side['code'] }}" name="goal" required class="w-full rounded-lg border border-slate-200 bg-white px-2.5 py-2 text-sm text-slate-700 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                                            <option value="">Pilih pemain</option>
                                            <option value="0">Own Goal</option>
                                            @foreach ($side['players'] as $player)
                                                <option value="{{ $player->id }}">{{ $player->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <label for="assist-{{ $side['code'] }}" class="mb-1 block text-xs font-medium text-slate-700">Assist (opsional)</label>
                                        <select id="assist-{{ $side['code'] }}" name="assist" class="w-full rounded-lg border border-slate-200 bg-white px-2.5 py-2 text-sm text-slate-700 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                                            <option value="">Tanpa assist</option>
                                            @foreach ($side['players'] as $player)
                                                <option value="{{ $player->id }}">{{ $player->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label for="menit-{{ $side['code'] }}" class="mb-1 block text-xs font-medium text-slate-700">Menit</label>
                                        <input id="menit-{{ $side['code'] }}" type="number" name="menit" min="0" max="150" placeholder="cth. 25" class="w-full rounded-lg border border-slate-200 bg-white px-2.5 py-2 text-sm text-slate-700 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                                    </div>
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit" class="rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white transition-colors hover:bg-blue-700">Tambah Gol {{ $side['code'] }}</button>
                                </div>
                            </form>
                            @else
                                <p class="border-b border-slate-100 bg-slate-50/70 px-4 py-3 text-xs text-slate-500">Input goal dikunci karena point pertandingan sudah disimpan.</p>
                            @endif

                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-xs">
                                    <thead class="bg-white uppercase tracking-wide text-slate-400"><tr><th scope="col" class="px-4 py-2.5 font-medium">#</th><th scope="col" class="px-4 py-2.5 font-medium">Gol</th><th scope="col" class="px-4 py-2.5 font-medium">Assist</th><th scope="col" class="px-4 py-2.5 font-medium">Menit</th><th scope="col" class="px-4 py-2.5 font-medium">Aksi</th></tr></thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @forelse ($side['goals'] as $goal)
                                            @php
                                                $goalPlayer = $side['players']->firstWhere('id', (int) $goal->goal);
                                                $assistPlayer = $side['players']->firstWhere('id', (int) $goal->assist);
                                            @endphp
                                            <tr>
                                                <td class="px-4 py-2.5 text-slate-400">{{ $loop->iteration }}</td>
                                                <td class="px-4 py-2.5 text-slate-700">{{ (string) $goal->goal === '0' ? 'Own Goal' : ($goalPlayer?->nama ?? 'Pemain tidak ditemukan') }}</td>
                                                <td class="px-4 py-2.5 text-slate-600">{{ $assistPlayer?->nama ?? '-' }}</td>
                                                <td class="px-4 py-2.5 text-slate-600">{{ $goal->menit !== '' ? $goal->menit.' min' : '-' }}</td>
                                                <td class="space-y-2 px-4 py-2.5">
                                                    @if (! $resultSaved)
                                                    <details>
                                                        <summary class="cursor-pointer font-semibold text-blue-600 hover:text-blue-700">Edit</summary>
                                                        <form method="POST" action="{{ route('admin.karir.open.point.update-goal', $goal->id) }}" class="mt-2 min-w-56 space-y-2 rounded-lg border border-slate-200 bg-slate-50 p-3">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="kode_main" value="{{ $kodeMain }}">
                                                            <input type="hidden" name="kode" value="{{ $kode }}">
                                                            <input type="hidden" name="teamA" value="{{ $selectedTeamA }}">
                                                            <input type="hidden" name="teamB" value="{{ $selectedTeamB }}">
                                                            <input type="hidden" name="tglmain" value="{{ $tglMain }}">
                                                            <input type="hidden" name="goalteam" value="{{ $side['code'] }}">
                                                            <label class="block text-[11px] font-medium text-slate-600">Pencetak gol
                                                                <select name="goal" required class="mt-1 w-full rounded-md border border-slate-200 bg-white px-2 py-1.5 text-xs">
                                                                    <option value="0" @selected((string) $goal->goal === '0')>Own Goal</option>
                                                                    @foreach ($side['players'] as $player)
                                                                        <option value="{{ $player->id }}" @selected((string) $goal->goal === (string) $player->id)>{{ $player->nama }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </label>
                                                            <label class="block text-[11px] font-medium text-slate-600">Assist
                                                                <select name="assist" class="mt-1 w-full rounded-md border border-slate-200 bg-white px-2 py-1.5 text-xs">
                                                                    <option value="">Tanpa assist</option>
                                                                    @foreach ($side['players'] as $player)
                                                                        <option value="{{ $player->id }}" @selected((string) $goal->assist === (string) $player->id)>{{ $player->nama }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </label>
                                                            <label class="block text-[11px] font-medium text-slate-600">Menit
                                                                <input type="number" name="menit" min="0" max="150" value="{{ $goal->menit }}" class="mt-1 w-full rounded-md border border-slate-200 bg-white px-2 py-1.5 text-xs">
                                                            </label>
                                                            <button type="submit" class="rounded-md bg-blue-600 px-2.5 py-1.5 text-xs font-semibold text-white hover:bg-blue-700">Simpan</button>
                                                        </form>
                                                    </details>
                                                    <form method="POST" action="{{ route('admin.karir.open.point.destroy-goal', $goal->id) }}" onsubmit="return confirm('Hapus data gol ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="kode_main" value="{{ $kodeMain }}">
                                                        <input type="hidden" name="kode" value="{{ $kode }}">
                                                        <input type="hidden" name="teamA" value="{{ $selectedTeamA }}">
                                                        <input type="hidden" name="teamB" value="{{ $selectedTeamB }}">
                                                        <input type="hidden" name="tglmain" value="{{ $tglMain }}">
                                                        <input type="hidden" name="goalteam" value="{{ $side['code'] }}">
                                                        <button type="submit" class="font-semibold text-rose-600 hover:text-rose-700">Hapus</button>
                                                    </form>
                                                    @else
                                                        <span class="text-xs text-slate-400">Terkunci</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="5" class="px-4 py-5 text-center text-slate-400">Belum ada gol dicatat.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    @endforeach
                </div>

                <section class="mt-6 rounded-2xl border border-indigo-200 bg-indigo-50/60 p-5">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-sm font-semibold text-slate-900">Simpan Point Rank Team</h3>
                            <p class="mt-1 text-xs text-slate-600">Masukkan skor akhir. Data pertandingan, statistik pemain, dan klasemen session aktif akan diperbarui.</p>
                        </div>
                        @if ($resultSaved)
                            <x-admin.badge tone="emerald">Point sudah ditambahkan</x-admin.badge>
                        @endif
                    </div>

                    @if (! $resultSaved)
                        <form method="POST" action="{{ route('admin.karir.open.point.save-result') }}" class="mt-4">
                            @csrf
                            <input type="hidden" name="kode_main" value="{{ $kodeMain }}">
                            <input type="hidden" name="kode" value="{{ $kode }}">
                            <input type="hidden" name="teamA" value="{{ $selectedTeamA }}">
                            <input type="hidden" name="teamB" value="{{ $selectedTeamB }}">
                            <input type="hidden" name="tglmain" value="{{ $tglMain }}">

                            <div class="grid items-end gap-3 sm:grid-cols-[1fr_auto_1fr_auto]">
                                <label class="text-xs font-medium text-slate-700">{{ $teamHome->team }}
                                    <input type="number" name="scoreteam" min="0" max="99" required value="{{ old('scoreteam', $goalsHome->count()) }}" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-center text-lg font-semibold text-slate-800" placeholder="0">
                                </label>
                                <span class="pb-2 text-center text-xs font-semibold uppercase text-slate-400">VS</span>
                                <label class="text-xs font-medium text-slate-700">{{ $teamAway->team }}
                                    <input type="number" name="scorelawan" min="0" max="99" required value="{{ old('scorelawan', $goalsAway->count()) }}" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-center text-lg font-semibold text-slate-800" placeholder="0">
                                </label>
                                <button type="submit" onclick="return confirm('Simpan hasil pertandingan dan tambahkan point rank team?')" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-indigo-700">Tambah Point Rank Team</button>
                            </div>
                        </form>
                    @endif
                </section>
            </div>
        @else
            <form method="GET" action="{{ route('admin.karir.open.point') }}" class="space-y-5 p-5 sm:p-7">
                <input type="hidden" name="kode_main" value="{{ $kodeMain }}">
                <input type="hidden" name="slide" value="2">
                <input type="hidden" name="kode" value="{{ $kode }}">

                <div class="max-w-sm">
                    <label for="tglmain" class="mb-1.5 block text-sm font-medium text-slate-700">Tanggal Main</label>
                    <input type="date" id="tglmain" name="tglmain" value="{{ $tglMain }}" required
                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="teamA" class="mb-1.5 block text-sm font-medium text-slate-700">Team Home</label>
                        <select id="teamA" name="teamA" required
                            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                            <option value="">-- Pilih team --</option>
                            @foreach ($teams as $team)
                                <option value="{{ $team->kode_team }}" @selected($selectedTeamA === (string) $team->kode_team)>{{ $team->team }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="teamB" class="mb-1.5 block text-sm font-medium text-slate-700">Team Away</label>
                        <select id="teamB" name="teamB" required
                            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                            <option value="">-- Pilih team lawan --</option>
                            @foreach ($teams as $team)
                                <option value="{{ $team->kode_team }}" @selected($selectedTeamB === (string) $team->kode_team)>{{ $team->team }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex justify-end border-t border-slate-100 pt-5">
                    <button type="submit" @disabled($teams->count() < 2)
                        class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-medium text-white shadow-lg shadow-blue-600/20 transition-colors hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50">
                        Lanjut
                        <x-admin.icon name="chevron-right" class="size-4" />
                    </button>
                </div>
            </form>
        @endif
    </section>
@endsection
