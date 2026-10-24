<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Gamerankteam extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Jika kode_main dikirim, cari berdasarkan kode_main
        if ($request->query('kode_main') !== null) {
            $data = DB::table('tbl_fungame_rankteam')
                ->where('kode_main', $request->query('kode_main'))
                ->first();

            return response()->json($data, 200);
        }

        // Jika kode_main tidak dikirim, ambil data status = 1 terbaru
        $data = DB::table('tbl_fungame_rankteam')
            ->where('status', 1)
            ->orderBy('id', 'desc')
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
