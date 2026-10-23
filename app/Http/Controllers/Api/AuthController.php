<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApiAccessToken;
use App\Models\DataUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:30', 'unique:tbl_user,email'],
            'nohp' => ['nullable', 'string', 'max:13'],
            'password' => ['required', 'string', 'min:10', 'confirmed'],
        ]);

        $user = DB::transaction(function () use ($validated): DataUser {
            $idAuth = (string) Str::uuid();
            $kodeUser = 'user-'.Str::lower(Str::random(10));

            $userId = DB::table('tbl_user')->insertGetId([
                'kode_user' => $kodeUser,
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'nohp' => $validated['nohp'] ?? '',
                'id_auth' => $idAuth,
                'pass' => Hash::make($validated['password']),
                'kode_verifikasi' => Str::random(20),
                'status' => 1,
                'status_hapus' => 0,
                'date' => now(),
            ]);

            DB::table('tbl_profil')->insert([
                'kode_user' => $kodeUser,
                'id_auth' => $idAuth,
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'nohp' => $validated['nohp'] ?? '',
                'date' => now(),
            ]);

            return DataUser::query()
                ->select(['id', 'kode_user', 'nama', 'email', 'nohp', 'status', 'date'])
                ->findOrFail($userId);
        });

        return $this->issueToken($user, 201);
    }

    public function login(Request $request): JsonResponse
    {
        $request->mergeIfMissing(['password' => $request->input('pass')]);

        $validated = $request->validate([
            'email' => ['required', 'email', 'max:50'],
            'password' => ['required', 'string', 'max:255'],
        ]);

        $user = DataUser::query()
            ->where('email', $validated['email'])
            ->where('status', 1)
            ->where(function ($query): void {
                $query->whereNull('status_hapus')->orWhere('status_hapus', 0);
            })
            ->first();
        $storedPassword = (string) ($user?->pass ?? '');

        if ($user === null || ! $this->passwordMatches($storedPassword, $validated['password'])) {
            return response()->json(['message' => 'Email atau password tidak sesuai.'], 401);
        }

        if (! $this->isHashedPassword($storedPassword)) {
            DB::table('tbl_user')->where('id', $user->id)->update([
                'pass' => Hash::make($validated['password']),
            ]);
        }

        return $this->issueToken($user->only(['id', 'kode_user', 'nama', 'email', 'nohp', 'status', 'date']));
    }

    public function logout(Request $request): JsonResponse
    {
        $token = $request->attributes->get('api_access_token');

        if ($token instanceof ApiAccessToken) {
            $token->delete();
        }

        return response()->json(['message' => 'Berhasil logout.']);
    }

    public function me(Request $request): JsonResponse
    {
        /** @var DataUser|null $user */
        $user = $request->attributes->get('api_user');

        return response()->json(['data' => $this->userPayload($user)]);
    }

    private function issueToken(DataUser|array $user, int $status = 200): JsonResponse
    {
        $plainTextToken = bin2hex(random_bytes(32));
        $expiresAt = now()->addDays(30);

        ApiAccessToken::query()->create([
            'user_id' => is_array($user) ? $user['id'] : $user->id,
            'token_hash' => hash('sha256', $plainTextToken),
            'expires_at' => $expiresAt,
        ]);

        $userData = is_array($user) ? $user : $this->userPayload($user);

        return response()->json([
            'data' => $userData,
            'token_type' => 'Bearer',
            'access_token' => $plainTextToken,
            'expires_at' => $expiresAt->toIso8601String(),
        ], $status);
    }

    /**
     * @return array{id: int|string, kode_user: string, nama: string, email: string, nohp: string, status: int|string, date: string|null}
     */
    private function userPayload(DataUser $user): array
    {
        return [
            'id' => $user->id,
            'kode_user' => $user->kode_user,
            'nama' => $user->nama,
            'email' => $user->email,
            'nohp' => $user->nohp,
            'status' => $user->status,
            'date' => $user->date,
        ];
    }

    private function passwordMatches(string $storedPassword, string $submittedPassword): bool
    {
        if ($storedPassword === '') {
            return false;
        }

        if ($this->isHashedPassword($storedPassword)) {
            return Hash::check($submittedPassword, $storedPassword);
        }

        return hash_equals($storedPassword, $submittedPassword);
    }

    private function isHashedPassword(string $password): bool
    {
        return preg_match('/^\$(2y|2a|2b|argon2i|argon2id)\$/', $password) === 1;
    }
}
