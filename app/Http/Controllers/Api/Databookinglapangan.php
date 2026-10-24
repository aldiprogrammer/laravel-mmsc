<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Databookinglapangan extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $iduser = $request->query('iduser');
        $tglbooking = $request->query('tglbooking');

        $query = DB::table('tbl_booking');

        if ($iduser !== null) {
            $query->where('iduser', $iduser)
                ->orderBy('id', 'desc');

            if ($tglbooking !== null) {
                $query->where('tgl', $tglbooking);
            }
        }

        $data = $query->get();

        return response()->json($data, 200);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $id = $request->input('id');

        DB::table('tbl_booking')
            ->where('id', $id)
            ->delete();

        return response()->json([
            'message' => 'true',
        ], 201);
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
    public function destroy(Request $request, string $id)
    {
        $id = $request->input('id');

        DB::table('tbl_booking')
            ->where('id', $id)
            ->delete();

        return response()->json([
            'message' => 'true',
        ], 201);
    }
}
