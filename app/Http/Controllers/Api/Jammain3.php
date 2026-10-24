<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Jammain3 extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tgl = $request->query('tgl');
        $lap = $request->query('lapangan');

        // Validasi parameter
        $request->validate([
            'tgl' => ['required', 'date_format:Y-m-d'],
            'lapangan' => ['required', 'integer'],
        ]);

        // Ambil nama hari berdasarkan tanggal
        $hari = Carbon::createFromFormat('Y-m-d', $tgl)
            ->locale('en')
            ->dayName;

        // Pengaturan berdasarkan periode tanggal
        if ($tgl > '2024-12-31') {

            if (in_array($tgl, [
                '2025-03-30',
                '2025-04-01',
                '2025-04-02',
                '2025-04-03',
            ])) {
                $seninjumat = 'senin-jumata';
                $jumat = 'jumata';
                $sabtuminggu = 'sabtu-minggua';
                $minggu = 'minggua';
                $minggubaru = 'minggubarua';
                $status = 13;
            } else {
                $seninjumat = 'senin-jumat';
                $jumat = 'senin-jumat';
                $sabtuminggu = 'sabtu-minggu';
                $minggu = 'sabtu-minggu';
                $minggubaru = 'minggubaru';
                $status = 1;
            }
        } else {
            $seninjumat = 'senin-jumat-new';
            $jumat = 'jumat-new';
            $sabtuminggu = 'sabtu-minggu-new';
            $minggu = 'minggu-new';
            $minggubaru = 'minggubaru-new';
            $status = 0;
        }

        // Lapangan 1, lapangan 5, dan lapangan lainnya
        // menggunakan logika pemilihan jam yang sama.
        $hariBooking = in_array($hari, ['Monday', 'Tuesday'])
            ? 'senin-selasa-diskon'
            : $seninjumat;

        $jam = DB::table('tbl_jambooking_lapangan')
            ->where('hari', $hariBooking)
            ->where('lapangan', $lap)
            ->where('status', $status)
            ->orderBy('jam_mulai', 'asc')
            ->get();

        return response()->json($jam, 200);
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
