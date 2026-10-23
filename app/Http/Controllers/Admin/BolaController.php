<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bola;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BolaController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q'));

        $bola = Bola::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($bolaQuery) use ($search) {
                    $bolaQuery->where('bola', 'like', '%'.$search.'%')
                        ->orWhere('grade', 'like', '%'.$search.'%');
                });
            })
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.bola.index', compact('bola', 'search'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules(true));
        $photoPath = $request->file('foto')->store('bola', 'public');

        if ($photoPath === false) {
            return back()->withInput()->with('error', 'Foto bola gagal disimpan. Silakan coba kembali.');
        }

        $validated['foto'] = $photoPath;
        $validated['status'] = 1;
        Bola::create($validated);

        return redirect()->route('admin.bola')->with('success', 'Bola berhasil ditambahkan.');
    }

    public function update(Request $request, Bola $bola): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        if ($request->hasFile('foto')) {
            $photoPath = $request->file('foto')->store('bola', 'public');

            if ($photoPath === false) {
                return back()->withInput()->with('error', 'Foto bola gagal disimpan. Silakan coba kembali.');
            }

            $this->deleteStoredPhoto($bola->foto);
            $validated['foto'] = $photoPath;
        }

        $bola->update($validated);

        return redirect()->route('admin.bola')->with('success', 'Data bola berhasil diperbarui.');
    }

    public function destroy(Bola $bola): RedirectResponse
    {
        $this->deleteStoredPhoto($bola->foto);
        $bola->delete();

        return redirect()->route('admin.bola')->with('success', 'Bola berhasil dihapus.');
    }

    public function toggle(Bola $bola): RedirectResponse
    {
        $bola->update(['status' => $bola->isAktif() ? 0 : 1]);

        return redirect()->route('admin.bola')->with('success', 'Status bola berhasil diperbarui.');
    }

    /**
     * @return array<string, list<string>>
     */
    private function rules(bool $photoRequired = false): array
    {
        return [
            'bola' => ['required', 'string', 'max:30'],
            'grade' => ['required', 'string', 'max:30'],
            'harga' => ['required', 'numeric', 'min:0'],
            'jml_jam' => ['required', 'string', 'max:11'],
            'foto' => [$photoRequired ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    private function deleteStoredPhoto(?string $photoPath): void
    {
        if ($photoPath !== null && str_starts_with($photoPath, 'bola/')) {
            Storage::disk('public')->delete($photoPath);
        }
    }
}
