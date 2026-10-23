@extends('layouts.admin')

@section('title', 'Data User')
@section('page-title', 'Data User')
@section('page-subtitle', 'Daftar user terdaftar')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <nav class="flex items-center gap-2 text-sm text-slate-500">
            <span class="font-medium text-slate-700">User</span>
            <x-admin.icon name="chevron-right" class="size-3.5 text-slate-300" />
            <span>Data User</span>
        </nav>

        <form method="GET" action="{{ route('admin.data-user') }}" class="relative">
            <x-admin.icon name="search" class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-slate-400" />
            <input type="search" name="q" value="{{ $search }}" placeholder="Cari nama, email, nomor HP, atau kode..."
                class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-9 pr-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100 sm:w-80" />
        </form>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
            <div>
                <h2 class="text-sm font-semibold text-slate-900">Daftar User</h2>
                <p class="text-xs text-slate-500">Data diambil dari tabel tbl_user</p>
            </div>
            <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                {{ $users->total() }} user
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[850px] text-left text-sm">
                <thead class="bg-slate-50 text-[11px] uppercase tracking-wide text-slate-500">
                    <tr>
                        <th scope="col" class="px-5 py-3 font-medium">No</th>
                        <th scope="col" class="px-5 py-3 font-medium">Kode User</th>
                        <th scope="col" class="px-5 py-3 font-medium">Nama</th>
                        <th scope="col" class="px-5 py-3 font-medium">Email</th>
                        <th scope="col" class="px-5 py-3 font-medium">Nomor HP</th>
                        <th scope="col" class="px-5 py-3 text-center font-medium">Status</th>
                        <th scope="col" class="px-5 py-3 font-medium">Tanggal Daftar</th>
                        <th scope="col" class="px-5 py-3 text-right font-medium">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($users as $user)
                        <tr class="transition-colors hover:bg-slate-50">
                            <td class="px-5 py-3 text-slate-500">{{ $users->firstItem() + $loop->index }}</td>
                            <td class="px-5 py-3 font-mono text-xs text-slate-600">{{ $user->kode_user ?: '-' }}</td>
                            <td class="px-5 py-3 font-medium text-slate-800">{{ $user->nama ?: '-' }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $user->email ?: '-' }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $user->nohp ?: '-' }}</td>
                            <td class="px-5 py-3 text-center">
                                @if ($user->isAktif())
                                    <x-admin.badge tone="emerald">Aktif</x-admin.badge>
                                @else
                                    <x-admin.badge tone="rose">Tidak Aktif</x-admin.badge>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-slate-600">{{ $user->date ?: '-' }}</td>
                            <td class="px-5 py-3 text-right">
                                <button type="button" data-modal-open="modal-hapus-user-{{ $user->id }}"
                                    class="inline-flex items-center gap-1 rounded-lg bg-rose-50 px-3 py-1.5 text-xs font-medium text-rose-700 transition-colors hover:bg-rose-100">
                                    Hapus
                                </button>
                            </td>
                        </tr>

                        <x-admin.modal id="modal-hapus-user-{{ $user->id }}" title="Hapus User">
                            <p class="text-sm text-slate-600">
                                Hapus user <span class="font-semibold text-slate-800">{{ $user->nama }}</span>?
                                Data akan disembunyikan dari daftar user.
                            </p>
                            <form method="POST" action="{{ route('admin.data-user.destroy', $user) }}" class="mt-6 flex justify-end gap-2">
                                @csrf
                                @method('DELETE')
                                <button type="button" data-modal-close
                                    class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-rose-700">
                                    Hapus User
                                </button>
                            </form>
                        </x-admin.modal>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-10 text-center text-sm text-slate-400">
                                Tidak ada user yang cocok.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="border-t border-slate-100 px-5 py-4">
                {{ $users->links() }}
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
