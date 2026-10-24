<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Addpelunasan extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $notif = $request->query('notif');

        if ($notif !== null) {

            $iduser = $request->query('user');

            $booking = DB::table('tbl_booking')
                ->where('status_pelunasan', 0)
                ->where('status_pembayaran', 200)
                ->where('sistem_pembayaran', 'Dp')
                ->where('iduser', $iduser)
                ->orderBy('id', 'desc')
                ->limit(2)
                ->get();

            return response()->json($booking, 200);
        } else {

            $kode = $request->query('kode');

            $data = DB::table('tbl_pelunasan_booking')
                ->where('kode_booking', $kode)
                ->orderBy('id', 'desc')
                ->first();

            return response()->json($data, 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = [
            'id_user' => $request->input('id_user'),
            'kode_booking' => $request->input('kode_booking'),
            'atas_nama' => $request->input('atas_nama'),
            'bank' => $request->input('bank'),
            'bukti' => $request->input('bukti'),
            'sisa_pembayaran' => $request->input('sisa_pembayaran'),
            'status' => 201,
        ];

        DB::table('tbl_pelunasan_booking')->insert($data);

        return response()->json([
            'message' => true
        ], 201);
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
