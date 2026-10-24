<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Pointrankteam extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil session aktif
        $session = DB::table('tbl_session')
            ->where('status', 1)
            ->first();

        if (!$session) {
            return response()->json([
                'message' => 'session tidak ditemukan'
            ], 404);
        }

        $sessionAktif = $session->session;

        // Cari berdasarkan ID user
        if ($request->query('id_user') !== null) {

            $team = DB::table('tbl_teamrankteam')
                ->where('id_user', $request->query('id_user'))
                ->first();

            if (!$team) {
                return response()->json(null, 200);
            }

            $data = DB::table('tbl_point_rankteam')
                ->where('kode_team', $team->kode)
                ->where('session', $sessionAktif)
                ->first();

            return response()->json($data, 200);
        }

        // Cari berdasarkan nama team
        elseif ($request->query('team') !== null) {

            $data = DB::table('tbl_point_rankteam')
                ->where('team', $request->query('team'))
                ->where('session', $sessionAktif)
                ->first();

            return response()->json($data, 200);
        }

        // Cari berdasarkan ID pemain
        elseif ($request->query('idpemain') !== null) {

            $pemain = DB::table('tbl_pemainrankteam')
                ->where('id', $request->query('idpemain'))
                ->first();

            if (!$pemain) {
                return response()->json(null, 200);
            }

            $data = DB::table('tbl_point_rankteam')
                ->where('kode_team', $pemain->kode_team)
                ->where('session', $sessionAktif)
                ->first();

            return response()->json($data, 200);
        }

        // Ambil klasemen berdasarkan pool
        else {

            $query = DB::table('tbl_point_rankteam')
                ->where('session', $sessionAktif)
                ->where('status', '!=', 1);

            $pool = $request->query('pool');

            if ($pool === 'A' || $pool === 'B') {
                $query->where('pool', $pool);
            }

            $data = $query
                ->orderByDesc('point')
                ->orderByDesc('selisih_goal')
                ->orderByDesc('play')
                ->orderByDesc('goal')
                ->get();

            return response()->json($data, 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
