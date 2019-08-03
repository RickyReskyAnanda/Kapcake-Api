<?php

namespace App\Http\Controllers\Api;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\AssignKategoriBahanDapur as AssignKategoriBahanDapurResource;

class AssignKategoriBahanDapurController extends Controller
{
    public function index(Request $request){
    	$data = $request->user()->bisnis
                    ->bahanDapur()
                    ->where('outlet_id', auth()->user()->outlet_terpilih_id)
                    ->where('nama_bahan_dapur', 'like', '%'.request()->pencarian.'%')
                    ->get();
        return AssignKategoriBahanDapurResource::collection($data);
    }

    public function update(Request $request){
    	$data = $request->validate([
    		'*.id' => 'required',
    		'*.kategori_id' => 'required'
    	]);

    	DB::beginTransaction();
        try {   
	    	foreach($data as $d){
	    		$bahanDapur = $request->user()->bisnis
				                    ->bahanDapur()
				                    ->find($d['id']);
				if(!is_null($bahanDapur))
					$bahanDapur->update(['kategori_bahan_dapur_id' => $d['kategori_id'] ]);
	    	}

            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }

    }
}
