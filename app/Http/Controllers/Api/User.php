<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DataUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class User extends Controller
{
    // GET: Cek email atau ambil profil berdasarkan ID auth
    public function index_get(Request $request)
    {
        $email = $request->query('email');

        if ($email !== null && $email !== '') {
            $cek = DB::table('tbl_profil')
                ->where('email', $email)
                ->first();

            if ($cek) {
                return response()->json([
                    'status' => 'tersedia'
                ], 200);
            }

            return response()->json([
                'status' => 'tidak tersedia'
            ], 404);
        }

        $id_auth = $request->query('id');

        if ($id_auth === null || $id_auth === '') {
            return response()->json([
                'message' => 'Parameter id wajib diisi'
            ], 400);
        }

        $cek = DB::table('tbl_profil')
            ->where('id_auth', $id_auth)
            ->first();

        if ($cek) {
            return response()->json($cek, 200);
        }

        return response()->json([
            'message' => 'salah'
        ], 404);
    }

    // POST: Registrasi user Google atau akun biasa
    public function index_post(Request $request)
    {
        $idauth = $request->input('idauth');

        // Registrasi melalui Google
        if ($idauth !== null && $idauth !== '') {
            $request->validate([
                'idauth' => 'required|string|max:255',
                'nama'   => 'required|string|max:255',
                'email'  => 'required|email|max:255',
            ]);

            $kode_user = $this->buatKodeUser();

            $data = [
                'kode_user' => $kode_user,
                'id_auth'   => $idauth,
                'nama'      => $request->input('nama'),
                'email'     => $request->input('email'),
                'status'    => 1,
            ];

            $profil = [
                'kode_user' => $kode_user,
                'id_auth'   => $idauth,
                'nama'      => $request->input('nama'),
                'email'     => $request->input('email'),
            ];
        } else {
            // Registrasi melalui formulir akun biasa
            $request->validate([
                'nama'     => 'required|string|max:255',
                'email'    => 'required|email|max:255',
                'nohp'     => 'required|string|max:30',
                'pass'     => 'required|string|min:8',
                'kodeveri' => 'nullable|string|max:255',
            ]);

            $kode_user = $this->buatKodeUser();
            $auth = 'auth-' . Str::random(32);

            $data = [
                'kode_user'       => $kode_user,
                'id_auth'         => $auth,
                'nama'            => $request->input('nama'),
                'email'           => $request->input('email'),
                'nohp'            => $request->input('nohp'),
                'pass'            => Hash::make($request->input('pass')),
                'kode_verifikasi' => $request->input('kodeveri'),
                'status'          => 1,
            ];

            $profil = [
                'kode_user' => $kode_user,
                'id_auth'   => $auth,
                'nama'      => $request->input('nama'),
                'email'     => $request->input('email'),
                'nohp'      => $request->input('nohp'),
            ];
        }

        // Simpan kedua tabel dalam satu transaksi
        DB::transaction(function () use ($data, $profil) {
            DB::table('tbl_user')->insert($data);
            DB::table('tbl_profil')->insert($profil);
        });

        // Jangan kirim password ke aplikasi
        unset($data['pass']);

        return response()->json($data, 201);
    }

    // Membuat kode user yang belum digunakan
    private function buatKodeUser()
    {
        do {
            $kode = 'user-' . random_int(100000, 999999);
        } while (
            DB::table('tbl_user')->where('kode_user', $kode)->exists()
        );

        return $kode;
    }
}
