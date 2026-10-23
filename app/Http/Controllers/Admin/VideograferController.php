<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Videografer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class VideograferController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q'));

        $videografer = Videografer::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($videograferQuery) use ($search) {
                    $videograferQuery->where('nama', 'like', '%'.$search.'%')
                        ->orWhere('nowa', 'like', '%'.$search.'%');
                });
            })
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.videografer.index', compact('videografer', 'search'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules(true));
        $photoPath = $request->file('foto')->store('videografer', 'public');

        if ($photoPath === false) {
            return back()->withInput()->with('error', 'Foto videografer gagal disimpan. Silakan coba kembali.');
        }

        $validated['foto'] = $photoPath;
        $validated['status'] = 1;
        Videografer::create($validated);

        return redirect()->route('admin.videografer')->with('success', 'Videografer berhasil ditambahkan.');
    }

    public function update(Request $request, Videografer $videografer): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        if ($request->hasFile('foto')) {
            $photoPath = $request->file('foto')->store('videografer', 'public');

            if ($photoPath === false) {
                return back()->withInput()->with('error', 'Foto videografer gagal disimpan. Silakan coba kembali.');
            }

            $this->deleteStoredPhoto($videografer->foto);
            $validated['foto'] = $photoPath;
        }

        $videografer->update($validated);

        return redirect()->route('admin.videografer')->with('success', 'Data videografer berhasil diperbarui.');
    }

    public function destroy(Videografer $videografer): RedirectResponse
    {
        $this->deleteStoredPhoto($videografer->foto);
        $videografer->delete();

        return redirect()->route('admin.videografer')->with('success', 'Videografer berhasil dihapus.');
    }

    public function toggle(Videografer $videografer): RedirectResponse
    {
        $videografer->update(['status' => $videografer->isAktif() ? 0 : 1]);

        return redirect()->route('admin.videografer')->with('success', 'Status videografer berhasil diperbarui.');
    }

    /**
     * @return array<string, list<string>>
     */
    private function rules(bool $photoRequired = false): array
    {
        return [
            'nama' => ['required', 'string', 'max:50'],
            'nowa' => ['required', 'string', 'max:14'],
            'alamat' => ['required', 'string'],
            'harga' => ['required', 'integer', 'min:0'],
            'harga2' => ['nullable', 'string', 'max:20'],
            'foto' => [$photoRequired ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    private function deleteStoredPhoto(?string $photoPath): void
    {
        if ($photoPath !== null && str_starts_with($photoPath, 'videografer/')) {
            Storage::disk('public')->delete($photoPath);
        }
    }
}
