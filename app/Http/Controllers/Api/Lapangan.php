<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Lapangan extends Controller
{
    function index(Request $request)
    {
        $slug = $request->query('slug');
        $id = $request->query('id');

        if ($slug) {
            $lap = DB::table('tbl_lapangan')
                ->where('slug', $slug)
                ->first();
        } elseif ($id) {
            $lap = DB::table('tbl_lapangan')
                ->where('id', $id)
                ->first();
        } else {
            $lap = DB::table('tbl_lapangan')
                ->get();
        }

        return response()->json($lap, 200);
    }
}
