<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Pesanbookingspesial2 extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ranksolo = DB::table('tbl_fungame')
            ->where('status', 1)
            ->whereDate('tgl_main', '>=', date('Y-m-d'))
            ->orderBy('id', 'desc')
            ->first();

        if ($ranksolo) {

            $kode_main = $ranksolo->kode_main;

            $cek1 = DB::table('tbl_member_karir')
                ->where('kode_main', $kode_main)
                ->where('sesi', 1)
                ->whereNotIn('status_pembayaran', [400])
                ->first();

            $cek2 = DB::table('tbl_member_karir')
                ->where('kode_main', $kode_main)
                ->where('sesi', 2)
                ->whereNotIn('status_pembayaran', [400])
                ->first();

            if ($cek1 && !$cek2) {

                return response()->json([
                    'pesan' => 'kosong'
                ], 200);
            } elseif ($cek2 && !$cek1) {

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
