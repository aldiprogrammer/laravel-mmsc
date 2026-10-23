@extends('layouts.admin')

@section('title', $section)
@section('page-title', $section)
@section('page-subtitle', 'Modul '.$section.' sedang dalam tahap pengembangan')

@section('content')
    <div class="flex min-h-[60vh] items-center justify-center">
        <div class="w-full max-w-lg rounded-2xl border border-slate-200 bg-white p-8 text-center shadow-sm">
            <span class="mx-auto grid size-14 place-items-center rounded-2xl bg-blue-50 text-blue-600 ring-1 ring-blue-100">
                <x-admin.icon name="cog-6-tooth" class="size-7" />
            </span>

            <h2 class="mt-5 text-lg font-semibold text-slate-900">Modul {{ $section }} Segera Hadir</h2>
            <p class="mt-2 text-sm leading-relaxed text-slate-500">
                Halaman {{ $section }} sedang dibangun. Saat ini Anda dapat melihat ringkasan data pada
                dashboard admin.
            </p>

            <a href="{{ route('admin.dashboard') }}"
                class="mt-6 inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-medium text-white shadow-lg shadow-blue-600/25 transition-colors hover:bg-blue-700">
                <x-admin.icon name="dashboard" class="size-4" />
                Kembali ke Dashboard
            </a>
        </div>
    </div>
@endsection
