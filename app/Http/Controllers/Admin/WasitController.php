<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wasit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class WasitController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q'));

        $wasit = Wasit::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($wasitQuery) use ($search) {
                    $wasitQuery->where('nama', 'like', '%'.$search.'%')
                        ->orWhere('nohp', 'like', '%'.$search.'%')
                        ->orWhere('lisensi', 'like', '%'.$search.'%');
                });
            })
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.wasit.index', [
            'wasit' => $wasit,
            'search' => $search,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules(true));
        $photoPath = $request->file('foto')->store('wasit', 'public');

        if ($photoPath === false) {
            return back()->withInput()->with('error', 'Foto wasit gagal disimpan. Silakan coba kembali.');
        }

        $validated['foto'] = $photoPath;
        $validated['status'] = 1;

        Wasit::create($validated);

        return redirect()->route('admin.wasit')->with('success', 'Wasit berhasil ditambahkan.');
    }

    public function update(Request $request, Wasit $wasit): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        if ($request->hasFile('foto')) {
            $photoPath = $request->file('foto')->store('wasit', 'public');

            if ($photoPath === false) {
                return back()->withInput()->with('error', 'Foto wasit gagal disimpan. Silakan coba kembali.');
            }

            $this->deleteStoredPhoto($wasit->foto);
            $validated['foto'] = $photoPath;
        }

        $wasit->update($validated);

        return redirect()->route('admin.wasit')->with('success', 'Data wasit berhasil diperbarui.');
    }

    public function destroy(Wasit $wasit): RedirectResponse
    {
        $this->deleteStoredPhoto($wasit->foto);

        $wasit->delete();

        return redirect()->route('admin.wasit')->with('success', 'Wasit berhasil dihapus.');
    }

    public function toggle(Wasit $wasit): RedirectResponse
    {
        $wasit->update(['status' => $wasit->isAktif() ? 0 : 1]);

        return redirect()->route('admin.wasit')->with('success', 'Status wasit berhasil diperbarui.');
    }

    /**
     * @return array<string, list<string>>
     */
    private function rules(bool $photoRequired = false): array
    {
        return [
            'nama' => ['required', 'string', 'max:20'],
            'nohp' => ['required', 'string', 'max:13'],
            'jk' => ['required', 'in:Laki - laki,Perempuan'],
            'alamat' => ['required', 'string'],
            'harga' => ['required', 'integer', 'min:0'],
            'jml_jam' => ['required', 'string', 'max:11'],
            'lisensi' => ['nullable', 'string', 'max:50'],
            'foto' => [$photoRequired ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    private function deleteStoredPhoto(?string $photoPath): void
    {
        if ($photoPath !== null && str_starts_with($photoPath, 'wasit/')) {
            Storage::disk('public')->delete($photoPath);
        }
    }
}
