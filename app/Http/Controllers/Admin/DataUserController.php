<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DataUserController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q'));

        $users = DataUser::query()
            ->select(['id', 'kode_user', 'nama', 'email', 'nohp', 'status', 'status_hapus', 'date'])
            ->where(function ($query) {
                $query->whereNull('status_hapus')
                    ->orWhere('status_hapus', 0);
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($userQuery) use ($search) {
                    $userQuery->where('nama', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%')
                        ->orWhere('nohp', 'like', '%'.$search.'%')
                        ->orWhere('kode_user', 'like', '%'.$search.'%');
                });
            })
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.data-user.index', [
            'users' => $users,
            'search' => $search,
        ]);
    }

    public function destroy(DataUser $dataUser): RedirectResponse
    {
        $dataUser->status_hapus = 1;
        $dataUser->save();

        return back()->with('success', 'Data user berhasil dihapus.');
    }
}
