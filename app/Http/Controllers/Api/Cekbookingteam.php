<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Cekbookingteam extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $iduser = $request->query('id_user');
        $data = DB::table('tbl_member_karir_team')
            ->where('id_user', $iduser)
            ->where('status_pembayaran', 200)
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
