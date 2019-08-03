<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Resources\ItemMenu as ItemMenuResource;
use App\Http\Resources\ItemBahanDapur as ItemBahanDapurResource;
use App\Http\Resources\ItemBarang as ItemBarangResource;

class ItemController extends Controller
{
    public function index(Request $request){
        $jenisItemTerpilih = auth()->user()->jenis_item_terpilih;
    	if($jenisItemTerpilih == 'menu'){
    		$data = $request->user()->bisnis
                    ->menu()
                    ->rightjoin('variasi_menu','variasi_menu.menu_id', '=', 'menu.id_menu')
                    ->where('variasi_menu.outlet_id', $request->outlet_id)
                    ->where('variasi_menu.is_inventarisasi', 1)
	    			->get();
	    	
	    	return ItemMenuResource::collection($data);
    	}elseif($jenisItemTerpilih == 'bahan_dapur'	){
    		$data = $request->user()->bisnis
                    ->bahanDapur()
                    ->where('outlet_id', $request->outlet_id)
                    ->where('is_inventarisasi', 1)
	    			->get();
	    	
	    	return ItemBahanDapurResource::collection($data);
    	}elseif($jenisItemTerpilih == 'barang'){
    		$data = $request->user()->bisnis
                    ->barang()
                    ->where('outlet_id', $request->outlet_id)
                    ->where('is_inventarisasi', 1)
	    			->get();
	    	
	    	return ItemBarangResource::collection($data);
    	}
    }
}
