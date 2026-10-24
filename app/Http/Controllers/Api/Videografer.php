<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Videografer extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DB::table('tbl_videograper')
            ->where('status', 0);

        if ($request->query('id') === null || $request->query('id') === '') {
            // Ambil semua data videograper dengan status 0
            $data = $query->get();
        } else {
            // Ambil satu data berdasarkan ID
            $data = $query
                ->where('id', $request->query('id'))
                ->first();
        }

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
