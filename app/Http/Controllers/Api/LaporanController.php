<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;

class LaporanController extends Controller
{
    public function ringkasanPenjualan(Request $request){
    	$user = $request->user();

    	/*
		|	yang belum ada ifnya nanti dikasih if status = suksea dan seleksi pembulatannya
    	*/
    	$data = $user
			    	->bisnis
			    	->penjualan()
			    	->select(DB::raw("
			    		SUM(subtotal) as total_penjualan_kotor,  
			    		SUM(total_diskon) as total_diskon,
			    		SUM(IF(status = 'refund', total, 0)) as total_pengembalian_uang,
			    		SUM(subtotal) - ( SUM(total_diskon) + SUM(IF(status = 'refund', total, 0)) )  as  total_penjualan_bersih,

			    		SUM(total_biaya_tambahan) as total_biaya_tambahan,
			    		SUM(total_pajak) as total_pajak,
			    		SUM(total_pembulatan) as total_pembulatan,
			    		SUM(total) as total
			    	"))
			    	->where(function ($q) use ($request, $user){
			    		if($request->has('outlet_id') && $request->outlet_id != '')
					    	$q->where('outlet_id', $request->outlet_id);
			    		if(	$request->has('tanggal_awal')  && 
			    			$request->has('tanggal_akhir') && 
			    			$request->tanggal_awal != '' && 
			    			$request->tanggal_akhir != '' ){

				    		$q->where('tanggal_proses','>=',$request->tanggal_awal);
				    		$q->where('tanggal_proses','<=',$request->tanggal_akhir);
			    		}
			    	})
			    	->groupBy('outlet_id')
			    	->first();


		return response()->json([
			'data' => $data
		]);
    }

    public function penjualan(Request $request){
    	$user = $request->user();

		/*
		| sku belum ditampilkan karena ambil dari tabel variasi_menu
		| diskon belum masuk dalam diskon item. jadi sementara dikurangi dengan diskon transaksi
		| berikan filter pencarian 
		*/    	

		/*
		|-----------------------------------------------------------
		|					Keterangan
		|-----------------------------------------------------------
		|	1. subtotal adalah hasil kali dari jumlah dan harga
		|	2. total adalah subtotal  kurang total_diskon dan total_refund
		|
		*/
		$data = $user
		    	->bisnis
		    	->penjualanItem()
		    	->with('variasiMenu','menu.kategori')
		    	->select(DB::raw("
	    			penjualan_item.variasi_menu_id,
	    			penjualan_item.menu_id,
		    		SUM(penjualan_item.jumlah) as total_penjualan_item,
		    		SUM(penjualan_item.jumlah_refund) as total_pengembalian_item,
		    		SUM(penjualan_item.subtotal) as total_penjualan_kotor, 
		    		SUM(penjualan_item.total_refund) as total_pengembalian_uang,
		    		SUM(penjualan_item.total) as total_penjualan_bersih
		    	"))
		    	->where(function ($q) use ($request){
		    		$q->where('penjualan.status','sukses');
		    		if($request->has('outlet_id'))
				    	$q->where('penjualan_item.outlet_id', $request->outlet_id);

		    		if(	$request->has('tanggal_awal')  && 
			    			$request->has('tanggal_akhir') && 
			    			$request->tanggal_awal != '' && 
			    			$request->tanggal_akhir != '' ){
			    		$q->where('penjualan.tanggal_proses', '>=', $request->tanggal_awal);
			    		$q->where('penjualan.tanggal_proses', '<=', $request->tanggal_akhir);
			    	}
		    	})
		    	->rightJoin('penjualan','penjualan.id_penjualan','penjualan_item.penjualan_id')
		    	->groupBy('penjualan_item.menu_id')
		    	->groupBy('penjualan_item.variasi_menu_id')
		    	->paginate(20);

		return response()->json($data);
    }

    public function kategoriPenjualan(Request $request){
    	$user = $request->user();

		/*
		| seleksi status belum ada , berikan seleksi status ketika perbaikan total
		| sku belum ditampilkan karena ambil dari tabel variasi_menu
		| diskon belum masuk dalam diskon item. jadi sementara dikurangi dengan diskon transaksi
		| berikan filter pencarian 
		*/    	

		/*
		|-----------------------------------------------------------
		|					Keterangan
		|-----------------------------------------------------------
		|	1. subtotal adalah hasil kali dari jumlah dan harga
		|	2. total adalah subtotal  kurang total_diskon dan total_refund
		|
		*/
		$data = $user
		    	->bisnis
		    	->penjualanItem()
		    	->select(DB::raw("
		    			IF(penjualan_item.nama_kategori_menu IS NULL, 'Tidak Dikategorikan', penjualan_item.nama_kategori_menu) as nama_kategori_menu,
			    		SUM(penjualan_item.jumlah) as total_penjualan_item,
			    		SUM(penjualan_item.jumlah_refund) as total_pengembalian_item,
			    		SUM(penjualan_item.subtotal) as total_penjualan_kotor, 
			    		SUM(penjualan_item.total_refund) as total_pengembalian_uang,
			    		SUM(penjualan_item.total) as total_penjualan_bersih
		    	"))
		    	->where(function($q) use ($request){
		    		if($request->has('outlet_id'))
			    		$q->where('penjualan_item.outlet_id', $request->outlet_id);

			    	if(	$request->has('tanggal_awal')  && 
			    			$request->has('tanggal_akhir') && 
			    			$request->tanggal_awal != '' && 
			    			$request->tanggal_akhir != '' ){
			    		$q->where('penjualan.tanggal_proses', '>=', $request->tanggal_awal);
			    		$q->where('penjualan.tanggal_proses', '<=', $request->tanggal_akhir);
			    	}
		    	})
		    	->rightJoin('penjualan','penjualan.id_penjualan','penjualan_item.penjualan_id')
		    	->groupBy('penjualan_item.nama_kategori_menu')
		    	->orderBy('penjualan_item.nama_kategori_menu','asc')
		    	->paginate(20);

		return response()->json($data);
    }

    public function transaksi(Request $request){
    	$user = $request->user();

		$data = $user
		    	->bisnis
		    	->penjualan()
		    	->select('kode_pemesanan','tanggal_proses as tanggal', 'waktu_proses as waktu', 'nama_user', 'total_item', 'subtotal','total')
		    	->where(function($q) use ($request){
		    		if($request->has('outlet_id'))
			    		$q->where('outlet_id', $request->outlet_id);

		    		if(	$request->has('tanggal_awal')  && 
			    			$request->has('tanggal_akhir') && 
			    			$request->tanggal_awal != '' && 
			    			$request->tanggal_akhir != '' ){
			    		$q->where('tanggal_proses','>=',$request->tanggal_awal);
			    		$q->where('tanggal_proses','<=',$request->tanggal_akhir);
		    		}
		    	})
		    	->paginate(20);
		return response()->json($data);
    }

    /*
    |----------------------------------------------------
    |			DITUNDA LAPORAN BAHAN DAPUR 
    |----------------------------------------------------
    |
    */
    public function bahanDapur(Request $request){
    	$user = $request->user();

		$data = $user
		    	->bisnis
		    	->bahanDapur()
		    	->with('satuan')
		    	->select('nama_bahan_dapur','kode_unik_bahan_dapur')
		    	->where(function($q) use ($request){
		    		if($request->has('outlet_id'))
			    		$q->where('outlet_id', $request->outlet_id);
		    	})
		    	->paginate();
		return response()->json([
			'data' => $data
		]);
    }

    public function diskon(Request $request){
    	$user = $request->user();

		$data = $user
		    	->bisnis
		    	->penjualan()
		    	->select(DB::raw("
		    		nama_diskon,
		    		jumlah_diskon,
	    			COUNT(*) as total_transaksi,
	    			SUM(total_diskon) as total_diskon_kotor,
	    			SUM(IF(status = 'refund', total_diskon, 0)) as total_diskon_dikembalikan,
	    			SUM(total_diskon - IF(status = 'refund', total_diskon, 0)) as total_diskon_bersih
	    		"))
		    	->where(function($q) use ($request){
			    	if($request->has('outlet_id'))
				    	$q->where('outlet_id', $request->outlet_id);

		    		if(	$request->has('tanggal_awal')  && 
			    			$request->has('tanggal_akhir') && 
			    			$request->tanggal_awal != '' && 
			    			$request->tanggal_akhir != '' ){
			    		$q->where('tanggal_proses','>=',$request->tanggal_awal);
			    		$q->where('tanggal_proses','<=',$request->tanggal_akhir);
		    		}
		    	})
		    	->where('diskon_id', '!=', 0)
		    	->groupBy('nama_diskon')
		    	->groupBy('jumlah_diskon')
		    	->paginate();
		return response()->json($data);
    }

    public function pajak(Request $request){
    	$user = $request->user();

		$data = $user
		    	->bisnis
		    	->penjualan()
		    	->with('pajak')
		    	->select(DB::raw("pajak_id, sum(subtotal) as jumlah_kena_pajak, sum(total_pajak) as pajak_terkumpul"))
		    	->where(function($q) use ($request){
		    		if($request->has('outlet_id'))
				    	$q->where('outlet_id', $request->outlet_id);

		    		if(	$request->has('tanggal_awal')  && 
			    			$request->has('tanggal_akhir') && 
			    			$request->tanggal_awal != '' && 
			    			$request->tanggal_akhir != '' ){
			    		$q->where('tanggal_proses','>=',$request->tanggal_awal);
			    		$q->where('tanggal_proses','<=',$request->tanggal_akhir);
		    		}
		    	})
		    	->where('pajak_id', '!=', 0)
		    	->groupBy('pajak_id')
		    	->paginate();
		return response()->json($data);
    }

    public function biayaTambahan(Request $request){
    	$user = $request->user();
    	
		$data = $user
		    	->bisnis
		    	->penjualan()
		    	->with('biayaTambahan')
		    	->select(DB::raw("biaya_tambahan_id, sum(subtotal) as jumlah_kena_biaya_tambahan, sum(total_biaya_tambahan) as biaya_tambahan_terkumpul"))
		    	->where(function($q) use ($request){
		    		if($request->has('outlet_id'))
			    		$q->where('outlet_id', $request->outlet_id);
		    		if(	$request->has('tanggal_awal')  && 
			    			$request->has('tanggal_akhir') && 
			    			$request->tanggal_awal != '' && 
			    			$request->tanggal_akhir != '' ){
			    		$q->where('tanggal_proses','>=',$request->tanggal_awal);
			    		$q->where('tanggal_proses','<=',$request->tanggal_akhir);
		    		}
		    	})
		    	->where('biaya_tambahan_id', '!=', 0)
		    	->groupBy('biaya_tambahan_id')
		    	->paginate();

		return response()->json($data);
    }


}
