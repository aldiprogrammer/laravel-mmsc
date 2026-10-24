<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Cekbooksatujam extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $id = $request->query('idlap');
        $tgl = $request->query('tgl');

        // Ambil booking yang belum dihapus dan pembayaran bukan 400
        $cek = DB::table('tbl_booking')
            ->where('status_pembayaran', '!=', 400)
            ->where('id_lapangan', $id)
            ->where('tgl', $tgl)
            ->where('status_hapus', 0)
            ->get();

        foreach ($cek as $data) {

            // Cek data member karir team
            $cekmm = DB::table('tbl_member_karir_team')
                ->where('kode_main', $data->kode_booking)
                ->where('status_pembayaran', '!=', 400)
                ->exists();

            if (
                $data->jam_mulai == '23.00' &&
                $data->jam_selesai == '24.00'
            ) {
                return response()->json([
                    'status' => 'true'
                ], 200);
            } elseif (
                $data->jam_mulai == '22.00' &&
                $data->jam_selesai == '23.00'
            ) {
                return response()->json([
                    'status' => 'true'
                ], 200);
            } elseif (
                $data->jam_mulai == '22.00' &&
                $data->jam_selesai == '24.00' &&
                $cekmm
            ) {
                return response()->json([
                    'status' => '2 jam'
                ], 200);
            } elseif (
                $data->jam_mulai == '22.00' &&
                $data->jam_selesai == '24.00' &&
                $data->user != 'Memberkarir team'
            ) {
                return response()->json([
                    'status' => '2 jam'
                ], 200);
            } else {
                return response()->json([
                    'status' => 'false'
                ], 200);
            }
        }

        // Jika tidak ada booking yang cocok
        return response()->json([
            'status' => 'false'
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
