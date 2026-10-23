@extends('layouts.admin')

@section('title', 'Data Booking Lapangan')
@section('page-title', 'Data Booking Lapangan')
@section('page-subtitle', 'Daftar booking lapangan dan status pembayarannya')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <nav class="mb-2 flex items-center gap-2 text-sm text-slate-500">
                <span class="font-medium text-slate-700">Booking</span>
                <x-admin.icon name="chevron-right" class="size-3.5 text-slate-300" />
                <span>Data Booking Lapangan</span>
            </nav>
            <p class="text-sm text-slate-500">Cari berdasarkan nama team dan tanggal booking.</p>
        </div>

        <form method="GET" action="{{ route('admin.booking') }}" class="flex flex-col gap-2 sm:flex-row">
            <label for="cari-booking" class="sr-only">Cari nama team atau tanggal booking</label>
            <input id="cari-booking" type="search" name="cari" value="{{ $search }}"
                placeholder="Nama team atau tanggal booking" required
                class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 shadow-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100 sm:w-72" />
            <div class="flex gap-2">
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition-colors hover:bg-emerald-700">
                    <x-admin.icon name="search" class="size-4" />
                    Search
                </button>
                <a href="{{ route('admin.booking') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 transition-colors hover:bg-slate-50">
                    Refresh
                </a>
            </div>
        </form>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <section class="overflow-hidden rounded-2xl border border-rose-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
            <div>
                <h2 class="text-sm font-semibold text-slate-900">Data Booking Lapangan</h2>
                <p class="text-xs text-slate-500">Data booking dan pembayaran ditampilkan berdasarkan kode booking.</p>
            </div>
            <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                {{ $bookings->total() }} data
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[1700px] border-collapse text-left text-sm">
                <thead class="bg-slate-50 text-[11px] uppercase tracking-wide text-slate-500">
                    <tr>
                        <th scope="col" class="border border-slate-200 px-3 py-3 font-medium">No</th>
                        <th scope="col" class="border border-slate-200 px-3 py-3 font-medium">Kode</th>
                        <th scope="col" class="border border-slate-200 px-3 py-3 font-medium">User</th>
                        <th scope="col" class="border border-slate-200 px-3 py-3 font-medium">Team</th>
                        <th scope="col" class="border border-slate-200 px-3 py-3 font-medium">Lapangan</th>
                        <th scope="col" class="border border-slate-200 px-3 py-3 font-medium">Jam Booking</th>
                        <th scope="col" class="border border-slate-200 px-3 py-3 font-medium">Tgl Main</th>
                        <th scope="col" class="border border-slate-200 px-3 py-3 font-medium">Harga</th>

                        <th scope="col" class="border border-slate-200 px-3 py-3 font-medium">Pembayaran</th>
                        <th scope="col" class="border border-slate-200 px-3 py-3 font-medium">Status</th>
                        <th scope="col" class="border border-slate-200 px-3 py-3 font-medium">Bukti</th>
                        <th scope="col" class="border border-slate-200 px-3 py-3 font-medium">Bukti Pelunasan</th>
                        <th scope="col" class="border border-slate-200 px-3 py-3 font-medium">Atas Nama</th>
                        <th scope="col" class="border border-slate-200 px-3 py-3 font-medium">No WA</th>
                        <th scope="col" class="border border-slate-200 px-3 py-3 font-medium">TGL Booking</th>

                        <th scope="col" class="border border-slate-200 px-3 py-3 font-medium">Date</th>
                        <th scope="col" class="border border-slate-200 px-3 py-3 text-center font-medium">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($bookings as $booking)
                        @php
                            $payment = $booking->pembayaran;
                            $settlement = $booking->pelunasan;
                            $fieldTone = match ((int) $booking->id_lapangan) {
                                5 => 'bg-rose-600',
                                1 => 'bg-blue-600',
                                default => 'bg-slate-900',
                            };
                            $fieldName = match ((int) $booking->id_lapangan) {
                                5 => 'Red Tiger',
                                1 => 'Blue Dragon',
                                default => 'White Rat',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50">
                            <td class="border border-slate-200 px-3 py-3 text-center text-slate-500">{{ $bookings->firstItem() + $loop->index }}</td>
                            <td class="border border-slate-200 px-3 py-3 font-mono text-xs text-slate-600">{{ $booking->kode_booking }}</td>
                            <td class="border border-slate-200 px-3 py-3 text-slate-700">{{ $bookingUsers[$booking->iduser] ?? $booking->user ?? '-' }}</td>
                            <td class="border border-slate-200 px-3 py-3 font-medium text-slate-800">{{ $booking->team ?: '-' }}</td>
                            <td class="border border-slate-200 px-3 py-3">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium text-white {{ $fieldTone }}">{{ $fieldName }}</span>
                                @if ($booking->booking === 'spesial')
                                    <p class="mt-1 text-xs font-semibold text-rose-600">Booking spesial</p>
                                @endif
                                @if ($payment && (float) $payment->harga !== (float) $payment->total_harga)
                                    <div class="mt-1 space-y-0.5 text-xs font-medium text-emerald-700">
                                        @if ($payment->wasit) <p>Wasit</p> @endif
                                        @if ($payment->bola) <p>Bola</p> @endif
                                        @if ($payment->rompi) <p>Rompi</p> @endif
                                        @if ($payment->video) <p>Videografer</p> @endif
                                        @if ($payment->poto) <p>Fotografer</p> @endif
                                    </div>
                                @endif
                            </td>
                            <td class="border border-slate-200 px-3 py-3 text-slate-700">{{ $booking->jam_booking ?: '-' }}</td>
                            <td class="border border-slate-200 px-3 py-3 text-slate-700">{{ $booking->tgl ?: '-' }}</td>
                            <td class="border border-slate-200 px-3 py-3 text-slate-700">{{ number_format($payment->harga, 0, ',', '.')  ?: '-' }}</td>

                            <td class="border border-slate-200 px-3 py-3 text-slate-700">{{ $booking->sistem_pembayaran ?: '-' }}</td>
                            <td class="border border-slate-200 px-3 py-3">
                                <x-admin.badge :tone="$booking->status_pembayaran_tone">{{ $booking->status_pembayaran_label }}</x-admin.badge>
                                @if ((string) $booking->status_pelunasan === '400')
                                    <p class="mt-1 text-xs text-amber-700">Pelunasan ditolak</p>
                                @endif
                            </td>
                            <td class="border border-slate-200 px-3 py-3">
                                @if ($payment?->bukti)
                                    <a href="{{ $payment->bukti }}" target="_blank" rel="noopener" class="text-xs font-medium text-blue-600 hover:text-blue-700">Lihat Bukti</a>
                                @else
                                    <span class="text-xs text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="border border-slate-200 px-3 py-3">
                                @if ($settlement?->bukti)
                                    <a href="{{ $settlement->bukti }}" target="_blank" rel="noopener" class="text-xs font-medium text-blue-600 hover:text-blue-700">Lihat Bukti</a>
                                    <p class="mt-1 text-xs {{ (string) $settlement->status === '200' ? 'text-emerald-600' : ((string) $settlement->status === '400' ? 'text-rose-600' : 'text-amber-600') }}">
                                        {{ (string) $settlement->status === '200' ? 'Disetujui' : ((string) $settlement->status === '400' ? 'Ditolak' : 'Menunggu') }}
                                    </p>
                                @else
                                    <span class="text-xs text-slate-400">Tidak ada</span>
                                @endif
                            </td>
                            <td class="border border-slate-200 px-3 py-3 text-slate-700">{{ $payment?->atasnama ?: '-' }}</td>
                            <td class="border border-slate-200 px-3 py-3 text-slate-700">{{ $payment?->wa ?: '-' }}</td>
                            <td class="border border-slate-200 px-3 py-3 text-slate-700">{{ $booking?->tgl_booking ?: '-' }}</td>

                            <td class="border border-slate-200 px-3 py-3 text-slate-600">{{ $payment?->date ?: '-' }}</td>
                            <td class="border border-slate-200 px-3 py-3">
                                <div class="flex min-w-24 flex-col items-stretch gap-1.5">
                                    @if ((string) $booking->status_pembayaran !== '200' || (string) $booking->status_pelunasan !== '0')
                                        <button type="button" data-modal-open="booking-approve-{{ $booking->id }}"
                                            class="rounded-lg bg-blue-50 px-2.5 py-1.5 text-xs font-medium text-blue-700 transition-colors hover:bg-blue-100">
                                            Setujui
                                        </button>
                                    @endif
                                    <button type="button" data-modal-open="booking-complete-{{ $booking->id }}"
                                        class="rounded-lg bg-emerald-50 px-2.5 py-1.5 text-xs font-medium text-emerald-700 transition-colors hover:bg-emerald-100">
                                        Lunas
                                    </button>
                                    <button type="button" data-modal-open="booking-reject-{{ $booking->id }}"
                                        class="rounded-lg bg-rose-50 px-2.5 py-1.5 text-xs font-medium text-rose-700 transition-colors hover:bg-rose-100">
                                        Tolak
                                    </button>
                                    <button type="button" data-modal-open="booking-detail-{{ $booking->id }}"
                                        class="rounded-lg bg-slate-100 px-2.5 py-1.5 text-xs font-medium text-slate-700 transition-colors hover:bg-slate-200">
                                        Detail
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <x-admin.modal id="booking-approve-{{ $booking->id }}" title="Setujui Booking">
                            <p class="text-sm text-slate-600">Setujui booking <span class="font-semibold text-slate-800">{{ $booking->kode_booking }}</span>?</p>
                            <form method="POST" action="{{ route('admin.booking.approve', $booking) }}" class="mt-6 flex justify-end gap-2">
                                @csrf
                                <button type="button" data-modal-close class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Batal</button>
                                <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700">Setujui</button>
                            </form>
                        </x-admin.modal>

                        <x-admin.modal id="booking-complete-{{ $booking->id }}" title="Tandai Lunas">
                            <p class="text-sm text-slate-600">Tandai booking <span class="font-semibold text-slate-800">{{ $booking->kode_booking }}</span> sudah lunas?</p>
                            <form method="POST" action="{{ route('admin.booking.complete', $booking) }}" class="mt-6 flex justify-end gap-2">
                                @csrf
                                <button type="button" data-modal-close class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Batal</button>
                                <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-emerald-700">Ya, Lunas</button>
                            </form>
                        </x-admin.modal>

                        <x-admin.modal id="booking-reject-{{ $booking->id }}" title="Tolak Booking">
                            <p class="text-sm text-slate-600">Tolak booking <span class="font-semibold text-slate-800">{{ $booking->kode_booking }}</span>?</p>
                            <form method="POST" action="{{ route('admin.booking.reject', $booking) }}" class="mt-6 flex justify-end gap-2">
                                @csrf
                                <button type="button" data-modal-close class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Batal</button>
                                <button type="submit" class="rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-rose-700">Tolak Booking</button>
                            </form>
                        </x-admin.modal>

                        <x-admin.modal id="booking-detail-{{ $booking->id }}" title="Detail Booking {{ $booking->kode_booking }}">
                            <dl class="grid gap-4 text-sm sm:grid-cols-2">
                                <div><dt class="text-xs text-slate-500">Pemesan</dt><dd class="mt-1 font-medium text-slate-800">{{ $bookingUsers[$booking->iduser] ?? $booking->user ?? '-' }}</dd></div>
                                <div><dt class="text-xs text-slate-500">Team</dt><dd class="mt-1 font-medium text-slate-800">{{ $booking->team ?: '-' }}</dd></div>
                                <div><dt class="text-xs text-slate-500">Lapangan</dt><dd class="mt-1 font-medium text-slate-800">{{ $booking->lapangan ?: $fieldName }}</dd></div>
                                <div><dt class="text-xs text-slate-500">Tanggal &amp; Jam</dt><dd class="mt-1 font-medium text-slate-800">{{ $booking->tgl ?: '-' }} · {{ $booking->jam_booking ?: '-' }}</dd></div>
                                <div><dt class="text-xs text-slate-500">Sistem Pembayaran</dt><dd class="mt-1 font-medium text-slate-800">{{ $booking->sistem_pembayaran ?: '-' }}</dd></div>
                                <div><dt class="text-xs text-slate-500">Total Harga</dt><dd class="mt-1 font-medium text-slate-800">{{ $payment && is_numeric($payment->total_harga) ? 'Rp '.number_format((float) $payment->total_harga, 0, ',', '.') : '-' }}</dd></div>
                                <div><dt class="text-xs text-slate-500">Status Booking</dt><dd class="mt-1 font-medium text-slate-800">{{ $booking->status_pembayaran_label }}</dd></div>
                                <div><dt class="text-xs text-slate-500">Status Pelunasan</dt><dd class="mt-1 font-medium text-slate-800">{{ $settlement ? ((string) $settlement->status === '200' ? 'Disetujui' : ((string) $settlement->status === '400' ? 'Ditolak' : 'Menunggu')) : 'Belum ada data' }}</dd></div>
                            </dl>
                        </x-admin.modal>
                    @empty
                        <tr>
                            <td colspan="15" class="px-5 py-10 text-center text-sm text-slate-400">Tidak ada data booking yang cocok.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($bookings->hasPages())
            <div class="border-t border-slate-100 px-5 py-4">
                {{ $bookings->links() }}
            </div>
        @endif
    </section>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('[data-modal-open]').forEach((button) => {
            button.addEventListener('click', () => {
                document.getElementById(button.dataset.modalOpen)?.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            });
        });

        document.querySelectorAll('[data-modal-close]').forEach((button) => {
            button.addEventListener('click', () => {
                button.closest('[data-modal]')?.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            });
        });
    </script>
@endpush
