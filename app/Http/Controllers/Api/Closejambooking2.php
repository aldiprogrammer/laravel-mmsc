<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Closejambooking2 extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $tgl = $request->query('tgl');
        $idlap = $request->query('lap');
        $idjam = $request->query('idjam');

        $cek = DB::table('tbl_tutupjammain2')
            ->where('tgl', $tgl)
            ->where('id_jam', $idjam)
            ->where('id_lap', $idlap)
            ->exists();

        return response()->json([
            'status' => $cek
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
