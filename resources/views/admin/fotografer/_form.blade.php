@php
    $values = [
        'nama' => old('nama', $fotograferItem?->nama),
        'nohp' => old('nohp', $fotograferItem?->nohp),
        'harga' => old('harga', $fotograferItem?->harga),
        'harga2' => old('harga2', $fotograferItem?->harga2),
        'role' => old('role', $fotograferItem?->role ?? 'booking'),
        'jml_jam' => old('jml_jam', $fotograferItem?->jml_jam),
        'keterangan' => old('keterangan', $fotograferItem?->keterangan),
        'tgl_tutup_mulai' => old('tgl_tutup_mulai', $fotograferItem?->tgl_tutup_mulai),
        'tgl_tutup_selesai' => old('tgl_tutup_selesai', $fotograferItem?->tgl_tutup_selesai),
        'jam_tutup_mulai' => old('jam_tutup_mulai', $fotograferItem?->jam_tutup_mulai),
        'jam_tutup_selesai' => old('jam_tutup_selesai', $fotograferItem?->jam_tutup_selesai),
    ];
@endphp

<div class="grid gap-4 sm:grid-cols-2">
    <div><label for="nama-{{ $formId }}" class="mb-1.5 block text-sm font-medium text-slate-700">Nama</label><input id="nama-{{ $formId }}" name="nama" value="{{ $values['nama'] }}" maxlength="50" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100"></div>
    <div><label for="nohp-{{ $formId }}" class="mb-1.5 block text-sm font-medium text-slate-700">Nomor HP</label><input id="nohp-{{ $formId }}" name="nohp" value="{{ $values['nohp'] }}" maxlength="50" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100"></div>
    <div><label for="harga-{{ $formId }}" class="mb-1.5 block text-sm font-medium text-slate-700">Harga</label><input id="harga-{{ $formId }}" type="number" name="harga" value="{{ $values['harga'] }}" min="0" step="any" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100"></div>
    <div><label for="harga2-{{ $formId }}" class="mb-1.5 block text-sm font-medium text-slate-700">Harga Tambahan (opsional)</label><input id="harga2-{{ $formId }}" name="harga2" value="{{ $values['harga2'] }}" maxlength="20" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100"></div>
    <div><label for="role-{{ $formId }}" class="mb-1.5 block text-sm font-medium text-slate-700">Role</label><select id="role-{{ $formId }}" name="role" required class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100"><option value="booking" @selected($values['role'] === 'booking')>Booking</option><option value="rankteam" @selected($values['role'] === 'rankteam')>Rank Team</option></select></div>
    <div><label for="jam-{{ $formId }}" class="mb-1.5 block text-sm font-medium text-slate-700">Jumlah Jam</label><input id="jam-{{ $formId }}" name="jml_jam" value="{{ $values['jml_jam'] }}" maxlength="11" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100"></div>
    <div class="sm:col-span-2"><label for="keterangan-{{ $formId }}" class="mb-1.5 block text-sm font-medium text-slate-700">Keterangan</label><textarea id="keterangan-{{ $formId }}" name="keterangan" rows="2" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100">{{ $values['keterangan'] }}</textarea></div>
    <div><label for="tgl-mulai-{{ $formId }}" class="mb-1.5 block text-sm font-medium text-slate-700">Tanggal Tutup Mulai</label><input id="tgl-mulai-{{ $formId }}" type="date" name="tgl_tutup_mulai" value="{{ $values['tgl_tutup_mulai'] }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100"></div>
    <div><label for="tgl-selesai-{{ $formId }}" class="mb-1.5 block text-sm font-medium text-slate-700">Tanggal Tutup Selesai</label><input id="tgl-selesai-{{ $formId }}" type="date" name="tgl_tutup_selesai" value="{{ $values['tgl_tutup_selesai'] }}" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100"></div>
    <div><label for="jam-tutup-mulai-{{ $formId }}" class="mb-1.5 block text-sm font-medium text-slate-700">Jam Tutup Mulai</label><input id="jam-tutup-mulai-{{ $formId }}" name="jam_tutup_mulai" value="{{ $values['jam_tutup_mulai'] }}" maxlength="15" placeholder="18.00" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100"></div>
    <div><label for="jam-tutup-selesai-{{ $formId }}" class="mb-1.5 block text-sm font-medium text-slate-700">Jam Tutup Selesai</label><input id="jam-tutup-selesai-{{ $formId }}" name="jam_tutup_selesai" value="{{ $values['jam_tutup_selesai'] }}" maxlength="15" placeholder="21.00" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100"></div>
    <div class="sm:col-span-2">
        <label for="foto-{{ $formId }}" class="mb-1.5 block text-sm font-medium text-slate-700">Foto {{ $fotograferItem ? '(opsional)' : '' }}</label>
        @if ($fotograferItem?->foto)<img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($fotograferItem->foto) }}" alt="Foto {{ $fotograferItem->nama }}" class="mb-2 size-16 rounded-lg object-cover">@endif
        <input id="foto-{{ $formId }}" type="file" name="foto" accept="image/jpeg,image/png,image/webp" @required($fotograferItem === null) class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 file:mr-3 file:rounded-lg file:border-0 file:bg-blue-50 file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-blue-700">
        <p class="mt-1 text-xs text-slate-400">Format JPG, JPEG, PNG, atau WEBP. Maksimal 5 MB. Kosongkan saat edit untuk mempertahankan foto lama.</p>
    </div>
</div>
