<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class Closelapangan extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tgl = Carbon::now('Asia/Jakarta')->startOfDay();
        $jml = 61;

        $htng = $tgl->copy()->addDays($jml)->format('Y-m-d');

        return response()->json([
            'date' => $htng,
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
