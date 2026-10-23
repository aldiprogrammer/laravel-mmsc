<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DataUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $search = trim((string) $request->query('q', ''));
        $perPage = min(max($request->integer('per_page', 20), 1), 100);

        $users = DataUser::query()
            ->where(function ($query): void {
                $query->whereNull('status_hapus')->orWhere('status_hapus', 0);
            })
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($userQuery) use ($search): void {
                    $userQuery->where('nama', 'like', '%'.$search.'%')
                        ->orWhere('kode_user', 'like', '%'.$search.'%');
                });
            })
            ->select(['id', 'kode_user', 'nama', 'status', 'date'])
            ->orderByDesc('id')
            ->paginate($perPage);

        return response()->json([
            'data' => $users->items(),
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ],
        ]);
    }

    public function show(Request $request, int $user): JsonResponse
    {
        /** @var DataUser $authenticatedUser */
        $authenticatedUser = $request->attributes->get('api_user');

        if ((int) $authenticatedUser->id !== $user) {
            return response()->json(['message' => 'Anda tidak dapat mengakses data user lain.'], 403);
        }

        return response()->json([
            'data' => [
                'id' => $authenticatedUser->id,
                'kode_user' => $authenticatedUser->kode_user,
                'nama' => $authenticatedUser->nama,
                'email' => $authenticatedUser->email,
                'nohp' => $authenticatedUser->nohp,
                'status' => $authenticatedUser->status,
                'date' => $authenticatedUser->date,
            ],
        ]);
    }

    public function checkEmail(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:50'],
        ]);

        $exists = DataUser::query()->where('email', $validated['email'])->exists();

        return response()->json([
            'status' => $exists ? 'tidak tersedia' : 'tersedia',
            'available' => ! $exists,
        ]);
    }
}
