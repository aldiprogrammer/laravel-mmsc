<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Tutupjammain extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $idlap = $request->query('idlap');
        $tgl = $request->query('tgl');

        $data = DB::table('tbl_tutupjammain')
            ->where('id_lap', $idlap)
            ->where('tgl', $tgl)
            ->first();

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
