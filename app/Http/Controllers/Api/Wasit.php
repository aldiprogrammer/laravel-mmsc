<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Wasit extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $id = $request->query('id');

        if ($id === null || $id === '') {
            // Ambil semua wasit dengan status 0
            $data = DB::table('tbl_wasit')
                ->where('status', 0)
                ->get();

            return response()->json($data, 200);
        }

        // Ambil satu wasit berdasarkan ID
        $data = DB::table('tbl_wasit')
            ->where('status', 0)
            ->where('id', $id)
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
