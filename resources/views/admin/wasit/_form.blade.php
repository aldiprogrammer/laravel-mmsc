@php
    $values = [
        'nama' => old('nama', $wasitItem?->nama),
        'nohp' => old('nohp', $wasitItem?->nohp),
        'jk' => old('jk', $wasitItem?->jk),
        'alamat' => old('alamat', $wasitItem?->alamat),
        'harga' => old('harga', $wasitItem?->harga),
        'jml_jam' => old('jml_jam', $wasitItem?->jml_jam),
        'lisensi' => old('lisensi', $wasitItem?->lisensi),
    ];
@endphp

<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label for="nama-{{ $formId }}" class="mb-1.5 block text-sm font-medium text-slate-700">Nama</label>
        <input id="nama-{{ $formId }}" name="nama" value="{{ $values['nama'] }}" maxlength="20" required
            class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
    </div>
    <div>
        <label for="nohp-{{ $formId }}" class="mb-1.5 block text-sm font-medium text-slate-700">Nomor HP</label>
        <input id="nohp-{{ $formId }}" name="nohp" value="{{ $values['nohp'] }}" maxlength="13" required
            class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
    </div>
    <div>
        <label for="jk-{{ $formId }}" class="mb-1.5 block text-sm font-medium text-slate-700">Jenis Kelamin</label>
        <select id="jk-{{ $formId }}" name="jk" required class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
            <option value="">Pilih jenis kelamin</option>
            <option value="Laki - laki" @selected($values['jk'] === 'Laki - laki')>Laki-laki</option>
            <option value="Perempuan" @selected($values['jk'] === 'Perempuan')>Perempuan</option>
        </select>
    </div>
    <div>
        <label for="harga-{{ $formId }}" class="mb-1.5 block text-sm font-medium text-slate-700">Harga</label>
        <input id="harga-{{ $formId }}" type="number" name="harga" value="{{ $values['harga'] }}" min="0" required
            class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
    </div>
    <div>
        <label for="jml-jam-{{ $formId }}" class="mb-1.5 block text-sm font-medium text-slate-700">Jumlah Jam</label>
        <input id="jml-jam-{{ $formId }}" name="jml_jam" value="{{ $values['jml_jam'] }}" maxlength="11" required
            class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
    </div>
    <div>
        <label for="lisensi-{{ $formId }}" class="mb-1.5 block text-sm font-medium text-slate-700">Lisensi</label>
        <input id="lisensi-{{ $formId }}" name="lisensi" value="{{ $values['lisensi'] }}" maxlength="50"
            class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
    </div>
    <div class="sm:col-span-2">
        <label for="alamat-{{ $formId }}" class="mb-1.5 block text-sm font-medium text-slate-700">Alamat</label>
        <textarea id="alamat-{{ $formId }}" name="alamat" rows="2" required
            class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100">{{ $values['alamat'] }}</textarea>
    </div>
    <div class="sm:col-span-2">
        <label for="foto-{{ $formId }}" class="mb-1.5 block text-sm font-medium text-slate-700">Foto {{ $wasitItem ? '(opsional)' : '' }}</label>
        @if ($wasitItem?->foto)
            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($wasitItem->foto) }}" alt="Foto {{ $wasitItem->nama }}" class="mb-2 size-16 rounded-lg object-cover">
        @endif
        <input id="foto-{{ $formId }}" type="file" name="foto" accept="image/jpeg,image/png,image/webp" @required($wasitItem === null)
            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 file:mr-3 file:rounded-lg file:border-0 file:bg-blue-50 file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-blue-700">
        <p class="mt-1 text-xs text-slate-400">Format JPG, JPEG, PNG, atau WEBP. Maksimal 5 MB. Kosongkan saat edit untuk mempertahankan foto saat ini.</p>
    </div>
</div>
