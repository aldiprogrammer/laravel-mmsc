<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use Illuminate\View\View;

class LapanganController extends Controller
{
    public function index(): View
    {
        return view('admin.lapangan', [
            'lapanganList' => Lapangan::orderBy('id')->get(),
        ]);
    }
}
