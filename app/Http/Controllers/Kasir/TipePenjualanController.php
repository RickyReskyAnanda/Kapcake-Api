<?php

namespace App\Http\Controllers\Kasir;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TipePenjualanController extends Controller
{
    public function index(Request $request){

        $data = $request->user()->bisnis
                ->tipePenjualan()
                ->where('outlet_id', $request->has('outlet_id') ? $request->outlet_id: 0)
                ->where('is_aktif', 1)
                ->get();

        return response()->json($data , 200);
    }
}
