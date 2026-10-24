<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Pertandinganrankteamterbaru extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sesi = DB::table('tbl_session')
            ->where('status', 1)
            ->first();

        if (!$sesi) {
            return response()->json([], 200);
        }

        $match = DB::table('tbl_pertandingan_rankteam')
            ->where('session', $sesi->session)
            ->orderBy('id', 'desc')
            ->limit(2)
            ->get();

        return response()->json($match, 200);
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
