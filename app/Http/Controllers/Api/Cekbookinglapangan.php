<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Cekbookinglapangan extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');

        $tgl = $request->query('tgl');
        $idlap = $request->query('id_lap');

        $cek = DB::table('tbl_booking')
            ->where('tgl', $tgl)
            ->where('id_lapangan', $idlap)
            ->get();

        return response()->json($cek, 200);
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
