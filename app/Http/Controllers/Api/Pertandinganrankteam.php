<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Pertandinganrankteam extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil session yang status = 1
        $session = DB::table('tbl_session')
            ->where('status', 1)
            ->first();

        if (!$session) {
            return response()->json([], 200);
        }

        $id_user = $request->query('id_user');

        if ($id_user === null) {
            // Jika id_user tidak dikirim, ambil pertandingan berdasarkan session aktif
            $data = DB::table('tbl_pertandingan_rankteam')
                ->where('session', $session->session)
                ->orderBy('id', 'desc')
                ->get();

            return response()->json($data, 200);
        }

        // Jika id_user dikirim, cari team berdasarkan id_user
        $team = DB::table('tbl_teamrankteam')
            ->where('id_user', $id_user)
            ->first();

        if (!$team) {
            return response()->json([], 200);
        }

        // Ambil data pertandingan berdasarkan session dan kode team
        $data = DB::table('tbl_main_rankteam')
            ->where('session', $session->session)
            ->where('kode_team', $team->kode)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

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
