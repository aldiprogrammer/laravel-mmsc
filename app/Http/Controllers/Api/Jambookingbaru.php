<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Jambookingbaru extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $id = $request->query('id');

        // Pisahkan ID berdasarkan koma
        $array = explode(',', $id ?? '');

        // Hitung total harga diskon
        $total = DB::table('tbl_jambooking_lapangan')
            ->whereIn('id', $array)
            ->sum('harga_diskon');

        return response()->json([
            'total_harga' => (float) $total
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
