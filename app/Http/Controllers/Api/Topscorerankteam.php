<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Topscorerankteam extends Controller
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

        // Cari berdasarkan ID pemain
        if ($request->query('idpemain') !== null) {

            $data = DB::table('tbl_jml_goal_rankteam')
                ->where('id_pemain', $request->query('idpemain'))
                ->where('session', $sessionAktif)
                ->first();

            return response()->json($data, 200);
        }

        // Ambil daftar pencetak gol berdasarkan pool
        $query = DB::table('tbl_jml_goal_rankteam')
            ->where('id_pemain', '!=', 0)
            ->where('status', '!=', 1)
            ->where('session', $sessionAktif);

        $pool = $request->query('pool');

        if ($pool === 'A' || $pool === 'B') {
            $query->where('pool', $pool);
        }

        $data = $query
            ->orderByDesc('jml')
            ->limit(50)
            ->get();

        return response()->json($data, 200);
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
