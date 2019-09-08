<?php

namespace App\Http\Controllers\Api;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\AssignKategoriBarang as AssignKategoriBarangResource;

class AssignKategoriBarangController extends Controller
{
    public function index(Request $request){
        $kategori = $request->user()->bisnis
                    ->kategoriBarang()
                    ->find($request->kategori_barang_id);
    	$data = $request->user()->bisnis
                    ->barang()
                    ->where(function($q) use ($kategori){
                            $q->where('outlet_id', $kategori->outlet_id);
                    })
                    ->get();
        return AssignKategoriBarangResource::collection($data);
    }

    public function update(Request $request){
    	$data = $request->validate([
    		'*.id' => 'required',
    		'*.kategori_id' => 'required'
    	]);

    	DB::beginTransaction();
        try {   
	    	foreach($data as $d){
	    		$barang = $request->user()->bisnis
				                    ->barang()
				                    ->find($d['id']);
				if(!is_null($barang))
					$barang->update(['kategori_barang_id' => $d['kategori_id'] ]);
	    	}

            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }

    }
}
