<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Jammainsatujam extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'idlap' => ['required', 'integer'],
            'tgl' => ['required', 'date_format:Y-m-d'],
        ]);

        $id = $request->query('idlap');
        $tgl = $request->query('tgl');

        // Menentukan kategori hari berdasarkan periode tanggal
        if ($tgl > '2024-12-31') {
            $jumatsabtu = 'jumat-sabtu';
            $seninkamis = 'senin-kamis';
        } else {
            $jumatsabtu = 'jumat-sabtu-new';
            $seninkamis = 'senin-kamis-new';
        }

        // Mendapatkan nama hari
        $hari = Carbon::createFromFormat('Y-m-d', $tgl)
            ->locale('en')
            ->dayName;

        $query = DB::table('tbl_jambooking_lapangan')
            ->where('lapangan', $id)
            ->where('status', 1);

        if (in_array($hari, ['Friday', 'Saturday'])) {

            $query->where('hari', $jumatsabtu);

            // Pengecualian jam pada tanggal tertentu
            if (in_array($tgl, [
                '2024-12-16',
                '2024-12-17',
                '2024-12-18',
                '2024-12-19',
                '2024-12-20',
                '2024-12-21',
            ])) {
                $query->where('jam_mulai', '!=', '22.00')
                    ->where('jam_berakhir', '!=', '23.00');
            }
        } else {
            $query->where('hari', $seninkamis);
        }

        $data = $query
            ->orderBy('jam_mulai', 'asc')
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
