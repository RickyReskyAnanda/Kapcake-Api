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
					    	->select(DB::raw("sum(subtotal) / (DATEDIFF('{$request->tanggal_akhir}','{$request->tanggal_awal}') + 1) as rata_rata_penjualan_perhari, count(*) as total_transaksi, sum(subtotal) as total_penjualan_kotor, sum(total_item) as total_produk_terjual,SUM(total_pajak) as total_pajak_terkumpul, SUM(total_diskon) as total_diskon"))
					    	->where(function($q) use ($request){
					    		if($request->has('outlet_id') && $request->outlet_id !='' && $request->outlet_id)
							    	$q->where('outlet_id', $request->outlet_id);
					    		$q->where('tanggal_proses','>=',$request->tanggal_awal);
					    		$q->where('tanggal_proses','<=',$request->tanggal_akhir);
					    	})
					    	->first();

		$menuTerlaris = $user->bisnis
									->penjualanItem()
							    	->select(DB::raw('penjualan_item.nama_menu, sum(penjualan_item.jumlah) as jumlah'))
							    	->where('penjualan_item.outlet_id',  $request->outlet_id)
							    	->where(function($q) use ($request){
							    		$q->where('penjualan.tanggal_proses', '>=',$request->tanggal_awal);
							    		$q->where('penjualan.tanggal_proses', '<=',$request->tanggal_akhir);
							    		$q->whereNotNull('nama_menu');
							    	})
							    	->join('penjualan','penjualan.id_penjualan','=','penjualan_item.penjualan_id')
							    	->groupBy('penjualan_item.nama_menu')
							    	->orderBy('jumlah', 'desc')
							    	->limit(5)
									->get();
		$kategoriMenuTerlaris = $user->bisnis
									->penjualanItem()
							    	->select(DB::raw('nama_kategori_menu, sum(penjualan_item.jumlah) as jumlah'))
									->where('penjualan_item.outlet_id',  $request->outlet_id)
							    	->where(function($q) use ($request){
							    		$q->where('penjualan.tanggal_proses', '>=',$request->tanggal_awal);
							    		$q->where('penjualan.tanggal_proses', '<=',$request->tanggal_akhir);
							    		$q->whereNotNull('nama_kategori_menu');
							    	})
							    	->join('penjualan','penjualan.id_penjualan','=','penjualan_item.penjualan_id')
							    	->groupBy('penjualan_item.nama_kategori_menu')
							    	->orderBy('jumlah', 'desc')
							    	->limit(5)
									->get();
		$jenisPemesananTerlaris = $user->bisnis
									->penjualanItem()
							    	->select(DB::raw('nama_tipe_penjualan, sum(penjualan_item.jumlah) as jumlah '))
									->where('penjualan_item.outlet_id',  $request->outlet_id)
							    	->where(function($q) use ($request){
							    		$q->where('penjualan.tanggal_proses', '>=',$request->tanggal_awal);
							    		$q->where('penjualan.tanggal_proses', '<=',$request->tanggal_akhir);
							    		$q->whereNotNull('nama_tipe_penjualan');
							    	})
							    	->join('penjualan','penjualan.id_penjualan','=','penjualan_item.penjualan_id')
							    	->groupBy('penjualan_item.nama_tipe_penjualan')
							    	->orderBy('jumlah', 'desc')
							    	->limit(5)
									->get();

		$penjualanBerdasarkanHari = $user->bisnis
									->penjualan()
							    	->select(DB::raw('DAYOFWEEK(tanggal_proses) as hari, sum(subtotal) as penjualan '))
									->where('outlet_id',  $request->outlet_id)
							    	->where(function($q) use ($request){
							    		$q->where('tanggal_proses', '>=',$request->tanggal_awal);
							    		$q->where('tanggal_proses', '<=',$request->tanggal_akhir);
							    	})
							    	->groupBy('hari')
							    	->orderBy('hari', 'asc')
									->get();
		$penjualanBerdasarkanJam = $user->bisnis
									->penjualan()
							    	->select(DB::raw("CASE
													   WHEN waktu_proses >= '00:00:00' AND waktu_proses <= '02:59:59' THEN '00-03'
													   WHEN waktu_proses >= '03:00:00' AND waktu_proses <= '05:59:59' THEN '03-06'
													   WHEN waktu_proses >= '06:00:00' AND waktu_proses <= '08:59:59' THEN '06-09'
													   WHEN waktu_proses >= '09:00:00' AND waktu_proses <= '11:59:59' THEN '09-12'
													   WHEN waktu_proses >= '12:00:00' AND waktu_proses <= '14:59:59' THEN '12-15'
													   WHEN waktu_proses >= '15:00:00' AND waktu_proses <= '17:59:59' THEN '15-18'
													   WHEN waktu_proses >= '18:00:00' AND waktu_proses <= '20:59:59' THEN '18-21'
													   WHEN waktu_proses >= '21:00:00' AND waktu_proses <= '23:59:59' THEN '21-00'
													END AS jam,
														sum(subtotal) as penjualan"))
									->where('outlet_id',  $request->outlet_id)
							    	->where(function($q) use ($request){
							    		$q->where('tanggal_proses', '>=',$request->tanggal_awal);
							    		$q->where('tanggal_proses', '<=',$request->tanggal_akhir);
							    	})
							    	->groupBy('jam')
							    	->orderBy('jam', 'asc')
									->get();


		$hari = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];
		$pBerdasarkanHari = [
			['name' => $hari[0], 'penjualan' => 0],
			['name' => $hari[1], 'penjualan' => 0],
			['name' => $hari[2], 'penjualan' => 0],
			['name' => $hari[3], 'penjualan' => 0],
			['name' => $hari[4], 'penjualan' => 0],
			['name' => $hari[5], 'penjualan' => 0],
			['name' => $hari[6], 'penjualan' => 0],
		];
		for($i = 1; $i <= 7; $i++) {
			foreach($penjualanBerdasarkanHari as $pH){
				if($pH->hari == $i){
					$pBerdasarkanHari[$i-1] = ['name' => $hari[$i-1], 'penjualan' => $pH->penjualan];
				}
			}
		}

		$pBerdasarkanJam = [
			['name' => '0-3', 'penjualan' => 0],
			['name' => '3-6', 'penjualan' => 0],
			['name' => '6-9', 'penjualan' => 0],
			['name' => '9-12', 'penjualan' => 0],
			['name' => '12-15', 'penjualan' => 0],
			['name' => '15-18', 'penjualan' => 0],
			['name' => '21-0', 'penjualan' => 0],
		];

		foreach($penjualanBerdasarkanJam as $p){
			if($p->jam == '00-03'){
				$pBerdasarkanJam[0]['penjualan'] = $p->penjualan;
			}elseif($p->jam == '03-06'){
				$pBerdasarkanJam[1]['penjualan'] = $p->penjualan;
			}elseif($p->jam == '06-09'){
				$pBerdasarkanJam[2]['penjualan'] = $p->penjualan;
			}elseif($p->jam == '12-15'){
				$pBerdasarkanJam[3]['penjualan'] = $p->penjualan;
			}elseif($p->jam == '15-18'){
				$pBerdasarkanJam[4]['penjualan'] = $p->penjualan;
			}elseif($p->jam == '18-21'){
				$pBerdasarkanJam[5]['penjualan'] = $p->penjualan;
			}elseif($p->jam == '21-00'){
				$pBerdasarkanJam[6]['penjualan'] = $p->penjualan;
			}
		}

		return response()->json([
			'data' => [
				'total_penjualan_kotor' => $penjualan->total_penjualan_kotor ?? 0,
				'total_produk_terjual' => $penjualan->total_produk_terjual ?? 0,
				'total_transaksi' => $penjualan->total_transaksi ?? 0,
				'rata_rata_penjualan_perhari' => (int)$penjualan->rata_rata_penjualan_perhari ?? 0,
				'total_pajak_terkumpul' => (int)$penjualan->total_pajak_terkumpul ?? 0,
				'total_diskon' => (int)$penjualan->total_diskon ?? 0,
				'menu_terlaris' => $menuTerlaris,
				'kategori_menu_terlaris' => $kategoriMenuTerlaris,
				'jenis_pemesanan_terlaris' => $jenisPemesananTerlaris,
				'penjualan_berdasarkan_hari' => $pBerdasarkanHari,
				'penjualan_berdasarkan_jam' => $pBerdasarkanJam
			]
		]);
    }	
}
