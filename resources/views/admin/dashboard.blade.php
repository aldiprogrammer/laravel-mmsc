@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan operasional '.config('app.name', 'MMSC').' hari ini')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-semibold tracking-tight text-slate-900">
                Selamat datang, {{ $admin->username }}
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Berikut ringkasan aktivitas operasional hari ini.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.laporan') }}"
                class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition-colors hover:bg-slate-50">
                <x-admin.icon name="chart-bar" class="size-4" />
                Laporan
            </a>

            <a href="{{ route('admin.booking') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-medium text-white shadow-lg shadow-blue-600/25 transition-colors hover:bg-blue-700">
                <x-admin.icon name="plus" class="size-4" />
                Booking Baru
            </a>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($stats as $stat)
            <x-admin.stat-card :label="$stat['label']" :value="$stat['value']" :hint="$stat['hint']"
                :icon="$stat['icon']" :tone="$stat['tone']" />
        @endforeach
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm lg:col-span-2">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h3 class="text-sm font-semibold text-slate-900">Aktivitas Booking</h3>
                    <p class="text-xs text-slate-500">Jumlah booking lapangan yang masuk per hari</p>
                </div>

                <div class="flex rounded-lg bg-slate-100 p-1" data-chart-tabs>
                    <button type="button" data-chart-range="7"
                        class="chart-range rounded-md bg-white px-3 py-1.5 text-xs font-medium text-blue-700 shadow-sm transition">
                        7 Hari
                    </button>
                    <button type="button" data-chart-range="30"
                        class="chart-range rounded-md px-3 py-1.5 text-xs font-medium text-slate-600 transition">
                        30 Hari
                    </button>
                </div>
            </div>

            @php
                $maxValue = max((int) collect($chart7Hari)->max('value'), 1);
            @endphp

            <div class="mt-6">
                <div class="flex h-56 items-end gap-1.5 sm:gap-2" data-chart-bars>
                    @foreach ($chart7Hari as $point)
                        @php
                            $height = $point['value'] > 0 ? max(($point['value'] / $maxValue) * 100, 4) : 1.5;
                        @endphp

                        <div class="group relative flex h-full flex-1 items-end">
                            <div class="pointer-events-none absolute bottom-full left-1/2 z-10 mb-2 -translate-x-1/2 whitespace-nowrap rounded-lg bg-slate-900 px-2 py-1 text-[11px] font-medium text-white opacity-0 transition-opacity group-hover:opacity-100">
                                {{ $point['value'] }} booking &middot; {{ $point['date'] }}
                            </div>

                            <div class="w-full rounded-t-lg bg-gradient-to-t from-blue-600 to-blue-400 transition-colors group-hover:from-blue-700 group-hover:to-blue-500"
                                style="height: {{ $height }}%"></div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-2 flex gap-1.5 sm:gap-2" data-chart-labels>
                    @foreach ($chart7Hari as $point)
                        <span class="flex-1 truncate text-center text-[10px] text-slate-500 sm:text-[11px]">
                            {{ $point['label'] }}
                        </span>
                    @endforeach
                </div>
            </div>

            <script type="application/json" id="booking-chart-data">
                @json(['7' => $chart7Hari, '30' => $chart30Hari])
            </script>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <h3 class="text-sm font-semibold text-slate-900">Lapangan Terpopuler</h3>
            <p class="text-xs text-slate-500">Distribusi booking pada setiap lapangan</p>

            <div class="mt-6 flex justify-center">
                <div class="grid size-40 place-items-center rounded-full" style="background: {{ $lapanganPopuler['gradient'] }}">
                    <div class="grid size-28 place-items-center rounded-full bg-white text-center shadow-inner">
                        <div>
                            <p class="text-2xl font-semibold tracking-tight text-slate-900">
                                {{ number_format($lapanganPopuler['total']) }}
                            </p>
                            <p class="text-[11px] text-slate-500">Total Booking</p>
                        </div>
                    </div>
                </div>
            </div>

            <ul class="mt-6 space-y-3">
                @forelse ($lapanganPopuler['segments'] as $segment)
                    <li class="flex items-center gap-3 text-sm">
                        <span class="size-2.5 shrink-0 rounded-full" style="background-color: {{ $segment['color'] }}"></span>
                        <span class="min-w-0 flex-1 truncate text-slate-600">{{ $segment['label'] }}</span>
                        <span class="font-medium text-slate-900">{{ number_format($segment['value']) }}</span>
                        <span class="w-12 text-right text-xs text-slate-400">{{ number_format($segment['percent'], 1) }}%</span>
                    </li>
                @empty
                    <li class="py-6 text-center text-sm text-slate-400">Belum ada data booking lapangan</li>
                @endforelse
            </ul>
        </section>
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-2">
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <div>
                    <h3 class="text-sm font-semibold text-slate-900">Pertandingan Terakhir</h3>
                    <p class="text-xs text-slate-500">Hasil pertandingan liga internal</p>
                </div>

                <a href="{{ route('admin.pertandingan') }}"
                    class="inline-flex items-center gap-1 text-xs font-medium text-blue-600 transition-colors hover:text-blue-700">
                    Lihat semua
                    <x-admin.icon name="chevron-right" class="size-3.5" />
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[540px] text-left text-sm">
                    <thead class="bg-slate-50 text-[11px] uppercase tracking-wide text-slate-500">
                        <tr>
                            <th scope="col" class="px-5 py-3 font-medium">Tim</th>
                            <th scope="col" class="px-5 py-3 font-medium">Lawan</th>
                            <th scope="col" class="px-5 py-3 text-center font-medium">Skor</th>
                            <th scope="col" class="px-5 py-3 text-right font-medium">Waktu</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse ($pertandinganTerakhir as $match)
                            <tr class="transition-colors hover:bg-slate-50">
                                <td class="px-5 py-3 font-medium text-slate-800">{{ trim($match->team) }}</td>
                                <td class="px-5 py-3 text-slate-600">{{ trim($match->lawan) }}</td>
                                <td class="px-5 py-3 text-center">
                                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">
                                        {{ $match->score_team }}
                                        <span class="text-blue-300">-</span>
                                        {{ $match->score_lawan }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-right text-xs text-slate-500">
                                    {{ \Illuminate\Support\Carbon::parse($match->date)->translatedFormat('d M Y, H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-sm text-slate-400">
                                    Belum ada data pertandingan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <div>
                    <h3 class="text-sm font-semibold text-slate-900">Booking Terbaru</h3>
                    <p class="text-xs text-slate-500">Pemesanan lapangan yang masuk</p>
                </div>

                <a href="{{ route('admin.booking') }}"
                    class="inline-flex items-center gap-1 text-xs font-medium text-blue-600 transition-colors hover:text-blue-700">
                    Lihat semua
                    <x-admin.icon name="chevron-right" class="size-3.5" />
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[560px] text-left text-sm">
                    <thead class="bg-slate-50 text-[11px] uppercase tracking-wide text-slate-500">
                        <tr>
                            <th scope="col" class="px-5 py-3 font-medium">Pemesan</th>
                            <th scope="col" class="px-5 py-3 font-medium">Lapangan</th>
                            <th scope="col" class="px-5 py-3 font-medium">Jadwal</th>
                            <th scope="col" class="px-5 py-3 text-right font-medium">Status</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse ($bookingTerbaru as $booking)
                            <tr class="transition-colors hover:bg-slate-50">
                                <td class="px-5 py-3">
                                    <p class="font-medium text-slate-800">{{ $booking->user ?: 'Tanpa nama' }}</p>
                                    <p class="text-xs text-slate-400">{{ $booking->team ?: 'Tanpa tim' }}</p>
                                </td>
                                <td class="px-5 py-3 text-slate-600">{{ $booking->lapangan }}</td>
                                <td class="px-5 py-3 text-xs text-slate-500">
                                    <p>{{ $booking->tgl }}</p>
                                    <p class="text-slate-400">{{ $booking->jam_booking }}</p>
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <x-admin.badge :tone="$booking->status_tone">{{ $booking->status_label }}</x-admin.badge>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-sm text-slate-400">
                                    Belum ada data booking
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-3">
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm xl:col-span-2">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <div>
                    <h3 class="text-sm font-semibold text-slate-900">Pemain Terbaru</h3>
                    <p class="text-xs text-slate-500">Pemain yang baru didaftarkan</p>
                </div>

                <a href="{{ route('admin.pemain') }}"
                    class="inline-flex items-center gap-1 text-xs font-medium text-blue-600 transition-colors hover:text-blue-700">
                    Lihat semua
                    <x-admin.icon name="chevron-right" class="size-3.5" />
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[560px] text-left text-sm">
                    <thead class="bg-slate-50 text-[11px] uppercase tracking-wide text-slate-500">
                        <tr>
                            <th scope="col" class="px-5 py-3 font-medium">Pemain</th>
                            <th scope="col" class="px-5 py-3 font-medium">Posisi</th>
                            <th scope="col" class="px-5 py-3 text-center font-medium">No. Punggung</th>
                            <th scope="col" class="px-5 py-3 text-right font-medium">Terdaftar</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse ($pemainTerbaru as $pemain)
                            <tr class="transition-colors hover:bg-slate-50">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <span class="grid size-8 shrink-0 place-items-center rounded-lg bg-blue-50 text-xs font-semibold text-blue-600">
                                            {{ strtoupper(mb_substr($pemain->nama, 0, 1)) }}
                                        </span>
                                        <span class="font-medium text-slate-800">{{ $pemain->nama }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3">
                                    <x-admin.badge tone="indigo">{{ $pemain->posisi ?: 'Tidak diketahui' }}</x-admin.badge>
                                </td>
                                <td class="px-5 py-3 text-center font-medium text-slate-700">{{ $pemain->no_punggung ?: '-' }}</td>
                                <td class="px-5 py-3 text-right text-xs text-slate-500">
                                    {{ \Illuminate\Support\Carbon::parse($pemain->date)->translatedFormat('d M Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-sm text-slate-400">
                                    Belum ada data pemain
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <h3 class="text-sm font-semibold text-slate-900">Sesi Mendatang</h3>
            <p class="text-xs text-slate-500">Jadwal sesi permainan berikutnya</p>

            <ul class="mt-5 space-y-3">
                @forelse ($sesiMendatang as $sesi)
                    <li class="flex items-center gap-3 rounded-xl border border-slate-100 bg-slate-50/60 p-3 transition-colors hover:border-blue-100 hover:bg-blue-50/40">
                        <span class="grid size-10 shrink-0 place-items-center rounded-xl bg-blue-600 text-center text-white">
                            <span class="block text-[9px] uppercase leading-none">Sesi</span>
                            <span class="block text-sm font-semibold leading-tight">{{ $sesi->sesi }}</span>
                        </span>

                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-slate-800">
                                {{ trim($sesi->team) }} <span class="text-slate-400">vs</span> {{ trim($sesi->lawan) }}
                            </p>
                            <p class="mt-0.5 flex items-center gap-1.5 text-xs text-slate-500">
                                <x-admin.icon name="calendar-days" class="size-3.5" />
                                {{ $sesi->tgl_main }} &middot; {{ $sesi->jam_mulai }} - {{ $sesi->jam_selesai }}
                            </p>
                        </div>
                    </li>
                @empty
                    <li class="rounded-xl border border-dashed border-slate-200 py-8 text-center text-sm text-slate-400">
                        Belum ada sesi terjadwal
                    </li>
                @endforelse
            </ul>
        </section>
    </div>
@endsection
