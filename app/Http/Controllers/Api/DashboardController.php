<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use App\Http\Resources\Dashboard as DashboardResource;
use DB;
class DashboardController extends Controller
{
    public function index(Request $request){
    	$user = $request->user();
    	$penjualan = $user
					    	->bisnis
					    	->penjualan()
					    	->select(DB::raw('count(*) as total_transaksi, sum(subtotal) as total_penjualan_kotor, sum(total_item) as total_produk_terjual'))
					    	->where('outlet_id', $user->outlet_terpilih_id)
					    	->first();
		$produkTerlaris = $user->bisnis
									->penjualanItem()
							    	->select(DB::raw('nama_menu, sum(jumlah) as jumlah'))
							    	->where('outlet_id', $user->outlet_terpilih_id)
							    	->groupBy('nama_menu')
							    	->orderBy('jumlah', 'desc')
							    	->limit(5)
									->get();
		return response()->json([
			'data' => [
				'total_penjualan_kotor' => $penjualan->total_penjualan_kotor ?? 0,
				'total_produk_terjual' => $penjualan->total_produk_terjual ?? 0,
				'total_transaksi' => $penjualan->total_transaksi ?? 0,
				'produk_terlaris' => $produkTerlaris
			]
		]);
    }	
}
