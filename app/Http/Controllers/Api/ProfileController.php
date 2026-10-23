<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DataUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        /** @var DataUser $user */
        $user = $request->attributes->get('api_user');

        $profile = DB::table('tbl_profil')
            ->where('id_auth', $user->id_auth)
            ->first([
                'kode_user', 'nama', 'email', 'alamat', 'jk', 'tgl_lahir',
                'nohp', 'posisi', 'image',
            ]);

        abort_if($profile === null, 404, 'Profil tidak ditemukan.');

        return response()->json(['data' => $profile]);
    }

    public function update(Request $request): JsonResponse
    {
        /** @var DataUser $user */
        $user = $request->attributes->get('api_user');
        $validated = $request->validate([
            'nama' => ['sometimes', 'string', 'max:30'],
            'alamat' => ['sometimes', 'nullable', 'string', 'max:2000'],
            'jk' => ['sometimes', 'nullable', 'in:Laki-laki,Perempuan'],
            'tgl_lahir' => ['sometimes', 'nullable', 'date_format:Y-m-d'],
            'nohp' => ['sometimes', 'nullable', 'string', 'max:13'],
            'posisi' => ['sometimes', 'nullable', 'string', 'max:20'],
            'image' => ['sometimes', 'nullable', 'url:http,https', 'max:2000'],
        ]);

        abort_if($validated === [], 422, 'Tidak ada data profil untuk diperbarui.');

        $profileExists = DB::table('tbl_profil')
            ->where('id_auth', $user->id_auth)
            ->exists();

        abort_if(! $profileExists, 404, 'Profil tidak ditemukan.');

        DB::table('tbl_profil')
            ->where('id_auth', $user->id_auth)
            ->update($validated);

        return $this->show($request);
    }
}
