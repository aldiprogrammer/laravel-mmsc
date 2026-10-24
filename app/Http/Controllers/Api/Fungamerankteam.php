<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Fungamerankteam extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tgl = date('Y-m-d');

        $data = DB::table('tbl_fungame_rankteam')
            ->where('status', 1)
            ->where('tgl_main', '>=', $tgl)
            ->limit(40)
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
