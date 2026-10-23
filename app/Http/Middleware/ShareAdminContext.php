<?php

namespace App\Http\Middleware;

use App\Models\Booking;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ShareAdminContext
{
    /**
     * Share the admin profile and notification feed with every admin view.
     */
    public function handle(Request $request, Closure $next): Response
    {
        View::share('admin', DB::table('tbl_admin')->first() ?? (object) [
            'username' => 'admin',
            'role' => 'Administrator',
        ]);

        View::share('notifications', Booking::orderByDesc('id')->limit(4)->get());

        return $next($request);
    }
}
