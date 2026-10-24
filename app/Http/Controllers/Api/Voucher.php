<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Voucher extends Controller
{
    /**
     * Display a listing of the resource.
     */
    function index(Request $request)
    {

        $iduser = $request->query('iduser');
        $notif = $request->query('notif');

        if ($notif == 1) {

            $data = DB::table('tbl_voucher')
                ->where('id_user', $iduser)
                ->where('status', 0)
                ->first();

            return response()->json($data, 200);
        } else {

            $data = DB::table('tbl_voucher')
                ->where('id_user', $iduser)
                ->orderBy('id', 'desc')
                ->get();

            return response()->json($data, 200);
        }
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
