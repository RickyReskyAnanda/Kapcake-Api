<?php

namespace App\Http\Controllers\Kasir;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BiayaTambahanController extends Controller
{
    public function index(Request $request){

        $data = $request->user()->bisnis
                ->outlet()
                ->where('id_outlet', $request->outlet_id)
                ->first();
        $data->load('biayaTambahan');

        return response()->json($data->biayaTambahan, 200);
    }
}
