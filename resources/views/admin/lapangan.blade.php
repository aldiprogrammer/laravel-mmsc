@extends('layouts.admin')

@section('title', 'Lapangan')
@section('page-title', 'Lapangan')
@section('page-subtitle', 'Daftar lapangan '.config('app.name', 'MMSC'))

@section('content')
    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-5 py-4">
            <div>
                <h3 class="text-sm font-semibold text-slate-900">Data Lapangan</h3>
                <p class="text-xs text-slate-500">Diambil dari tabel tbl_lapangan</p>
            </div>

            <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
               
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[720px] text-left text-sm">
                <thead class="bg-slate-50 text-[11px] uppercase tracking-wide text-slate-500">
                    <tr>
                        <th scope="col" class="px-5 py-3 font-medium">Lapangan</th>
                        <th scope="col" class="px-5 py-3 font-medium">Fasilitas</th>
                        <th scope="col" class="px-5 py-3 text-center font-medium">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($lapanganList as $item)
                        <tr class="transition-colors hover:bg-slate-50">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    @if ($item->gambar)
                                        <img src="{{ $item->gambar }}" alt="{{ $item->lapangan }}"
                                            class="size-12 shrink-0 rounded-xl object-cover ring-1 ring-slate-200">
                                    @else
                                        <span class="grid size-12 shrink-0 place-items-center rounded-xl bg-blue-50 text-blue-600">
                                            <x-admin.icon name="map-pin" class="size-5" />
                                        </span>
                                    @endif

                                    <div class="min-w-0">
                                        <p class="font-medium text-slate-800">{{ $item->lapangan }}</p>
                                        <p class="truncate text-xs text-slate-400">{{ $item->slug }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-slate-600">
                                <p class="max-w-md truncate">{{ $item->pasilitas ?: '-' }}</p>
                            </td>
                            <td class="px-5 py-3 text-center">
                                @if ($item->status_booking === '0')
                                    <x-admin.badge tone="emerald">Siap Booking</x-admin.badge>
                                @else
                                    <x-admin.badge tone="amber">Sedang Dibooking</x-admin.badge>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-5 py-8 text-center text-sm text-slate-400">
                                Belum ada data lapangan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
