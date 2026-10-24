<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Peraturan extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $id_user = $request->query('id_user');

        $peraturan = $request->query('ranksolo') !== null
            ? 'rank solo'
            : 'rank team';

        $data = DB::table('tbl_peraturan')
            ->where('id_user', $id_user)
            ->where('peraturan', $peraturan)
            ->first();

        if (!$data) {
            return response()->json([
                'message' => 'salah'
            ], 404);
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
