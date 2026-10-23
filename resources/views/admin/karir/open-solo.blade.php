@extends('layouts.admin')

@section('title', 'Open Member Karir Solo')
@section('page-title', 'Member Karir')
@section('page-subtitle', 'Open Member Karir Solo')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <nav class="flex items-center gap-2 text-sm text-slate-500">
            <span class="font-medium text-slate-700">Member Karir</span>
            <x-admin.icon name="chevron-right" class="size-3.5 text-slate-300" />
            <span class="text-slate-500">Open Member Karir Solo</span>
        </nav>

        <div class="flex flex-col gap-3 sm:flex-row">
            <form method="GET" action="{{ route('admin.karir.open-solo') }}" class="flex gap-2">
                <label for="search-open-solo" class="sr-only">Cari kode main, tanggal, jam, atau lapangan</label>
                <div class="relative">
                    <x-admin.icon name="search" class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-slate-400" />
                    <input id="search-open-solo" type="search" name="q" value="{{ $search }}" placeholder="Cari kode, tanggal, lapangan..."
                        class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-9 pr-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100 sm:w-64">
                </div>
                <button type="submit" class="rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Cari</button>
                @if ($search !== '')
                    <a href="{{ route('admin.karir.open-solo') }}" class="rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-500 hover:bg-slate-50">Reset</a>
                @endif
            </form>
            <button type="button" data-modal-open="modal-tambah-solo"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-medium text-white shadow-lg shadow-blue-600/25 transition-colors hover:bg-blue-700">
                <x-admin.icon name="plus" class="size-4" />
                Buka Game
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
                <h3 class="text-sm font-semibold text-slate-900">Jadwal Open Member Karir Solo</h3>
                <p class="text-xs text-slate-500">Data diambil dari tabel tbl_fungame</p>
            </div>

            <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                {{ $game->count() }} game
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[880px] text-left text-sm">
                <thead class="bg-slate-50 text-[11px] uppercase tracking-wide text-slate-500">
                    <tr>
                        <th scope="col" class="px-5 py-3 font-medium">Kode Main</th>
                        <th scope="col" class="px-5 py-3 font-medium">Tgl Main</th>
                        <th scope="col" class="px-5 py-3 font-medium">Jam Main</th>
                        <th scope="col" class="px-5 py-3 text-center font-medium">Jml Pemain</th>
                        <th scope="col" class="px-5 py-3 text-right font-medium">Harga</th>
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
                            <td class="px-5 py-3 text-center text-slate-700">{{ $item->jml_pemain }}</td>
                            <td class="px-5 py-3 text-right text-slate-700">Rp {{ number_format((float) $item->harga, 0, ',', '.') }}</td>
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

                                    <form method="POST" action="{{ route('admin.karir.open-solo.toggle', $item) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="inline-flex items-center rounded-lg bg-amber-50 px-2.5 py-1.5 text-xs font-medium text-amber-700 transition-colors hover:bg-amber-100">
                                            {{ $item->isAktif() ? 'Tutup' : 'Buka' }}
                                        </button>
                                    </form>

                                    <button type="button" data-modal-open="modal-edit-solo-{{ $item->id }}"
                                        class="inline-flex items-center rounded-lg bg-slate-100 px-2.5 py-1.5 text-xs font-medium text-slate-700 transition-colors hover:bg-slate-200">
                                        Edit
                                    </button>

                                    <button type="button" data-modal-open="modal-hapus-solo-{{ $item->id }}"
                                        class="inline-flex items-center rounded-lg bg-rose-50 px-2.5 py-1.5 text-xs font-medium text-rose-700 transition-colors hover:bg-rose-100">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <x-admin.modal id="modal-edit-solo-{{ $item->id }}" title="Edit Game">
                            <form method="POST" action="{{ route('admin.karir.open-solo.update', $item) }}" class="space-y-4">
                                @csrf
                                @method('PUT')

                                <input type="hidden" name="kode_main" value="{{ $item->kode_main }}">

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
                                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Jml Pemain</label>
                                        <input type="number" name="jml_pemain" value="{{ $item->jml_pemain }}" min="1" required
                                            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                                    </div>
                                    <div>
                                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Harga</label>
                                        <input type="number" name="harga" value="{{ $item->harga }}" min="0" required
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
                                        Update
                                    </button>
                                </div>
                            </form>
                        </x-admin.modal>

                        <x-admin.modal id="modal-hapus-solo-{{ $item->id }}" title="Hapus Game">
                            <p class="text-sm text-slate-600">Apakah anda ingin menghapus data ini?</p>
                            <form method="POST" action="{{ route('admin.karir.open-solo.destroy', $item) }}" class="mt-6 flex justify-end gap-2">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="kode_main" value="{{ $item->kode_main }}">
                                <button type="button" data-modal-close
                                    class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50">
                                    Close
                                </button>
                                <button type="submit"
                                    class="rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-medium text-white shadow-lg shadow-rose-600/25 transition-colors hover:bg-rose-700">
                                    Yes
                                </button>
                            </form>
                        </x-admin.modal>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-8 text-center text-sm text-slate-400">
                                Belum ada game dibuka
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <x-admin.modal id="modal-tambah-solo" title="Buka Game Member Karir Solo">
        <form method="POST" action="{{ route('admin.karir.open-solo.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Tanggal Main</label>
                <input type="date" name="tgl_main" required
                    class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Jam Mulai</label>
                    <select name="jam_mulai" required
                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                        <option value="">-- Pilih --</option>
                        @foreach ($jamOptions as $jam)
                            <option value="{{ $jam }}">{{ $jam }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Jam Selesai</label>
                    <select name="jam_selesai" required
                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                        <option value="">-- Pilih --</option>
                        @foreach ($jamOptions as $jam)
                            <option value="{{ $jam }}">{{ $jam }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Lapangan</label>
                <select name="lapangan" required
                    class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                    <option value="">Pilih lapangan</option>
                    @foreach ($lapangan as $lap)
                        <option value="{{ $lap->id }}">{{ $lap->lapangan }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Jml Pemain</label>
                    <input type="number" name="jml_pemain" value="7" required
                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Harga</label>
                    <input type="number" name="harga" required
                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                </div>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Open Sesi</label>
                <select name="open_sesi"
                    class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                    <option value="0">None</option>
                    <option value="2">Buka Sesi 1</option>
                    <option value="1">Buka Sesi 2</option>
                </select>
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
    </script>
@endpush
