<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Karturankteam extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $kode_main = $request->query('kode_main');
        $kode = $request->query('kode');
        $team = $request->query('team');

        // Cari team berdasarkan nama_team
        $tt = DB::table('tbl_teamrankteam')
            ->where('nama_team', $team)
            ->first();

        if (!$tt) {
            return response()->json([], 200);
        }

        // Ambil kartu berdasarkan kode_main, kode, dan kode_team
        $data = DB::table('tbl_kartu_rankteam')
            ->where('kode_main', $kode_main)
            ->where('kode', $kode)
            ->where('kode_team', $tt->kode)
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
