@extends('layouts.admin')

@section('title', 'Data Wasit')
@section('page-title', 'Data Wasit')
@section('page-subtitle', 'Kelola data dan status wasit')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <nav class="flex items-center gap-2 text-sm text-slate-500">
            <span class="font-medium text-slate-700">Data</span>
            <x-admin.icon name="chevron-right" class="size-3.5 text-slate-300" />
            <span>Wasit</span>
        </nav>

        <div class="flex flex-col gap-3 sm:flex-row">
            <form method="GET" action="{{ route('admin.wasit') }}" class="relative">
                <x-admin.icon name="search" class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-slate-400" />
                <input type="search" name="q" value="{{ $search }}" placeholder="Cari nama, HP, lisensi..."
                    class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-9 pr-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100 sm:w-64" />
            </form>
            <button type="button" data-modal-open="modal-tambah-wasit"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-medium text-white shadow-lg shadow-blue-600/25 transition-colors hover:bg-blue-700">
                <x-admin.icon name="plus" class="size-4" /> Tambah Wasit
            </button>
        </div>
    </div>

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
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
            <div>
                <h2 class="text-sm font-semibold text-slate-900">Daftar Wasit</h2>
                <p class="text-xs text-slate-500">Data diambil dari tabel tbl_wasit</p>
            </div>
            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">{{ $wasit->total() }} wasit</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[1160px] text-left text-sm">
                <thead class="bg-slate-50 text-[11px] uppercase tracking-wide text-slate-500">
                    <tr>
                        <th scope="col" class="px-4 py-3 font-medium">No</th>
                        <th scope="col" class="px-4 py-3 font-medium">Nama / HP</th>
                        <th scope="col" class="px-4 py-3 font-medium">Foto</th>
                        <th scope="col" class="px-4 py-3 font-medium">Jenis Kelamin</th>
                        <th scope="col" class="px-4 py-3 font-medium">Alamat</th>
                        <th scope="col" class="px-4 py-3 text-right font-medium">Harga</th>
                        <th scope="col" class="px-4 py-3 text-center font-medium">Jam</th>
                        <th scope="col" class="px-4 py-3 font-medium">Lisensi</th>
                        <th scope="col" class="px-4 py-3 text-center font-medium">Status</th>
                        <th scope="col" class="px-4 py-3 text-right font-medium">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($wasit as $item)
                        <tr class="transition-colors hover:bg-slate-50">
                            <td class="px-4 py-3 text-slate-500">{{ $wasit->firstItem() + $loop->index }}</td>
                            <td class="px-4 py-3">
                                <p class="font-medium text-slate-800">{{ $item->nama }}</p>
                                <p class="text-xs text-slate-500">{{ $item->nohp }}</p>
                            </td>
                            <td class="px-4 py-3">
                                @if ($item->foto)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($item->foto) }}" alt="Foto {{ $item->nama }}" class="size-10 rounded-lg object-cover">
                                @else
                                    <span class="text-xs text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-slate-600">{{ $item->jk ?: '-' }}</td>
                            <td class="max-w-56 truncate px-4 py-3 text-slate-600" title="{{ $item->alamat }}">{{ $item->alamat ?: '-' }}</td>
                            <td class="px-4 py-3 text-right text-slate-700">Rp {{ number_format((float) $item->harga, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-center text-slate-600">{{ $item->jml_jam ?: '-' }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $item->lisensi ?: '-' }}</td>
                            <td class="px-4 py-3 text-center">
                                @if ($item->isAktif())
                                    <x-admin.badge tone="emerald">Aktif</x-admin.badge>
                                @else
                                    <x-admin.badge tone="rose">Tidak Aktif</x-admin.badge>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1.5">
                                    <form method="POST" action="{{ route('admin.wasit.toggle', $item) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="rounded-lg bg-amber-50 px-2.5 py-1.5 text-xs font-medium text-amber-700 transition-colors hover:bg-amber-100">
                                            {{ $item->isAktif() ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                    <button type="button" data-modal-open="modal-edit-wasit-{{ $item->id }}"
                                        class="rounded-lg bg-blue-50 px-2.5 py-1.5 text-xs font-medium text-blue-700 transition-colors hover:bg-blue-100">Edit</button>
                                    <button type="button" data-modal-open="modal-hapus-wasit-{{ $item->id }}"
                                        class="rounded-lg bg-rose-50 px-2.5 py-1.5 text-xs font-medium text-rose-700 transition-colors hover:bg-rose-100">Hapus</button>
                                </div>
                            </td>
                        </tr>

                        <x-admin.modal id="modal-edit-wasit-{{ $item->id }}" title="Edit Wasit">
                            <form method="POST" action="{{ route('admin.wasit.update', $item) }}" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                @method('PUT')
                                @include('admin.wasit._form', ['formId' => 'edit-'.$item->id, 'wasitItem' => $item])
                                <div class="flex justify-end gap-2 pt-2">
                                    <button type="button" data-modal-close class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Batal</button>
                                    <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700">Simpan</button>
                                </div>
                            </form>
                        </x-admin.modal>

                        <x-admin.modal id="modal-hapus-wasit-{{ $item->id }}" title="Hapus Wasit">
                            <p class="text-sm text-slate-600">Hapus data wasit <span class="font-semibold text-slate-800">{{ $item->nama }}</span>?</p>
                            <form method="POST" action="{{ route('admin.wasit.destroy', $item) }}" class="mt-6 flex justify-end gap-2">
                                @csrf
                                @method('DELETE')
                                <button type="button" data-modal-close class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Batal</button>
                                <button type="submit" class="rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-rose-700">Hapus</button>
                            </form>
                        </x-admin.modal>
                    @empty
                        <tr>
                            <td colspan="10" class="px-5 py-10 text-center text-sm text-slate-400">Belum ada data wasit.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($wasit->hasPages())
            <div class="border-t border-slate-100 px-5 py-4">{{ $wasit->links() }}</div>
        @endif
    </section>

    <x-admin.modal id="modal-tambah-wasit" title="Tambah Wasit">
        <form method="POST" action="{{ route('admin.wasit.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @include('admin.wasit._form', ['formId' => 'create', 'wasitItem' => null])
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" data-modal-close class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Batal</button>
                <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700">Simpan Wasit</button>
            </div>
        </form>
    </x-admin.modal>
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
