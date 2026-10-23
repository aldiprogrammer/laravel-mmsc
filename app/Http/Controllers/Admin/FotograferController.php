<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fotografer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class FotograferController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q'));

        $fotografer = Fotografer::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($fotograferQuery) use ($search) {
                    $fotograferQuery->where('nama', 'like', '%'.$search.'%')
                        ->orWhere('nohp', 'like', '%'.$search.'%')
                        ->orWhere('role', 'like', '%'.$search.'%');
                });
            })
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.fotografer.index', compact('fotografer', 'search'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules(true));
        $photoPath = $request->file('foto')->store('fotografer', 'public');

        if ($photoPath === false) {
            return back()->withInput()->with('error', 'Foto fotografer gagal disimpan. Silakan coba kembali.');
        }

        $validated['foto'] = $photoPath;
        $validated['status'] = 1;
        Fotografer::create($validated);

        return redirect()->route('admin.fotografer')->with('success', 'Fotografer berhasil ditambahkan.');
    }

    public function update(Request $request, Fotografer $fotografer): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        if ($request->hasFile('foto')) {
            $photoPath = $request->file('foto')->store('fotografer', 'public');

            if ($photoPath === false) {
                return back()->withInput()->with('error', 'Foto fotografer gagal disimpan. Silakan coba kembali.');
            }

            $this->deleteStoredPhoto($fotografer->foto);
            $validated['foto'] = $photoPath;
        }

        $fotografer->update($validated);

        return redirect()->route('admin.fotografer')->with('success', 'Data fotografer berhasil diperbarui.');
    }

    public function destroy(Fotografer $fotografer): RedirectResponse
    {
        $this->deleteStoredPhoto($fotografer->foto);
        $fotografer->delete();

        return redirect()->route('admin.fotografer')->with('success', 'Fotografer berhasil dihapus.');
    }

    public function toggle(Fotografer $fotografer): RedirectResponse
    {
        $fotografer->update(['status' => $fotografer->isAktif() ? 0 : 1]);

        return redirect()->route('admin.fotografer')->with('success', 'Status fotografer berhasil diperbarui.');
    }

    /**
     * @return array<string, list<string>>
     */
    private function rules(bool $photoRequired = false): array
    {
        return [
            'nama' => ['required', 'string', 'max:50'],
            'nohp' => ['required', 'string', 'max:50'],
            'harga' => ['required', 'numeric', 'min:0'],
            'harga2' => ['nullable', 'string', 'max:20'],
            'role' => ['required', 'in:booking,rankteam'],
            'jml_jam' => ['required', 'string', 'max:11'],
            'keterangan' => ['nullable', 'string'],
            'tgl_tutup_mulai' => ['nullable', 'date'],
            'tgl_tutup_selesai' => ['nullable', 'date', 'after_or_equal:tgl_tutup_mulai'],
            'jam_tutup_mulai' => ['nullable', 'string', 'max:15'],
            'jam_tutup_selesai' => ['nullable', 'string', 'max:15'],
            'foto' => [$photoRequired ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    private function deleteStoredPhoto(?string $photoPath): void
    {
        if ($photoPath !== null && str_starts_with($photoPath, 'fotografer/')) {
            Storage::disk('public')->delete($photoPath);
        }
    }
}
