<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Updatestatusmain extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $date = Carbon::now('Asia/Jakarta')->startOfDay();
        $id_user = $request->input('id_user');

        $main = DB::table('tbl_main')
            ->where('id_user', $id_user)
            ->get();

        $update = false;

        foreach ($main as $dat) {

            $tgl_main = Carbon::parse(
                $dat->tgl_main,
                'Asia/Jakarta'
            )->startOfDay();

            $d = abs($date->diffInDays($tgl_main));

            if ($d >= 1) {
                $status = 0;
            } else {
                $status = 1;
            }

            $update = DB::table('tbl_main')
                ->where('id', $dat->id)
                ->update([
                    'status_main' => $status
                ]) > 0;
        }

        return response()->json($update, 200);
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
