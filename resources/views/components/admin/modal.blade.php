@props([
    'id',
    'title' => '',
])

<div id="{{ $id }}" data-modal
    class="fixed inset-0 z-[60] hidden overflow-y-auto bg-slate-900/60 p-4 backdrop-blur-sm">
    <div class="mx-auto mt-10 w-full max-w-lg overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl">
        @if ($title !== '')
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <h3 class="text-sm font-semibold text-slate-900">{{ $title }}</h3>

                <button type="button" data-modal-close class="rounded-lg p-1.5 text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-600">
                    <x-admin.icon name="x-mark" class="size-4" />
                </button>
            </div>
        @endif

        <div class="px-5 py-5">
            {{ $slot }}
        </div>
    </div>
</div>
