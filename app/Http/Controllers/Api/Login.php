<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Login extends Controller
{
    function index(Request $request)
    {
        $email = $request->query('email');
        $pass = $request->query('pass');

        $cekemail = DB::table('tbl_user')->where('email', $email)->first();
        if ($cekemail == true) {

            if ($cekemail->pass == '') {
                return response()->json($cekemail, 200);
            } elseif ($pass == $cekemail->pass) {

                return response()->json($cekemail, 200);
            } else {
                return response()->json(['status' => 'pass salah'], 404);
            }
        } else {
            return response()->json(['status' => 'salah erer'], 404);
        }
    }
}
