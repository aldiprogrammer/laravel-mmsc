<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Bookingrankteam extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->query('limit');
        $iduser = $request->query('iduser');

        $query = DB::table('tbl_member_karir_team')
            ->where('id_user', $iduser)
            ->orderBy('id', 'desc');

        if ($limit == 4) {
            $data = $query->limit(3)->get();
            return response()->json($data, 200);
        } elseif ($limit === 'all') {
            $data = $query->get();
            return response()->json($data, 200);
        }

        return response()->json([], 200);
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
