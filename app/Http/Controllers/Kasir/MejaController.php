<?php

namespace App\Http\Controllers\Kasir;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MejaController extends Controller
{
    public function index(Request $request){

        $data = $request->user()->bisnis
                ->outlet()
                ->where('id_outlet', $request->outlet_id)
                ->first();
        $data->load('meja', 'kategoriMeja');

        return response()->json(['meja' => $data->meja, 'kategori_meja' => $data->kategoriMeja], 200);
    }
}
