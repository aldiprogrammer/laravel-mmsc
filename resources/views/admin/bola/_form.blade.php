@php
    $values = [
        'bola' => old('bola', $bolaItem?->bola),
        'grade' => old('grade', $bolaItem?->grade),
        'harga' => old('harga', $bolaItem?->harga),
        'jml_jam' => old('jml_jam', $bolaItem?->jml_jam),
    ];
@endphp

<div class="grid gap-4 sm:grid-cols-2">
    <div><label for="bola-{{ $formId }}" class="mb-1.5 block text-sm font-medium text-slate-700">Nama Bola</label><input id="bola-{{ $formId }}" name="bola" value="{{ $values['bola'] }}" maxlength="30" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100"></div>
    <div><label for="grade-{{ $formId }}" class="mb-1.5 block text-sm font-medium text-slate-700">Grade</label><input id="grade-{{ $formId }}" name="grade" value="{{ $values['grade'] }}" maxlength="30" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100"></div>
    <div><label for="harga-{{ $formId }}" class="mb-1.5 block text-sm font-medium text-slate-700">Harga</label><input id="harga-{{ $formId }}" type="number" name="harga" value="{{ $values['harga'] }}" min="0" step="any" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100"></div>
    <div><label for="jam-{{ $formId }}" class="mb-1.5 block text-sm font-medium text-slate-700">Jumlah Jam</label><input id="jam-{{ $formId }}" name="jml_jam" value="{{ $values['jml_jam'] }}" maxlength="11" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100"></div>
    <div class="sm:col-span-2">
        <label for="foto-{{ $formId }}" class="mb-1.5 block text-sm font-medium text-slate-700">Foto {{ $bolaItem ? '(opsional)' : '' }}</label>
        @if ($bolaItem?->foto)<img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($bolaItem->foto) }}" alt="Foto {{ $bolaItem->bola }}" class="mb-2 size-16 rounded-lg object-cover">@endif
        <input id="foto-{{ $formId }}" type="file" name="foto" accept="image/jpeg,image/png,image/webp" @required($bolaItem === null) class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 file:mr-3 file:rounded-lg file:border-0 file:bg-blue-50 file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-blue-700">
        <p class="mt-1 text-xs text-slate-400">Format JPG, JPEG, PNG, atau WEBP. Maksimal 5 MB. Kosongkan saat edit untuk mempertahankan foto lama.</p>
    </div>
</div>
