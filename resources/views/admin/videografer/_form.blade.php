@php
    $values = [
        'nama' => old('nama', $videograferItem?->nama),
        'nowa' => old('nowa', $videograferItem?->nowa),
        'alamat' => old('alamat', $videograferItem?->alamat),
        'harga' => old('harga', $videograferItem?->harga),
        'harga2' => old('harga2', $videograferItem?->harga2),
    ];
@endphp

<div class="grid gap-4 sm:grid-cols-2">
    <div><label for="nama-{{ $formId }}" class="mb-1.5 block text-sm font-medium text-slate-700">Nama</label><input id="nama-{{ $formId }}" name="nama" value="{{ $values['nama'] }}" maxlength="50" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100"></div>
    <div><label for="nowa-{{ $formId }}" class="mb-1.5 block text-sm font-medium text-slate-700">Nomor WhatsApp</label><input id="nowa-{{ $formId }}" name="nowa" value="{{ $values['nowa'] }}" maxlength="14" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100"></div>
    <div><label for="harga-{{ $formId }}" class="mb-1.5 block text-sm font-medium text-slate-700">Harga</label><input id="harga-{{ $formId }}" type="number" name="harga" value="{{ $values['harga'] }}" min="0" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100"></div>
    <div><label for="harga2-{{ $formId }}" class="mb-1.5 block text-sm font-medium text-slate-700">Harga Tambahan (opsional)</label><input id="harga2-{{ $formId }}" name="harga2" value="{{ $values['harga2'] }}" maxlength="20" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100"></div>
    <div class="sm:col-span-2"><label for="alamat-{{ $formId }}" class="mb-1.5 block text-sm font-medium text-slate-700">Alamat</label><textarea id="alamat-{{ $formId }}" name="alamat" rows="2" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100">{{ $values['alamat'] }}</textarea></div>
    <div class="sm:col-span-2">
        <label for="foto-{{ $formId }}" class="mb-1.5 block text-sm font-medium text-slate-700">Foto {{ $videograferItem ? '(opsional)' : '' }}</label>
        @if ($videograferItem?->foto)<img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($videograferItem->foto) }}" alt="Foto {{ $videograferItem->nama }}" class="mb-2 size-16 rounded-lg object-cover">@endif
        <input id="foto-{{ $formId }}" type="file" name="foto" accept="image/jpeg,image/png,image/webp" @required($videograferItem === null) class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 file:mr-3 file:rounded-lg file:border-0 file:bg-blue-50 file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-blue-700">
        <p class="mt-1 text-xs text-slate-400">Format JPG, JPEG, PNG, atau WEBP. Maksimal 5 MB. Kosongkan saat edit untuk mempertahankan foto lama.</p>
    </div>
</div>
