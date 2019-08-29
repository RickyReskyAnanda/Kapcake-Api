<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Resources\InventoriMenu as InventoriMenuResource;
use App\Http\Resources\InventoriBahanDapur as InventoriBahanDapurResource;
use App\Http\Resources\InventoriBarang as InventoriBarangResource;
use DB;

class InventoriController extends Controller
{
    public function index(Request $request){
        $user = $request->user();

        if($request->has('jenis_item'))
        	if($request->jenis_item == 'menu'){
                $data = DB::table('inventori_menu')
                                        // ".($request->has('outlet_id') && $request->outlet_id != 0 ? "AND a1.outlet_id  =".$request->outlet_id:'' )."
                        ->select(
                            DB::raw("
                                menu.nama_menu, 
                                kategori_menu.nama_kategori_menu,
                                if(variasi_menu.nama_variasi_menu IS NOT NULL, variasi_menu.nama_variasi_menu, 'Tanpa   Variasi') AS nama_variasi_menu,
                                (   SELECT stok_awal
                                    FROM inventori_menu a1 
                                    WHERE a1.menu_id = inventori_menu.menu_id
                                        AND a1.kategori_menu_id = inventori_menu.kategori_menu_id
                                        AND a1.variasi_menu_id = inventori_menu.variasi_menu_id
                                        AND a1.bisnis_id = inventori_menu.bisnis_id 
                                        AND a1.outlet_id  = inventori_menu.outlet_id"
                                        .( $request->has('tanggal_awal') && $request->has('tanggal_akhir') ? "
                                        AND a1.created_at >= '{$request->tanggal_awal}'       
                                        AND a1.created_at <= '{$request->tanggal_akhir}'" : "").
                                    "ORDER BY created_at asc
                                    LIMIT 1              
                                ) as stok_awal,
                                SUM(penjualan) AS penjualan,
                                SUM(penyesuaian_stok) AS penyesuaian_stok,
                                SUM(transfer) AS transfer,
                                (SELECT stok_akhir
                                    FROM inventori_menu a1 
                                    WHERE a1.menu_id = inventori_menu.menu_id
                                        AND a1.kategori_menu_id = inventori_menu.kategori_menu_id
                                        AND a1.variasi_menu_id = inventori_menu.variasi_menu_id
                                        AND a1.bisnis_id = inventori_menu.bisnis_id 
                                        AND a1.outlet_id  = inventori_menu.outlet_id"
                                        .( $request->has('tanggal_awal') && $request->has('tanggal_akhir') ? "
                                        AND a1.created_at >= '{$request->tanggal_awal}'       
                                        AND a1.created_at <= '{$request->tanggal_akhir}'" : "").
                                    "ORDER BY created_at desc
                                    LIMIT 1              
                                ) as stok_akhir
                            ")
                        )
                        ->leftJoin('menu', 'inventori_menu.menu_id', '=', 'menu.id_menu')
                        ->leftJoin('kategori_menu', 'inventori_menu.kategori_menu_id', '=', 'kategori_menu.id_kategori_menu')
                        ->leftJoin('variasi_menu', 'inventori_menu.variasi_menu_id', '=', 'variasi_menu.id_variasi_menu')
                        ->where(function($q) use ($user,$request){
                            $q->where('inventori_menu.bisnis_id', $user->bisnis_id);

                            if($request->has('outlet_id') && $request->outlet_id != 0)
                                $q->where('inventori_menu.outlet_id', 24);

                            if($request->has('tanggal_awal') && $request->has('tanggal_akhir'))
                                $q->whereBetween('inventori_menu.created_at', [$request->tanggal_awal, $request->tanggal_akhir]);
                        })
                        ->groupBy('nama_menu', 'nama_kategori_menu', 'nama_variasi_menu')
                        ->orderBy('nama_menu','asc')
                        ->orderBy('nama_kategori_menu','asc')
                        ->orderBy('nama_variasi_menu','asc')
                        ->paginate(10);

    	    	return InventoriMenuResource::collection($data);
        	}elseif($request->jenis_item == 'bahan_dapur'){
        		$data = DB::table('inventori_bahan_dapur')
                        ->select(
                            DB::raw("
                                bahan_dapur.nama_bahan_dapur, 
                                kategori_bahan_dapur.nama_kategori_bahan_dapur,
                                (   SELECT stok_awal
                                    FROM inventori_bahan_dapur a1 
                                    WHERE a1.bahan_dapur_id = inventori_bahan_dapur.bahan_dapur_id
                                        AND a1.kategori_bahan_dapur_id = inventori_bahan_dapur.kategori_bahan_dapur_id
                                        AND a1.bisnis_id = inventori_bahan_dapur.bisnis_id 
                                        AND a1.outlet_id  = inventori_bahan_dapur.outlet_id"
                                        .( $request->has('tanggal_awal') && $request->has('tanggal_akhir') ? "
                                        AND a1.created_at >= '{$request->tanggal_awal}'       
                                        AND a1.created_at <= '{$request->tanggal_akhir}'" : "").
                                    "ORDER BY created_at asc
                                    LIMIT 1              
                                ) as stok_awal,
                                SUM(pemakaian) AS pemakaian,
                                SUM(penyesuaian_stok) AS penyesuaian_stok,
                                SUM(transfer) AS transfer,
                                (SELECT stok_akhir
                                    FROM inventori_bahan_dapur a1 
                                    WHERE a1.bahan_dapur_id = inventori_bahan_dapur.bahan_dapur_id
                                        AND a1.kategori_bahan_dapur_id = inventori_bahan_dapur.kategori_bahan_dapur_id
                                        AND a1.bisnis_id = inventori_bahan_dapur.bisnis_id 
                                        AND a1.outlet_id  = inventori_bahan_dapur.outlet_id"
                                        .( $request->has('tanggal_awal') && $request->has('tanggal_akhir') ? "
                                        AND a1.created_at >= '{$request->tanggal_awal}'       
                                        AND a1.created_at <= '{$request->tanggal_akhir}'" : "").
                                    "ORDER BY created_at desc
                                    LIMIT 1              
                                ) as stok_akhir
                            ")
                        )
                        ->leftJoin('bahan_dapur', 'inventori_bahan_dapur.bahan_dapur_id', '=', 'bahan_dapur.id_bahan_dapur')
                        ->leftJoin('kategori_bahan_dapur', 'inventori_bahan_dapur.kategori_bahan_dapur_id', '=', 'kategori_bahan_dapur.id_kategori_bahan_dapur')
                        ->where(function($q) use ($user,$request){
                            $q->where('inventori_bahan_dapur.bisnis_id', $user->bisnis_id);

                            if($request->has('outlet_id') && $request->outlet_id != 0)
                                $q->where('inventori_bahan_dapur.outlet_id', 24);

                            if($request->has('tanggal_awal') && $request->has('tanggal_akhir'))
                                $q->whereBetween('inventori_bahan_dapur.created_at', [$request->tanggal_awal, $request->tanggal_akhir]);
                        })
                        ->groupBy('nama_bahan_dapur', 'nama_kategori_bahan_dapur')
                        ->orderBy('nama_bahan_dapur','asc')
                        ->orderBy('nama_kategori_bahan_dapur','asc')
                        ->paginate(10);
    	    	
    	    	return InventoriBahanDapurResource::collection($data);
        	}elseif($request->jenis_item == 'barang'){
        		$data = DB::table('inventori_barang')
                        ->select(
                            DB::raw("
                                barang.nama_barang, 
                                kategori_barang.nama_kategori_barang,
                                (   SELECT stok_awal
                                    FROM inventori_barang a1 
                                    WHERE a1.barang_id = inventori_barang.barang_id
                                        AND a1.kategori_barang_id = inventori_barang.kategori_barang_id
                                        AND a1.bisnis_id = inventori_barang.bisnis_id 
                                        AND a1.outlet_id  = inventori_barang.outlet_id"
                                        .( $request->has('tanggal_awal') && $request->has('tanggal_akhir') ? "
                                        AND a1.created_at >= '{$request->tanggal_awal}'       
                                        AND a1.created_at <= '{$request->tanggal_akhir}'" : "").
                                    "ORDER BY created_at asc
                                    LIMIT 1              
                                ) as stok_awal,
                                SUM(pemakaian) AS pemakaian,
                                SUM(penyesuaian_stok) AS penyesuaian_stok,
                                SUM(transfer) AS transfer,
                                (SELECT stok_akhir
                                    FROM inventori_barang a1 
                                    WHERE a1.barang_id = inventori_barang.barang_id
                                        AND a1.kategori_barang_id = inventori_barang.kategori_barang_id
                                        AND a1.bisnis_id = inventori_barang.bisnis_id 
                                        AND a1.outlet_id  = inventori_barang.outlet_id"
                                        .( $request->has('tanggal_awal') && $request->has('tanggal_akhir') ? "
                                        AND a1.created_at >= '{$request->tanggal_awal}'       
                                        AND a1.created_at <= '{$request->tanggal_akhir}'" : "").
                                    "ORDER BY created_at desc
                                    LIMIT 1              
                                ) as stok_akhir
                            ")
                        )
                        ->leftJoin('barang', 'inventori_barang.barang_id', '=', 'barang.id_barang')
                        ->leftJoin('kategori_barang', 'inventori_barang.kategori_barang_id', '=', 'kategori_barang.id_kategori_barang')
                        ->where(function($q) use ($user,$request){
                            $q->where('inventori_barang.bisnis_id', $user->bisnis_id);

                            if($request->has('outlet_id') && $request->outlet_id != 0)
                                $q->where('inventori_barang.outlet_id', 24);

                            if($request->has('tanggal_awal') && $request->has('tanggal_akhir'))
                                $q->whereBetween('inventori_barang.created_at', [$request->tanggal_awal, $request->tanggal_akhir]);
                        })
                        ->groupBy('nama_barang', 'nama_kategori_barang')
                        ->orderBy('nama_barang','asc')
                        ->orderBy('nama_kategori_barang','asc')
                        ->paginate(10);
    	    	
    	    	return InventoriBarangResource::collection($data);
        	}
    }
}

//               $data = DB::select("SELECT 
//         b.nama_menu,
//         c.nama_kategori_menu, 
//         if(d.nama_variasi_menu IS NOT NULL, d.nama_variasi_menu, 'Tanpa Variasi') AS nama_variasi_menu,
//         (       SELECT stok_awal
//                     FROM inventori_menu a1 
//                    WHERE a1.menu_id = a.menu_id
//                             AND a1.kategori_menu_id = a.kategori_menu_id
//                             AND a1.variasi_menu_id = a.variasi_menu_id
//                             AND a.bisnis_id = 35 
//                             AND a.outlet_id  = 24
//                             AND a.created_at >= '2019-05-01 00:00:00'       
//                             AND a.created_at <= '2019-08-01 23:59:59'
//                         ORDER BY created_at asc
//                         LIMIT 1              
//         ) as stok_awal,
//         SUM(penjualan) AS penjualan,
//         SUM(penyesuaian_stok) AS penyesuaian_stok,
//         SUM(transfer) AS transfer,
//         (SELECT stok_akhir
//                     FROM inventori_menu a1 
//                    WHERE a1.menu_id = a.menu_id
//                         AND a1.kategori_menu_id = a.kategori_menu_id
//                         AND a1.variasi_menu_id = a.variasi_menu_id
//                         AND a.bisnis_id = 35 
//                             AND a.outlet_id  = 24
//                             AND a.created_at >= '2019-05-01 00:00:00'       
//                             AND a.created_at <= '2019-08-01 23:59:59'
//                         ORDER BY created_at desc
//                         LIMIT 1              
//         ) as stok_akhir
        
//     FROM inventori_menu a
//     LEFT JOIN menu b ON a.menu_id = b.id_menu
//     LEFT JOIN kategori_menu c ON a.kategori_menu_id = c.id_kategori_menu
//     LEFT JOIN variasi_menu d ON a.variasi_menu_id = d.id_variasi_menu
//     WHERE 
//     a.bisnis_id = 35 
//     AND a.outlet_id  = 24
//     AND a.created_at >= '2019-05-01 00:00:00'   
//     AND a.created_at <= '2019-08-01 23:59:59'
// ");