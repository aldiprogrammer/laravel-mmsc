<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Jmlpemainrankteam extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $id = $request->query('id');

        $team = DB::table('tbl_teamrankteam')
            ->where('id_user', $id)
            ->first();

        if (!$team) {
            return response()->json([
                'message' => 'team tidak ditemukan'
            ], 404);
        }

        $jml = DB::table('tbl_pemainrankteam')
            ->where('kode_team', $team->kode)
            ->where('baru', '!=', 1)
            ->count();

        return response()->json([
            'jml' => $jml
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
