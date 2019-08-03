<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Resources\InventoriMenu as InventoriMenuResource;
use App\Http\Resources\InventoriBahanDapur as InventoriBahanDapurResource;
use App\Http\Resources\InventoriBarang as InventoriBarangResource;

class InventoriController extends Controller
{
    public function index(Request $request){
    	$jenisItemTerpilih = auth()->user()->jenis_item_terpilih;
    	if($jenisItemTerpilih == 'menu'){
    		$data = $request->user()->bisnis
                    ->inventoriMenu()
                    ->with('menu.variasi')
                    // ->whereBetween('tanggal', [$request->tanggal_awal, $request->tanggal_akhir])
	    			->paginate();
	    	
	    	return InventoriMenuResource::collection($data);
    	}elseif($jenisItemTerpilih == 'bahan_dapur'){
    		$data = $request->user()->bisnis
                    ->inventoriBahanDapur()
                    // ->whereBetween('tanggal', [$request->tanggal_awal, $request->tanggal_akhir])
	    			->paginate();
	    	
	    	return InventoriBahanDapurResource::collection($data);
    	}elseif($jenisItemTerpilih == 'barang'){
    		$data = $request->user()->bisnis
                    ->inventoriBarang()
                    // ->whereBetween('tanggal', [$request->tanggal_awal, $request->tanggal_akhir])
	    			->paginate();
	    	
	    	return InventoriBarangResource::collection($data);
    	}
    }
}
