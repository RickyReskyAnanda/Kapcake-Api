<?php

namespace App\Http\Controllers\Api;

use App\Aplikasi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AplikasiController extends Controller
{
    public function index(Request $request)
    {
        return Aplikasi::with('otorisasi.child')->get();
    }

    public function show(Aplikasi $aplikasi)
    {
        return $aplikasi->load('otorisasi');
    }
}
