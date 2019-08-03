<?php

namespace App\Http\Controllers\Kasir;

use DB;
use App\Outlet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OutletController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->user()->bisnis
                ->outlet()
                ->with(
                    'menu',
                    'menu.tipePenjualan.tipePenjualan',
                    'menu.gambar',
                    'menu.variasi.tipePenjualan',
                    'kategoriMenu', 
                    'kategoriMeja.meja', 
                    'pajak', 
                    'diskon',
                    'biayaTambahan',
                    'jenisPemesanan',
                    'pemesanan.item',
                    'penjualan.item'
                )
                ->where('id_outlet', $request->outlet_id)
                ->first();

        
        $noUrutPesanan = $data
                    ->pemesanan
                    ->where('tanggal_simpan', date('Y-m-d'))  /// tanggalnya belum bisa disesuaikan dgn perangkat 
                    ->max('no_pemesanan');

        return response()->json([
            'jenis_pemesanan' => $data->jenisPemesanan,
            'kategori_menu' => $data->kategoriMenu,
            'menu' => $data->menu,
            'kategori_meja' => $data->kategoriMeja,
            'pelanggan' => $request->user()->bisnis->pelanggan ?? [],
            'pajak' => $data->pajak,
            'diskon' => $data->diskon,
            'biaya_tambahan' => $data->biayaTambahan,
            'pemesanan' => $data->pemesanan,
            'penjualan' => $data->penjualan,
            'no_urut_pesanan' => $noUrutPesanan ?? 1,
        ], 200);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Outlet::class);
        
        $data = $request->validate($this->validation());
        DB::beginTransaction();
        try {   
            $outlet = $request
                            ->user()
                            ->bisnis
                            ->outlet()
                            ->create($data);
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function show(Outlet $outlet)
    {
        $this->authorize('show', $outlet);

        return $outlet;
    }

    public function update(Request $request, Outlet $outlet)
    {
        $this->authorize('update', $outlet);

        $data = $request->validate($this->validation());

        DB::beginTransaction();
        try {   
            $outlet->update($data);
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function destroy(Outlet $outlet)
    {
        $this->authorize('delete', $outlet);
        
        DB::beginTransaction();
        try {
            $outlet->delete();
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function validation(){
        return [
            'nama_outlet' => 'required|max:255',
            'telpon' => 'required',
            'email' => 'required',
            'kota' => 'nullable',
            'provinsi' => 'nullable',
            'kode_pos' => 'nullable',
            'alamat' => 'nullable',
            'catatan' => 'nullable',
        ];
    }
}
