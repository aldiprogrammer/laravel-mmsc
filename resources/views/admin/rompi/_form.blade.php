@php
    $values = [
        'rompi' => old('rompi', $rompiItem?->rompi),
        'warna' => old('warna', $rompiItem?->warna),
        'grade' => old('grade', $rompiItem?->grade),
        'harga' => old('harga', $rompiItem?->harga),
        'jml_jam' => old('jml_jam', $rompiItem?->jml_jam),
    ];
@endphp

<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label for="rompi-{{ $formId }}" class="mb-1.5 block text-sm font-medium text-slate-700">Nama Rompi</label>
        <input id="rompi-{{ $formId }}" name="rompi" value="{{ $values['rompi'] }}" maxlength="50" required
            class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
    </div>
    <div>
        <label for="warna-{{ $formId }}" class="mb-1.5 block text-sm font-medium text-slate-700">Warna</label>
        <input id="warna-{{ $formId }}" name="warna" value="{{ $values['warna'] }}" maxlength="30" required
            class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
    </div>
    <div>
        <label for="grade-{{ $formId }}" class="mb-1.5 block text-sm font-medium text-slate-700">Grade</label>
        <input id="grade-{{ $formId }}" name="grade" value="{{ $values['grade'] }}" maxlength="15" required
            class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
    </div>
    <div>
        <label for="harga-{{ $formId }}" class="mb-1.5 block text-sm font-medium text-slate-700">Harga</label>
        <input id="harga-{{ $formId }}" type="number" name="harga" value="{{ $values['harga'] }}" min="0" step="any" required
            class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
    </div>
    <div>
        <label for="jml-jam-{{ $formId }}" class="mb-1.5 block text-sm font-medium text-slate-700">Jumlah Jam</label>
        <input id="jml-jam-{{ $formId }}" name="jml_jam" value="{{ $values['jml_jam'] }}" maxlength="11" required
            class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
    </div>
    <div class="sm:col-span-2">
        <label for="foto-{{ $formId }}" class="mb-1.5 block text-sm font-medium text-slate-700">Foto {{ $rompiItem ? '(opsional)' : '' }}</label>
        @if ($rompiItem?->foto)
            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($rompiItem->foto) }}" alt="Foto {{ $rompiItem->rompi }}" class="mb-2 size-16 rounded-lg object-cover">
        @endif
        <input id="foto-{{ $formId }}" type="file" name="foto" accept="image/jpeg,image/png,image/webp" @required($rompiItem === null)
            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 file:mr-3 file:rounded-lg file:border-0 file:bg-blue-50 file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-blue-700">
        <p class="mt-1 text-xs text-slate-400">Format JPG, JPEG, PNG, atau WEBP. Maksimal 5 MB. Kosongkan saat edit untuk mempertahankan foto saat ini.</p>
    </div>
</div>
