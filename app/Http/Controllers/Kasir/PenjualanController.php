<?php

namespace App\Http\Controllers\Kasir;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Kasir\Penjualan as PenjualanResource;
use DB;
use App\Pemesanan;

class PenjualanController extends Controller
{
   
    public function index(Request $request)
    {
        $data = $request->user()->bisnis
                ->outlet()
                ->penjualan()
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
        // DB::beginTransaction();
        // try {   
            if(isset($data['penjualan']))
            foreach ($data['penjualan'] as $d) {
                array_push($dataKode, $d['kode_pemesanan']);
            }

            if(isset($data['penjualan']))
            foreach ($data['penjualan'] as $d) {
                $item = $d['item'];
                unset($d['item']);

                $penjualan = $request->user()->bisnis
                                ->penjualan()
                                ->create([
                                    'outlet_id' => $data['outlet_id'],
                                    'status' =>  $d['status'],

                                    'no_pemesanan' =>  $d['no_pemesanan'],
                                    'kode_pemesanan' =>  $d['kode_pemesanan'],
                                    'tanggal_simpan' =>  $d['tanggal_simpan'],
                                    'waktu_simpan' =>  $d['waktu_simpan'],
                                    'tanggal_proses' =>  $d['tanggal_proses'],
                                    'waktu_proses' =>  $d['waktu_proses'],

                                    'pelanggan_id' =>  (int)($d['pelanggan_id']),
                                    'nama_pelanggan' =>  $d['nama_pelanggan'],

                                    'kategori_meja_id' =>  (int)($d['kategori_meja_id']),
                                    'nama_kategori_meja' =>  $d['nama_kategori_meja'],

                                    'meja_id' =>  (int)($d['meja_id']),
                                    'nama_meja' =>  $d['nama_meja'],
                                    
                                    'subtotal' =>  (float)($d['subtotal']),
                                    // diskon
                                    'diskon_id' =>  (int)($d['diskon_id']),
                                    'nama_diskon' =>  $d['nama_diskon'],
                                    'jumlah_diskon' =>  (float)($d['jumlah_diskon']),
                                    'total_diskon' =>  (float)($d['total_diskon']),
                                    //biaya tambahan
                                    'biaya_tambahan_id' =>  (int)($d['biaya_tambahan_id']),
                                    'nama_biaya_tambahan' =>  $d['nama_biaya_tambahan'],
                                    'jumlah_biaya_tambahan' =>  (float)($d['jumlah_biaya_tambahan']),
                                    'total_biaya_tambahan' =>  (float)($d['total_biaya_tambahan']),
                                    //pajak
                                    'pajak_id' =>  (int)($d['pajak_id']),
                                    'nama_pajak' =>  $d['nama_pajak'],
                                    'jumlah_pajak' =>  (float)($d['jumlah_pajak']),
                                    'total_pajak' =>  (float)($d['total_pajak']),

                                    'total_item' =>  (float)($d['total_item']),
                                    'total' =>  (float)($d['total']),
                                    'tunai' =>  (float)($d['tunai']),
                                    'kembalian' =>  (float)($d['kembalian']),
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
                            'catatan' =>  $i['catatan']
                        ]);
                }
            }

            Pemesanan::whereIn('kode_pemesanan',$dataKode)->delete();

            // DB::commit();

            $penjualan = $request->user()->bisnis
                                ->penjualan()
                                ->with('item','item.menu.gambar')
                                ->where('outlet_id',$data['outlet_id'])
                                ->orderBy('tanggal_proses','desc')
                                ->orderBy('waktu_proses','desc')
                                ->get();

            $response = PenjualanResource::collection($penjualan);
            return response($response, 200);
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return response('error',500);
        // }
    }

    private function validation(){
        return [
            'outlet_id' => 'required|integer',
            'penjualan' => 'nullable',
        ];
    }

}
