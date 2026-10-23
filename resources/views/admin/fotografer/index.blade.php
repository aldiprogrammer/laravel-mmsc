@extends('layouts.admin')
@section('title', 'Data Fotografer')
@section('page-title', 'Data Fotografer')
@section('page-subtitle', 'Kelola data, foto, dan status fotografer')
@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <nav class="flex items-center gap-2 text-sm text-slate-500"><span class="font-medium text-slate-700">Data</span><x-admin.icon name="chevron-right" class="size-3.5 text-slate-300" /><span>Fotografer</span></nav>
        <div class="flex flex-col gap-3 sm:flex-row">
            <form method="GET" action="{{ route('admin.fotografer') }}" class="relative"><x-admin.icon name="search" class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-slate-400" /><input type="search" name="q" value="{{ $search }}" placeholder="Cari nama, HP, atau role..." class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-9 pr-3 text-sm text-slate-700 shadow-sm outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 sm:w-64"></form>
            <button type="button" data-modal-open="modal-tambah-fotografer" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700"><x-admin.icon name="plus" class="size-4" /> Tambah Fotografer</button>
        </div>
    </div>
    @if (session('success'))<div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">{{ session('success') }}</div>@endif
    @if (session('error'))<div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">{{ session('error') }}</div>@endif
    @if ($errors->any())<div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700"><p class="font-semibold">Data belum dapat disimpan:</p><ul class="mt-1 list-inside list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4"><div><h2 class="text-sm font-semibold text-slate-900">Daftar Fotografer</h2><p class="text-xs text-slate-500">Data diambil dari tabel tbl_potografer</p></div><span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">{{ $fotografer->total() }} fotografer</span></div>
        <div class="overflow-x-auto"><table class="w-full min-w-[1050px] text-left text-sm">
            <thead class="bg-slate-50 text-[11px] uppercase tracking-wide text-slate-500"><tr><th scope="col" class="px-4 py-3 font-medium">No</th><th scope="col" class="px-4 py-3 font-medium">Foto</th><th scope="col" class="px-4 py-3 font-medium">Nama / HP</th><th scope="col" class="px-4 py-3 font-medium">Role</th><th scope="col" class="px-4 py-3 text-right font-medium">Harga</th><th scope="col" class="px-4 py-3 text-center font-medium">Jam</th><th scope="col" class="px-4 py-3 font-medium">Keterangan</th><th scope="col" class="px-4 py-3 text-center font-medium">Status</th><th scope="col" class="px-4 py-3 text-right font-medium">Opsi</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($fotografer as $item)
                    <tr class="transition-colors hover:bg-slate-50">
                        <td class="px-4 py-3 text-slate-500">{{ $fotografer->firstItem() + $loop->index }}</td>
                        <td class="px-4 py-3">@if ($item->foto)<img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($item->foto) }}" alt="Foto {{ $item->nama }}" class="size-10 rounded-lg object-cover">@else<span class="text-xs text-slate-400">-</span>@endif</td>
                        <td class="px-4 py-3"><p class="font-medium text-slate-800">{{ $item->nama }}</p><p class="text-xs text-slate-500">{{ $item->nohp }}</p></td>
                        <td class="px-4 py-3 text-slate-600">{{ $item->role }}</td><td class="px-4 py-3 text-right text-slate-700">Rp {{ number_format((float) $item->harga, 0, ',', '.') }}</td><td class="px-4 py-3 text-center text-slate-600">{{ $item->jml_jam }}</td>
                        <td class="max-w-48 truncate px-4 py-3 text-slate-600" title="{{ $item->keterangan }}">{{ $item->keterangan ?: '-' }}</td>
                        <td class="px-4 py-3 text-center">@if ($item->isAktif())<x-admin.badge tone="emerald">Aktif</x-admin.badge>@else<x-admin.badge tone="rose">Tidak Aktif</x-admin.badge>@endif</td>
                        <td class="px-4 py-3"><div class="flex items-center justify-end gap-1.5"><form method="POST" action="{{ route('admin.fotografer.toggle', $item) }}">@csrf @method('PATCH')<button type="submit" class="rounded-lg bg-amber-50 px-2.5 py-1.5 text-xs font-medium text-amber-700 hover:bg-amber-100">{{ $item->isAktif() ? 'Nonaktifkan' : 'Aktifkan' }}</button></form><button type="button" data-modal-open="modal-edit-fotografer-{{ $item->id }}" class="rounded-lg bg-blue-50 px-2.5 py-1.5 text-xs font-medium text-blue-700 hover:bg-blue-100">Edit</button><button type="button" data-modal-open="modal-hapus-fotografer-{{ $item->id }}" class="rounded-lg bg-rose-50 px-2.5 py-1.5 text-xs font-medium text-rose-700 hover:bg-rose-100">Hapus</button></div></td>
                    </tr>
                    <x-admin.modal id="modal-edit-fotografer-{{ $item->id }}" title="Edit Fotografer"><form method="POST" action="{{ route('admin.fotografer.update', $item) }}" enctype="multipart/form-data" class="space-y-4">@csrf @method('PUT')@include('admin.fotografer._form', ['formId' => 'edit-'.$item->id, 'fotograferItem' => $item])<div class="flex justify-end gap-2 pt-2"><button type="button" data-modal-close class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-700">Batal</button><button type="submit" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-medium text-white">Simpan</button></div></form></x-admin.modal>
                    <x-admin.modal id="modal-hapus-fotografer-{{ $item->id }}" title="Hapus Fotografer"><p class="text-sm text-slate-600">Hapus data fotografer <span class="font-semibold text-slate-800">{{ $item->nama }}</span>?</p><form method="POST" action="{{ route('admin.fotografer.destroy', $item) }}" class="mt-6 flex justify-end gap-2">@csrf @method('DELETE')<button type="button" data-modal-close class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-700">Batal</button><button type="submit" class="rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-medium text-white">Hapus</button></form></x-admin.modal>
                @empty
                    <tr><td colspan="9" class="px-5 py-10 text-center text-sm text-slate-400">Belum ada data fotografer.</td></tr>
                @endforelse
            </tbody>
        </table></div>
        @if ($fotografer->hasPages())<div class="border-t border-slate-100 px-5 py-4">{{ $fotografer->links() }}</div>@endif
    </section>
    <x-admin.modal id="modal-tambah-fotografer" title="Tambah Fotografer"><form method="POST" action="{{ route('admin.fotografer.store') }}" enctype="multipart/form-data" class="space-y-4">@csrf @include('admin.fotografer._form', ['formId' => 'create', 'fotograferItem' => null])<div class="flex justify-end gap-2 pt-2"><button type="button" data-modal-close class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-700">Batal</button><button type="submit" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-medium text-white">Simpan Fotografer</button></div></form></x-admin.modal>
@endsection
@push('scripts')
    <script>
        document.querySelectorAll('[data-modal-open]').forEach((button) => button.addEventListener('click', () => { document.getElementById(button.dataset.modalOpen)?.classList.remove('hidden'); document.body.classList.add('overflow-hidden'); }));
        document.querySelectorAll('[data-modal-close]').forEach((button) => button.addEventListener('click', () => { button.closest('[data-modal]')?.classList.add('hidden'); document.body.classList.remove('overflow-hidden'); }));
        document.querySelectorAll('[data-modal]').forEach((modal) => modal.addEventListener('click', (event) => { if (event.target === modal) { modal.classList.add('hidden'); document.body.classList.remove('overflow-hidden'); } }));
    </script>
@endpush
