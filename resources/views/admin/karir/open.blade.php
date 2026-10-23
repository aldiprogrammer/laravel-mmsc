@extends('layouts.admin')

@section('title', 'Open Member Karir Team')
@section('page-title', 'Member Karir')
@section('page-subtitle', 'Open Member Karir Team')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <nav class="flex items-center gap-2 text-sm text-slate-500">
            <span class="font-medium text-slate-700">Member Karir</span>
            <x-admin.icon name="chevron-right" class="size-3.5 text-slate-300" />
            <span class="text-slate-500">Open Member Karir Team</span>
        </nav>

        <div class="flex flex-col gap-3 sm:flex-row">
            <form method="GET" action="{{ route('admin.karir.open.index') }}" class="flex gap-2">
                <label for="search-open-team" class="sr-only">Cari kode main, tanggal, jam, atau lapangan</label>
                <div class="relative">
                    <x-admin.icon name="search" class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-slate-400" />
                    <input id="search-open-team" type="search" name="q" value="{{ $search }}" placeholder="Cari kode, tanggal, lapangan..."
                        class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-9 pr-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100 sm:w-64">
                </div>
                <button type="submit" class="rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Cari</button>
                @if ($search !== '')
                    <a href="{{ route('admin.karir.open.index') }}" class="rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-500 hover:bg-slate-50">Reset</a>
                @endif
            </form>
            <button type="button" data-modal-open="modal-tambah"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-medium text-white shadow-lg shadow-blue-600/25 transition-colors hover:bg-blue-700">
                <x-admin.icon name="plus" class="size-4" />
                Tambah Jadwal
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            <x-admin.icon name="check-circle" class="size-5 shrink-0" />
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 flex items-center gap-2 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
            <x-admin.icon name="exclamation-circle" class="size-5 shrink-0" />
            {{ session('error') }}
        </div>
    @endif

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
            <div>
                <h3 class="text-sm font-semibold text-slate-900">Jadwal Open Member Karir Team</h3>
                <p class="text-xs text-slate-500">Data diambil dari tabel tbl_fungame_rankteam</p>
            </div>

            <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                {{ $game->count() }} jadwal
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[1080px] text-left text-sm">
                <thead class="bg-slate-50 text-[11px] uppercase tracking-wide text-slate-500">
                    <tr>
                        <th scope="col" class="px-5 py-3 font-medium">Kode Main</th>
                        <th scope="col" class="px-5 py-3 font-medium">Tgl Main</th>
                        <th scope="col" class="px-5 py-3 font-medium">Jam Main</th>
                        <th scope="col" class="px-5 py-3 text-center font-medium">Jml Team</th>
                        <th scope="col" class="px-5 py-3 text-right font-medium">Harga Sesi 1</th>
                        <th scope="col" class="px-5 py-3 text-right font-medium">Harga Sesi 2</th>
                        <th scope="col" class="px-5 py-3 font-medium">Lapangan</th>
                        <th scope="col" class="px-5 py-3 text-center font-medium">Status</th>
                        <th scope="col" class="px-5 py-3 text-right font-medium">Opsi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($game as $item)
                        <tr class="transition-colors hover:bg-slate-50">
                            <td class="px-5 py-3 font-mono text-xs text-slate-600">{{ $item->kode_main }}</td>
                            <td class="px-5 py-3 text-slate-700">{{ $item->tgl_main }}</td>
                            <td class="px-5 py-3 text-slate-700">{{ $item->jam_main }}</td>
                            <td class="px-5 py-3 text-center text-slate-700">{{ $item->jml_team }}</td>
                            <td class="px-5 py-3 text-right text-slate-700">Rp {{ number_format((float) $item->harga, 0, ',', '.') }}</td>
                            <td class="px-5 py-3 text-right text-slate-700">
                                Rp {{ number_format((float) ($item->harga2 !== '' ? $item->harga2 : $item->harga), 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-3 text-slate-600">{{ $item->lapangan }}</td>
                            <td class="px-5 py-3 text-center">
                                @if ($item->isAktif())
                                    <x-admin.badge tone="emerald">Aktif</x-admin.badge>
                                @else
                                    <x-admin.badge tone="rose">Tidak Aktif</x-admin.badge>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex flex-wrap items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.karir.open.detail', ['kode' => $item->kode_main]) }}"
                                        class="inline-flex items-center rounded-lg bg-blue-50 px-2.5 py-1.5 text-xs font-medium text-blue-700 transition-colors hover:bg-blue-100">
                                        Detail
                                    </a>
                                    <a href="{{ route('admin.karir.open.point', ['kode_main' => $item->kode_main]) }}"
                                        class="inline-flex items-center rounded-lg bg-indigo-50 px-2.5 py-1.5 text-xs font-medium text-indigo-700 transition-colors hover:bg-indigo-100">
                                        Point
                                    </a>
                                    <a href="{{ route('admin.karir.open.hasil', ['kode' => $item->kode_main]) }}"
                                        class="inline-flex items-center rounded-lg bg-sky-50 px-2.5 py-1.5 text-xs font-medium text-sky-700 transition-colors hover:bg-sky-100">
                                        Hasil
                                    </a>
                                    <a href="{{ route('admin.karir.open.absensi', ['kode_main' => $item->kode_main]) }}"
                                        class="inline-flex items-center rounded-lg bg-cyan-50 px-2.5 py-1.5 text-xs font-medium text-cyan-700 transition-colors hover:bg-cyan-100">
                                        Absensi
                                    </a>

                                    <form method="POST" action="{{ route('admin.karir.open.toggle', $item) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="inline-flex items-center rounded-lg bg-amber-50 px-2.5 py-1.5 text-xs font-medium text-amber-700 transition-colors hover:bg-amber-100">
                                            {{ $item->isAktif() ? 'Tutup' : 'Buka' }}
                                        </button>
                                    </form>

                                    <button type="button" data-modal-open="modal-edit-{{ $item->id }}"
                                        class="inline-flex items-center rounded-lg bg-slate-100 px-2.5 py-1.5 text-xs font-medium text-slate-700 transition-colors hover:bg-slate-200">
                                        Edit
                                    </button>

                                    <button type="button" data-modal-open="modal-hapus-{{ $item->id }}"
                                        class="inline-flex items-center rounded-lg bg-rose-50 px-2.5 py-1.5 text-xs font-medium text-rose-700 transition-colors hover:bg-rose-100">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-5 py-8 text-center text-sm text-slate-400">
                                Belum ada jadwal open member karir team
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <x-admin.modal id="modal-tambah" title="Tambah Jadwal Member Karir Team">
        <form method="POST" action="{{ route('admin.karir.open.store') }}" class="space-y-4" data-form-cek>
            @csrf

            <div>
                <label for="tgl_main" class="mb-1.5 block text-sm font-medium text-slate-700">Tanggal Main</label>
                <input type="date" name="tgl_main" id="tgl_main" value="{{ old('tgl_main') }}" required
                    data-cek-field
                    class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="jam_mulai" class="mb-1.5 block text-sm font-medium text-slate-700">Jam Mulai</label>
                    <select name="jam_mulai" id="jam_mulai" required data-cek-field
                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                        <option value="">-- Pilih jam mulai --</option>
                        @foreach ($jamOptions as $jam)
                            <option value="{{ $jam }}" @selected(old('jam_mulai') === $jam)>{{ $jam }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="jam_selesai" class="mb-1.5 block text-sm font-medium text-slate-700">Jam Selesai</label>
                    <select name="jam_selesai" id="jam_selesai" required data-cek-field
                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                        <option value="">-- Pilih jam selesai --</option>
                        @foreach ($jamOptions as $jam)
                            <option value="{{ $jam }}" @selected(old('jam_selesai') === $jam)>{{ $jam }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label for="lapangan" class="mb-1.5 block text-sm font-medium text-slate-700">Lapangan</label>
                <select name="lapangan" id="lapangan" required data-cek-field
                    class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                    <option value="">Pilih lapangan</option>
                    @foreach ($lapangan as $lap)
                        <option value="{{ $lap->id }}" @selected(old('lapangan') == $lap->id)>{{ $lap->lapangan }}</option>
                    @endforeach
                </select>
            </div>

            <div data-cek-result
                class="hidden rounded-xl border px-3 py-2.5 text-xs font-medium"></div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="jml_team" class="mb-1.5 block text-sm font-medium text-slate-700">Jml Team</label>
                    <input type="number" name="jml_team" id="jml_team" value="{{ old('jml_team', 4) }}" min="1" required
                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                </div>

                <div>
                    <label for="open_sesi" class="mb-1.5 block text-sm font-medium text-slate-700">Open Sesi</label>
                    <select name="open_sesi" id="open_sesi" required
                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                        <option value="">-- Pilih open sesi --</option>
                        <option value="All" @selected(old('open_sesi') === 'All')>All</option>
                        <option value="Sesi 1" @selected(old('open_sesi') === 'Sesi 1')>Sesi 1</option>
                        <option value="Sesi 2" @selected(old('open_sesi') === 'Sesi 2')>Sesi 2</option>
                    </select>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="harga" class="mb-1.5 block text-sm font-medium text-slate-700">Harga Sesi 1</label>
                    <input type="number" name="harga" id="harga" value="{{ old('harga') }}" min="0" required
                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                </div>

                <div>
                    <label for="harga2" class="mb-1.5 block text-sm font-medium text-slate-700">Harga Sesi 2</label>
                    <input type="number" name="harga2" id="harga2" value="{{ old('harga2') }}" min="0" required
                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" data-modal-close
                    class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50">
                    Close
                </button>
                <button type="submit"
                    class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-medium text-white shadow-lg shadow-blue-600/25 transition-colors hover:bg-blue-700">
                    Submit
                </button>
            </div>
        </form>
    </x-admin.modal>

    @foreach ($game as $item)
        <x-admin.modal id="modal-edit-{{ $item->id }}" title="Edit Jadwal">
            <form method="POST" action="{{ route('admin.karir.open.update', $item) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Tanggal Main</label>
                    <input type="date" name="tgl_main" value="{{ $item->tgl_main }}" required
                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Jam Mulai</label>
                        <select name="jam_mulai" required
                            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                            <option value="{{ $item->jam_mulai }}">{{ $item->jam_mulai }}</option>
                            @foreach ($jamOptions as $jam)
                                @if ($jam !== $item->jam_mulai)
                                    <option value="{{ $jam }}">{{ $jam }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Jam Selesai</label>
                        <select name="jam_selesai" required
                            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                            <option value="{{ $item->jam_selesai }}">{{ $item->jam_selesai }}</option>
                            @foreach ($jamOptions as $jam)
                                @if ($jam !== $item->jam_selesai)
                                    <option value="{{ $jam }}">{{ $jam }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Lapangan</label>
                    <select name="lapangan" required
                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                        <option value="{{ $item->id_lapangan }}">{{ $item->lapangan }}</option>
                        @foreach ($lapangan as $lap)
                            @if ((string) $lap->id !== (string) $item->id_lapangan)
                                <option value="{{ $lap->id }}">{{ $lap->lapangan }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Jml Team</label>
                        <input type="number" name="jml_team" value="{{ $item->jml_team }}" min="1" required
                            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Harga Sesi 1</label>
                        <input type="number" name="harga" value="{{ $item->harga }}" min="0" required
                            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                    </div>
                </div>

                     <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Harga Sesi 2</label>
                        <input type="number" name="harga2" value="{{ $item->harga2 }}" min="1" required
                            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                    </div>

                    <div>
                    
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" data-modal-close
                        class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50">
                        Close
                    </button>
                    <button type="submit"
                        class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-medium text-white shadow-lg shadow-blue-600/25 transition-colors hover:bg-blue-700">
                        Update
                    </button>
                </div>
            </form>
        </x-admin.modal>

        <x-admin.modal id="modal-hapus-{{ $item->id }}" title="Hapus Jadwal">
            <p class="text-sm text-slate-600">Apakah anda ingin menghapus jadwal <span class="font-medium text-slate-800">{{ $item->kode_main }}</span>?</p>
            <form method="POST" action="{{ route('admin.karir.open.destroy', $item) }}" class="mt-6 flex justify-end gap-2">
                @csrf
                @method('DELETE')
                <button type="button" data-modal-close
                    class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50">
                    Close
                </button>
                <button type="submit"
                    class="rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-medium text-white shadow-lg shadow-rose-600/25 transition-colors hover:bg-rose-700">
                    Hapus
                </button>
            </form>
        </x-admin.modal>
    @endforeach
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('[data-modal-open]').forEach((button) => {
            button.addEventListener('click', () => {
                const modal = document.getElementById(button.dataset.modalOpen);
                modal?.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            });
        });

        document.querySelectorAll('[data-modal-close]').forEach((button) => {
            button.addEventListener('click', () => {
                button.closest('[data-modal]')?.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            });
        });

        document.querySelectorAll('[data-modal]').forEach((modal) => {
            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    modal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }
            });
        });

        const cekForm = document.querySelector('[data-form-cek]');
        const cekResult = cekForm?.querySelector('[data-cek-result]');
        const cekFields = cekForm ? Array.from(cekForm.querySelectorAll('[data-cek-field]')) : [];

        const cekBooking = async () => {
            if (!cekForm || !cekResult) {
                return;
            }

            const payload = new URLSearchParams({
                tgl_main: cekForm.querySelector('[name="tgl_main"]')?.value ?? '',
                jam_mulai: cekForm.querySelector('[name="jam_mulai"]')?.value ?? '',
                jam_selesai: cekForm.querySelector('[name="jam_selesai"]')?.value ?? '',
                lapangan: cekForm.querySelector('[name="lapangan"]')?.value ?? '',
            });

            try {
                const response = await fetch(`{{ route('admin.karir.open.cek-booking') }}?${payload.toString()}`, {
                    headers: { Accept: 'application/json' },
                });
                const data = await response.json();

                if (!data.message) {
                    cekResult.className = 'hidden rounded-xl border px-3 py-2.5 text-xs font-medium';
                    cekResult.textContent = '';

                    return;
                }

                const tone = data.status === 'booked'
                    ? 'border-rose-200 bg-rose-50 text-rose-700'
                    : 'border-emerald-200 bg-emerald-50 text-emerald-700';

                cekResult.className = `rounded-xl border px-3 py-2.5 text-xs font-medium ${tone}`;
                cekResult.textContent = data.message;
            } catch (error) {
                cekResult.className = 'hidden rounded-xl border px-3 py-2.5 text-xs font-medium';
            }
        };

        cekFields.forEach((field) => {
            field.addEventListener('change', cekBooking);
            field.addEventListener('input', cekBooking);
        });
    </script>
@endpush
