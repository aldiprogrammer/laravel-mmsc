<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Datateam extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $id_user = $request->query('id_user');

        $data = DB::table('tbl_teamrankteam')
            ->where('id_user', $id_user)
            ->first();

        if ($data) {
            return response()->json($data, 200);
        }

        return response()->json([
            'status' => false
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $id_user = $request->input('id_user');
        $namateam = $request->input('namateam');
        $logoteam = $request->input('logoteam');
        $ubah = $request->input('ubah');

        if ($ubah == '1') {

            $data = [
                'logo_team' => $logoteam,
            ];

            if ($namateam != '') {

                $team = DB::table('tbl_teamrankteam')
                    ->where('id_user', $id_user)
                    ->first();

                if (!$team) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Team tidak ditemukan'
                    ], 404);
                }

                // Update nama team pada pemain
                DB::table('tbl_pemainrankteam')
                    ->where('kode_team', $team->kode)
                    ->update([
                        'team' => $namateam,
                    ]);

                $data = [
                    'nama_team' => $namateam,
                    'logo_team' => $logoteam,
                ];
            }

            $update = DB::table('tbl_teamrankteam')
                ->where('id_user', $id_user)
                ->update($data);

            return response()->json($update > 0, 201);
        } else {

            $kode = 'kode-' . Str::random(12);

            $add = DB::table('tbl_teamrankteam')->insert([
                'kode' => $kode,
                'id_user' => $id_user,
                'nama_team' => $namateam,
                'logo_team' => $logoteam,
            ]);

            return response()->json($add, 201);
        }
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
