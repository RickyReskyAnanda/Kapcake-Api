<?php

namespace App\Http\Controllers\Kasir;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MenuController extends Controller
{
    public function index(Request $request){

        $menu = $request->user()->bisnis
                ->menu()
                ->with(['tipePenjualan' => function($q){
                    $q->whereHas('tipePenjualan',function($q){
                        $q->where('is_aktif',1);
                    });
                }, 'tipePenjualan.tipePenjualan','gambar','variasi.tipePenjualan'])
                ->where('outlet_id', $request->has('outlet_id') ? $request->outlet_id: 0)
                ->get();
        $kategoriMenu = $request->user()->bisnis
                ->kategoriMenu()
                ->where('outlet_id', $request->has('outlet_id') ? $request->outlet_id: 0)
                ->has('menu')
                ->get();

        return response()->json(['menu' => $menu, 'kategori_menu' => $kategoriMenu], 200);
    }

    // public function store(Request $request){
    //     return $request->all();
    //     // $this->authorize('create', Barang::class);
    //     $data = $request->validate($this->validation());
    //     DB::beginTransaction();
    //     try {   
    //         foreach ($data['pemesanan'] as $d) {
    //             $pemesanan = $request->user()->bisnis
    //                             ->pemesanan()
    //                             ->updateOrCreate(
    //                                 ['kode_pemesanan' =>  $d['kode_pemesanan']],
    //                                 [
    //                                 'outlet_id' => $data['outlet_id'],
    //                                 'no_pemesanan' =>  (float)($d['no_pemesanan']),
    //                                 'kode_pemesanan' =>  $d['kode_pemesanan'],
    //                                 'tanggal_simpan' =>  $d['tanggal_simpan'],
    //                                 'waktu_simpan' =>  $d['waktu_simpan'],
    //                                 'tanggal_proses' =>  $d['tanggal_proses'],
    //                                 'waktu_proses' =>  $d['waktu_proses'],
    //                                 'pelanggan_id' =>  (int)($d['pelanggan_id']),
    //                                 'nama_pelanggan' =>  $d['nama_pelanggan'],
    //                                 'kategori_meja_id' =>  (int)($d['kategori_meja_id']),
    //                                 'nama_kategori_meja' =>  $d['nama_kategori_meja'],
    //                                 'meja_id' =>  (int)($d['meja_id']),
    //                                 'nama_meja' =>  $d['nama_meja'],
    //                                 'diskon_id' =>  (int)($d['diskon_id']),
    //                                 'nama_diskon' =>  $d['nama_diskon'],
    //                                 'jumlah_diskon' =>  (float)($d['jumlah_diskon']),
    //                                 'total_diskon' =>  (float)($d['total_diskon']),
    //                                 'biaya_tambahan_id' =>  (int)($d['biaya_tambahan_id']),
    //                                 'nama_biaya_tambahan' =>  $d['nama_biaya_tambahan'],
    //                                 'jumlah_biaya_tambahan' =>  (float)($d['jumlah_biaya_tambahan']),
    //                                 'total_biaya_tambahan' =>  (float)($d['total_biaya_tambahan']),
    //                                 'pajak_id' =>  (int)($d['pajak_id']),
    //                                 'nama_pajak' =>  $d['nama_pajak'],
    //                                 'jumlah_pajak' =>  (float)($d['jumlah_pajak']),
    //                                 'total_pajak' =>  (float)($d['total_pajak']),
    //                                 'subtotal' =>  (float)($d['subtotal']),
    //                                 'total_item' =>  (float)($d['total_item']),
    //                                 'total' =>  (float)($d['total'])
    //                             ]);
    //             foreach ($d['pesanan'] as $i) {
    //                 $pemesanan
    //                     ->item()
    //                     ->updateOrCreate(
    //                     [   'menu_id' =>(int)$i['menu_id'],
    //                         'tipe_penjualan_id' => (int)$i['tipe_penjualan_id'],
    //                         'variasi_menu_id' => (int)$i['variasi_menu_id']
    //                     ],
    //                     [
    //                         'outlet_id' => $data['outlet_id'],
    //                         'menu_id' => (int)$i['menu_id'],
    //                         'nama_menu' => $i['nama_menu'],
    //                         'variasi_menu_id' => (int)$i['variasi_menu_id'],
    //                         'nama_variasi_menu' => $i['nama_variasi_menu'],
    //                         'tipe_penjualan_id' => (int)$i['tipe_penjualan_id'],
    //                         'nama_tipe_penjualan' => $i['nama_tipe_penjualan'],
    //                         'jumlah' => (float)$i['jumlah'],
    //                         'harga' => (float)$i['harga'],
    //                         'total' => (float)$i['total'],
    //                     ]);
    //             }
    //         }
    //         DB::commit();
    //         return response('success',200);
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         return response('error',500);
    //     }
    // }

    // private function validation(){
    //     return [
    //         'outlet_id' => 'required|integer',
    //         'pemesanan' => 'required',
    //     ];
    // }
}
