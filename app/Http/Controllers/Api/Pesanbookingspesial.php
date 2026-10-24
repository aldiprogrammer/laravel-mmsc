<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Pesanbookingspesial extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = DB::table('tbl_fungame_rankteam')
            ->whereDate('tgl_main', '>=', date('Y-m-d'))
            ->where('status', '1')
            ->get();

        if ($data->isNotEmpty()) {
            foreach ($data as $get) {

                $jml = DB::table('tbl_member_karir_team')
                    ->where('kode_main', $get->kode_main)
                    ->where('status_pembayaran', '!=', 400)
                    ->count();

                $sesi1 = DB::table('tbl_member_karir_team')
                    ->where('kode_main', $get->kode_main)
                    ->where('sesi', 1)
                    ->where('status_pembayaran', 200)
                    ->count();

                $sesi2 = DB::table('tbl_member_karir_team')
                    ->where('kode_main', $get->kode_main)
                    ->where('sesi', 2)
                    ->where('status_pembayaran', 200)
                    ->count();

                if ($jml == 2) {
                    if ($sesi1 == 2 || $sesi2 == 2) {
                        return response()->json([
                            'pesan' => 'kosong'
                        ], 200);
                    } else {
                        return response()->json([
                            'pesan' => 'kosong'
                        ], 200);
                    }
                } else {
                    return response()->json([
                        'pesan' => 'kosong'
                    ], 200);
                }
            }
        }

        return response()->json([
            'pesan' => 'kosong'
        ], 200);
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
