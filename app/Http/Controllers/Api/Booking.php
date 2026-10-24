<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Booking extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Waktu Jakarta
        $now = Carbon::now('Asia/Jakarta');

        $jam = (int) $now->format('H');
        $date = $now->format('Y-m-d');

        $id = $request->query('id');

        // Ambil booking lapangan untuk hari ini
        $booking = DB::table('tbl_booking')
            ->where('id_lapangan', $id)
            ->where('tgl', $date)
            ->get();

        foreach ($booking as $data) {
            $jamMulai = (int) $data->jam_mulai;
            $jamSelesai = (int) $data->jam_selesai;

            if ($jamMulai == $jam) {
                $status = 'Mulai';
            } elseif ($jamMulai > $jam) {
                $status = 'Menunggu';
            } elseif ($jamSelesai <= $jam) {
                $status = 'Selesai';
            } else {
                $status = 'Menunggu';
            }

            // Update status booking
            DB::table('tbl_booking')
                ->where('id', $data->id)
                ->where('tgl', $date)
                ->update([
                    'status' => $status,
                ]);
        }

        // Ambil kembali data booking setelah status diperbarui
        $bk = DB::table('tbl_booking')
            ->where('id_lapangan', $id)
            ->where('tgl', $date)
            ->get();

        return response()->json($bk, 200);
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
