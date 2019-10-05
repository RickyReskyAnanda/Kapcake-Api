<?php

namespace App\Http\Controllers\Kasir;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Perangkat;

class PerangkatController extends Controller
{
    public function logout(Request $request){
    	$request->validate([
    		'perangkat_id' => 'required',
    	]);
    	Perangkat::where('id_perangkat', $request->perangkat_id)->update([
    		'is_aktif' => 0
    	]);
    	return response(['response'=>'success'],200);
    }
}
