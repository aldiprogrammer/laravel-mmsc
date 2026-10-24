<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Getbooking extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');

        $id = $request->query('id');
        $all = $request->query('all');

        $query = DB::table('tbl_booking')
            ->where('iduser', $id)
            ->orderBy('id', 'desc');

        if ($all !== 'true') {
            $query->limit(2);
        }

        $data = $query->get();

        if ($data->isNotEmpty()) {
            return response()->json($data, 200);
        }

        return response()->json([
            'status' => false
        ], 400);
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
