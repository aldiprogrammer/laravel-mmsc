@extends('layouts.admin')

@section('title', 'Data Member Karir Team')
@section('page-title', 'Member Karir')
@section('page-subtitle', 'Data Member Karir Team')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <nav class="flex items-center gap-2 text-sm text-slate-500">
            <span class="font-medium text-slate-700">Member Karir</span>
            <x-admin.icon name="chevron-right" class="size-3.5 text-slate-300" />
            <span class="text-slate-500">Data Member Karir Team</span>
        </nav>

        <form method="GET" action="{{ route('admin.karir.member') }}" class="relative">
            @if ($status)
                <input type="hidden" name="status" value="{{ $status }}">
            @endif

            <x-admin.icon name="search"
                class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-slate-400" />
            <input type="search" name="q" value="{{ $search }}" placeholder="Cari team/kode main..."
                class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-9 pr-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100 sm:w-64" />
        </form>
    </div>

    @if (session('success'))
        <div class="mb-6 flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            <x-admin.icon name="check-circle" class="size-5 shrink-0" />
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-6 flex flex-wrap items-center gap-2">
        @php
            $tabs = [
                'all' => ['label' => 'Semua', 'count' => $totalMenunggu + $totalDisetujui + $totalDitolak],
                'menunggu' => ['label' => 'Menunggu', 'count' => $totalMenunggu],
                'disetujui' => ['label' => 'Disetujui', 'count' => $totalDisetujui],
                'ditolak' => ['label' => 'Ditolak', 'count' => $totalDitolak],
            ];
        @endphp

        @foreach ($tabs as $key => $tab)
            @php
                $value = $key === 'all' ? null : $key;
                $isActive = $status === $value;
            @endphp

            <a href="{{ route('admin.karir.member', array_filter(['status' => $value, 'q' => $search])) }}"
                class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-medium shadow-sm transition-colors {{ $isActive ? 'bg-blue-600 text-white' : 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50' }}">
                {{ $tab['label'] }}
                <span class="rounded-full px-1.5 text-[11px] {{ $isActive ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500' }}">
                    {{ $tab['count'] }}
                </span>
            </a>
        @endforeach
    </div>

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
            <div>
                <h3 class="text-sm font-semibold text-slate-900">Data Member Karir Team</h3>
                <p class="text-xs text-slate-500">Data diambil dari tabel tbl_member_karir_team</p>
            </div>

            <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                {{ $members->total() }} data
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[1100px] text-left text-sm">
                <thead class="bg-slate-50 text-[11px] uppercase tracking-wide text-slate-500">
                    <tr>
                        <th scope="col" class="px-5 py-3 font-medium">Kode Main</th>
                        <th scope="col" class="px-5 py-3 font-medium">Team</th>
                        <th scope="col" class="px-5 py-3 font-medium">Tgl Main</th>
                        <th scope="col" class="px-5 py-3 font-medium">Jam</th>
                        <th scope="col" class="px-5 py-3 text-center font-medium">Sesi</th>
                        <th scope="col" class="px-5 py-3 font-medium">Lapangan</th>
                        <th scope="col" class="px-5 py-3 text-right font-medium">Harga</th>
                        <th scope="col" class="px-5 py-3 text-right font-medium">Total Harga</th>
                        <th scope="col" class="px-5 py-3 text-center font-medium">Status</th>
                        <th scope="col" class="px-5 py-3 font-medium">Bukti</th>
                        <th scope="col" class="px-5 py-3 text-right font-medium">Opsi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($members as $item)
                        <tr class="transition-colors hover:bg-slate-50">
                            <td class="px-5 py-3 font-mono text-xs text-slate-600">{{ $item->kode_main }}</td>
                            <td class="px-5 py-3">
                                <p class="font-medium text-slate-800">{{ $item->team }}</p>
                                <p class="text-xs text-slate-400">Lawan: {{ $item->lawan ?: '-' }}</p>
                            </td>
                            <td class="px-5 py-3 text-slate-700">{{ $item->tgl_main }}</td>
                            <td class="px-5 py-3 text-slate-700">{{ $item->jam_mulai }}</td>
                            <td class="px-5 py-3 text-center text-slate-700">{{ $item->sesi }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $item->lapangan }}</td>
                            <td class="px-5 py-3 text-right text-slate-700">Rp {{ number_format((float) $item->harga, 0, ',', '.') }}</td>
                            <td class="px-5 py-3 text-right font-medium text-slate-700">Rp {{ number_format((float) $item->total_harga, 0, ',', '.') }}</td>
                            <td class="px-5 py-3 text-center">
                                @if ($item->status_pembayaran == 201)
                                    <x-admin.badge tone="amber">Menunggu</x-admin.badge>
                                @elseif ($item->status_pembayaran == 400)
                                    <x-admin.badge tone="rose">Ditolak</x-admin.badge>
                                @else
                                    <x-admin.badge tone="emerald">Disetujui</x-admin.badge>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                @if ($item->bukti)
                                    <a href="{{ $item->bukti }}" target="_blank" rel="noopener"
                                        class="text-xs font-medium text-blue-600 hover:text-blue-700">
                                        Lihat Bukti
                                    </a>
                                @else
                                    <span class="text-xs text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex flex-wrap items-center justify-end gap-1.5">
                                    <button type="button" data-modal-open="modal-setujui-{{ $item->id }}"
                                        class="inline-flex items-center rounded-lg bg-emerald-50 px-2.5 py-1.5 text-xs font-medium text-emerald-700 transition-colors hover:bg-emerald-100">
                                        Setujui
                                    </button>
                                    <button type="button" data-modal-open="modal-tolak-{{ $item->id }}"
                                        class="inline-flex items-center rounded-lg bg-amber-50 px-2.5 py-1.5 text-xs font-medium text-amber-700 transition-colors hover:bg-amber-100">
                                        Tolak
                                    </button>
                                    <button type="button" data-modal-open="modal-hapus-member-{{ $item->id }}"
                                        class="inline-flex items-center rounded-lg bg-rose-50 px-2.5 py-1.5 text-xs font-medium text-rose-700 transition-colors hover:bg-rose-100">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <x-admin.modal id="modal-setujui-{{ $item->id }}" title="Setujui Pembayaran">
                            <p class="text-sm text-slate-600">Apakah anda ingin menyetujui pembayaran ini?</p>
                            <form method="POST" action="{{ route('admin.karir.member.setujui') }}" class="mt-6 flex justify-end gap-2">
                                @csrf
                                <input type="hidden" name="id" value="{{ $item->id }}">
                                <button type="button" data-modal-close
                                    class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50">
                                    Close
                                </button>
                                <button type="submit"
                                    class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white shadow-lg shadow-emerald-600/25 transition-colors hover:bg-emerald-700">
                                    Ya
                                </button>
                            </form>
                        </x-admin.modal>

                        <x-admin.modal id="modal-tolak-{{ $item->id }}" title="Tolak Pembayaran">
                            <p class="text-sm text-slate-600">Apakah anda ingin menolak pembayaran ini?</p>
                            <form method="POST" action="{{ route('admin.karir.member.tolak') }}" class="mt-6 flex justify-end gap-2">
                                @csrf
                                <input type="hidden" name="id" value="{{ $item->id }}">
                                <button type="button" data-modal-close
                                    class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50">
                                    Close
                                </button>
                                <button type="submit"
                                    class="rounded-xl bg-amber-600 px-4 py-2.5 text-sm font-medium text-white shadow-lg shadow-amber-600/25 transition-colors hover:bg-amber-700">
                                    Ya
                                </button>
                            </form>
                        </x-admin.modal>

                        <x-admin.modal id="modal-hapus-member-{{ $item->id }}" title="Hapus Data">
                            <p class="text-sm text-slate-600">Apakah anda ingin menghapus data ini?</p>
                            <form method="POST" action="{{ route('admin.karir.member.hapus') }}" class="mt-6 flex justify-end gap-2">
                                @csrf
                                <input type="hidden" name="id" value="{{ $item->id }}">
                                <button type="button" data-modal-close
                                    class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50">
                                    Close
                                </button>
                                <button type="submit"
                                    class="rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-medium text-white shadow-lg shadow-rose-600/25 transition-colors hover:bg-rose-700">
                                    Ya
                                </button>
                            </form>
                        </x-admin.modal>
                    @empty
                        <tr>
                            <td colspan="11" class="px-5 py-8 text-center text-sm text-slate-400">
                                Belum ada data member karir team
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($members->hasPages())
            <div class="border-t border-slate-100 px-5 py-4">
                {{ $members->links() }}
            </div>
        @endif
    </section>
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
