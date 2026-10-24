<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Cekbookinglapangan2 extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        // Parameter request
        $idjam = $request->query('idjam');
        $idlap = $request->query('idlap');
        $date = $request->query('tgl');

        // Ambil data jam booking berdasarkan ID
        $cekid = DB::table('tbl_jambooking_lapangan')
            ->where('id', $idjam)
            ->first();

        // Jika ID jam tidak ditemukan
        if (!$cekid) {
            return response()->json([
                'status' => false
            ], 200);
        }

        $jam_mulai = $cekid->jam_mulai;

        // Cek booking pada lapangan, tanggal, jam, dan status pembayaran
        $cek = DB::table('tbl_booking')
            ->where('id_lapangan', $idlap)
            ->where('tgl', $date)
            ->where('jam_mulai', $jam_mulai)
            ->where('status_pembayaran', 200)
            ->first();

        if ($cek) {
            return response()->json([
                'data' => $cek,
                'status' => true
            ], 200);
        }

        return response()->json([
            'status' => false
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
