<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rompi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class RompiController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q'));

        $rompi = Rompi::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($rompiQuery) use ($search) {
                    $rompiQuery->where('rompi', 'like', '%'.$search.'%')
                        ->orWhere('warna', 'like', '%'.$search.'%')
                        ->orWhere('grade', 'like', '%'.$search.'%');
                });
            })
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.rompi.index', [
            'rompi' => $rompi,
            'search' => $search,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules(true));
        $photoPath = $request->file('foto')->store('rompi', 'public');

        if ($photoPath === false) {
            return back()->withInput()->with('error', 'Foto rompi gagal disimpan. Silakan coba kembali.');
        }

        $validated['foto'] = $photoPath;
        $validated['status'] = 1;

        Rompi::create($validated);

        return redirect()->route('admin.rompi')->with('success', 'Rompi berhasil ditambahkan.');
    }

    public function update(Request $request, Rompi $rompi): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        if ($request->hasFile('foto')) {
            $photoPath = $request->file('foto')->store('rompi', 'public');

            if ($photoPath === false) {
                return back()->withInput()->with('error', 'Foto rompi gagal disimpan. Silakan coba kembali.');
            }

            $this->deleteStoredPhoto($rompi->foto);
            $validated['foto'] = $photoPath;
        }

        $rompi->update($validated);

        return redirect()->route('admin.rompi')->with('success', 'Data rompi berhasil diperbarui.');
    }

    public function destroy(Rompi $rompi): RedirectResponse
    {
        $this->deleteStoredPhoto($rompi->foto);
        $rompi->delete();

        return redirect()->route('admin.rompi')->with('success', 'Rompi berhasil dihapus.');
    }

    public function toggle(Rompi $rompi): RedirectResponse
    {
        $rompi->update(['status' => $rompi->isAktif() ? 0 : 1]);

        return redirect()->route('admin.rompi')->with('success', 'Status rompi berhasil diperbarui.');
    }

    /**
     * @return array<string, list<string>>
     */
    private function rules(bool $photoRequired = false): array
    {
        return [
            'rompi' => ['required', 'string', 'max:50'],
            'warna' => ['required', 'string', 'max:30'],
            'grade' => ['required', 'string', 'max:15'],
            'harga' => ['required', 'numeric', 'min:0'],
            'jml_jam' => ['required', 'string', 'max:11'],
            'foto' => [$photoRequired ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    private function deleteStoredPhoto(?string $photoPath): void
    {
        if ($photoPath !== null && str_starts_with($photoPath, 'rompi/')) {
            Storage::disk('public')->delete($photoPath);
        }
    }
}
