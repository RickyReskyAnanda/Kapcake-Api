<?php

namespace App\Http\Controllers\Kasir;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Pemesanan;

class PenjualanController extends Controller
{
   
    public function index(Request $request)
    {
        $data = $request->user()->bisnis
                ->outlet()
                ->pemesanan()
                ->get();

        return response()->json([
            'data' => $data,
            'total' => count($data)
        ], 200);
    }

    /*
    |-----------------------------------------------------------------------------
    |                         FUNGSI STORE 
    |----------------------------------------------------------------------------
    |   1. cek data apakah ada, jika ada maka  update dan jika belum ada maka untuk kondisi yang belum diketahui
    |   2. 
    */

    public function store(Request $request){
        // $this->authorize('create', Barang::class);

        $data = $request->validate($this->validation());
        $dataKode = [];
        DB::beginTransaction();
        try {   
            foreach ($data['penjualan'] as $d) {
                array_push($dataKode, $d['kode_pemesanan']);
            }

            foreach ($data['penjualan'] as $d) {
                $item = $d['item'];
                unset($d['item']);

                $penjualan = $request->user()->bisnis
                                ->penjualan()
                                ->create([
                                    'outlet_id' => $data['outlet_id'],
                                    'no_pemesanan' =>  (float)($d['no_pemesanan']),
                                    'kode_pemesanan' =>  $d['kode_pemesanan'],
                                    'tanggal_simpan' =>  $d['tanggal_simpan'],
                                    'waktu_simpan' =>  $d['waktu_simpan'],
                                    'tanggal_proses' =>  $d['tanggal_proses'],
                                    'waktu_proses' =>  $d['waktu_proses'],
                                    'pelanggan_id' =>  (int)($d['pelanggan_id']),
                                    'kategori_meja_id' =>  (int)($d['kategori_meja_id']),
                                    'meja_id' =>  (int)($d['meja_id']),
                                    'diskon_id' =>  (int)($d['diskon_id']),
                                    'jumlah_diskon' =>  (float)($d['jumlah_diskon']),
                                    'total_diskon' =>  (float)($d['total_diskon']),
                                    'biaya_tambahan_id' =>  (int)($d['biaya_tambahan_id']),
                                    'jumlah_biaya_tambahan' =>  (float)($d['jumlah_biaya_tambahan']),
                                    'total_biaya_tambahan' =>  (float)($d['total_biaya_tambahan']),
                                    'pajak_id' =>  (int)($d['pajak_id']),
                                    'jumlah_pajak' =>  (float)($d['jumlah_pajak']),
                                    'total_pajak' =>  (float)($d['total_pajak']),
                                    'subtotal' =>  (float)($d['subtotal']),
                                    'total_item' =>  (float)($d['total_item']),
                                    'total' =>  (float)($d['total']),
                                ]);
                foreach ($item as $i) {
                    $penjualan
                        ->item()
                        ->create([
                            'outlet_id' => $data['outlet_id'],
                            'menu_id' => (int)$i['menu_id'],
                            'nama_menu' => $i['nama_menu'],
                            'variasi_menu_id' => (int)$i['variasi_menu_id'],
                            'nama_variasi_menu' => $i['nama_variasi_menu'],
                            'tipe_penjualan_id' => (int)$i['tipe_penjualan_id'],
                            'nama_tipe_penjualan' => $i['nama_tipe_penjualan'],
                            'jumlah' => (float)$i['jumlah'],
                            'harga' => (float)$i['harga'],
                            'total' => (float)$i['total'],
                        ]);
                }
            }

            Pemesanan::whereIn('kode_pemesanan',$dataKode)->delete();

            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    private function validation(){
        return [
            'outlet_id' => 'required|integer',
            'penjualan' => 'required',
        ];
    }

}