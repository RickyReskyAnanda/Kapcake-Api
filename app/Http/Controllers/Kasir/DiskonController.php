<?php

namespace App\Http\Controllers\Kasir;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DiskonController extends Controller
{
    public function index(Request $request){

        $data = $request->user()->bisnis
                ->outlet()
                ->where('id_outlet', $request->outlet_id)
                ->first();
        $data->load('diskon');

        return response()->json($data->diskon, 200);
    }
}
