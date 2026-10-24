<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class Pemainrankteam2 extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $kuota = DB::table('tbl_jml_pemain_rankteam')->first();

        // Cek jumlah pemain
        if ($request->query('jmlpemain') == 'true') {

            $kode_team = DB::table('tbl_teamrankteam')
                ->where('id_user', $request->query('id_user'))
                ->first();

            if (!$kode_team) {
                return response()->json([
                    'message' => 'false'
                ], 404);
            }

            $jml = DB::table('tbl_pemainrankteam')
                ->where('kode_team', $kode_team->kode)
                ->count();

            $data2 = DB::table('tbl_teamrankteam')
                ->where('kode', $kode_team->kode)
                ->first();

            return response()->json([
                'jml' => min($jml, 25),
                'data' => $data2
            ], 200);

            // Ambil pemain berdasarkan ID
        } elseif ($request->query('idpemain')) {

            $data = DB::table('tbl_pemainrankteam')
                ->where('id', $request->query('idpemain'))
                ->first();

            return response()->json($data, 200);

            // Ambil pemain berdasarkan nama tim
        } elseif ($request->query('team')) {

            $data = DB::table('tbl_pemainrankteam')
                ->where('team', $request->query('team'))
                ->limit($kuota->jml ?? 0)
                ->get();

            return response()->json($data, 200);

            // Ambil semua pemain milik user
        } else {

            $team = DB::table('tbl_teamrankteam')
                ->where('id_user', $request->query('id_user'))
                ->first();

            if (!$team) {
                return response()->json([
                    'message' => 'error'
                ], 404);
            }

            $data = DB::table('tbl_pemainrankteam')
                ->where('kode_team', $team->kode)
                ->get();

            if ($data->isNotEmpty()) {
                return response()->json($data, 200);
            }

            return response()->json([
                'message' => 'error'
            ], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $kode_team = DB::table('tbl_teamrankteam')
            ->where('id_user', $request->input('id_user'))
            ->first();

        if (!$kode_team) {
            return response()->json([
                'message' => 'team tidak ditemukan'
            ], 404);
        }

        $jml = DB::table('tbl_pemainrankteam')
            ->where('kode_team', $kode_team->kode)
            ->count();

        $kuota = DB::table('tbl_jml_pemain_rankteam')
            ->first();

        if (!$kuota) {
            return response()->json([
                'message' => 'kuota tidak ditemukan'
            ], 404);
        }

        // Cek kuota pemain
        if ($jml >= $kuota->jml) {
            return response()->json([
                'kuota' => 'full'
            ], 201);
        }

        $nickname = $request->input('nickname');

        $nc = ($nickname === null || $nickname === '')
            ? ''
            : $nickname;

        // Cek data pemain yang sama
        $cek = DB::table('tbl_pemainrankteam')
            ->where('kode_team', $kode_team->kode)
            ->where('nama', $request->input('nama_pemain'))
            ->where('posisi', $request->input('posisi'))
            ->where('no_punggung', $request->input('no_punggung'))
            ->where('nik', $request->input('nik'))
            ->exists();

        if ($cek) {
            return response()->json([
                'message' => 'data sama'
            ], 201);
        }

        $data = [
            'kode_team'   => $kode_team->kode,
            'nama'        => $request->input('nama_pemain'),
            'team'        => $kode_team->nama_team,
            'posisi'      => $request->input('posisi'),
            'no_punggung' => $request->input('no_punggung'),
            'nik'         => $request->input('nik'),
            'nickname'    => $nc,
        ];

        try {
            $add = DB::table('tbl_pemainrankteam')->insert($data);

            return response()->json($add, 201);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'message' => 'gagal menyimpan data'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
