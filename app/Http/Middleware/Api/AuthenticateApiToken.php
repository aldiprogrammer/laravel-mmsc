<?php

namespace App\Http\Middleware\Api;

use App\Models\ApiAccessToken;
use App\Models\DataUser;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $plainTextToken = $request->bearerToken();

        if ($plainTextToken === null || $plainTextToken === '') {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $token = ApiAccessToken::query()
            ->where('token_hash', hash('sha256', $plainTextToken))
            ->where('expires_at', '>', now())
            ->first();

        if ($token === null) {
            return response()->json(['message' => 'Token tidak valid atau sudah kedaluwarsa.'], 401);
        }

        $user = DataUser::query()
            ->where('id', $token->user_id)
            ->where('status', 1)
            ->where(function ($query): void {
                $query->whereNull('status_hapus')->orWhere('status_hapus', 0);
            })
            ->first(['id', 'kode_user', 'id_auth', 'nama', 'email', 'nohp', 'status', 'status_hapus', 'date']);

        if ($user === null) {
            $token->delete();

            return response()->json(['message' => 'Akun tidak aktif.'], 401);
        }

        $token->forceFill(['last_used_at' => now()])->save();
        $request->attributes->set('api_user', $user);
        $request->attributes->set('api_access_token', $token);

        return $next($request);
    }
}
