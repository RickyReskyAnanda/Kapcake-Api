<?php

namespace App\Http\Controllers\Kasir;

use DB;
use App\Outlet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Kasir\Printer as PrinterResource;
use App\Http\Resources\Kasir\Penjualan as PenjualanResource;

class OutletController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->user()->bisnis
                ->outlet()
                ->where('id_outlet', $request->outlet_id)
                ->first();
        $data->load([
                    'menu',
                    'menu.tipePenjualan' => function($q){
                        $q->whereHas('tipePenjualan',function($q){
                            $q->where('is_aktif',1);
                        });
                    },
                    'menu.tipePenjualan.tipePenjualan',
                    'menu.gambar',
                    'menu.variasi.tipePenjualan',
                    'kategoriMenu' => function($q){
                        $q->has('menu');
                    }, 
                    'meja', 
                    'kategoriMeja' => function($q){
                        $q->has('meja');
                    },
                    'pajakTerpilih', 
                    'diskon',
                    'biayaTambahan',
                    'jenisPemesanan'  => function($q){
                        $q->where('is_aktif',1);
                    }, 
                    'pemesanan.item',
                    'penjualan' => function($q){
                        $q->with('item.menu.gambar');
                        $q->orderBy('tanggal_proses','desc');
                        $q->orderBy('waktu_proses','desc');
                    }]
                );
        if(isset($data->pemesanan))
        $noUrutPesanan = $data
                    ->pemesanan
                    ->where('tanggal_simpan', date('Y-m-d'))  /// tanggalnya belum bisa disesuaikan dgn perangkat 
                    ->max('no_pemesanan');
        $perangkat = \App\Perangkat::find($request->perangkat_id);
        $printer = $request
                    ->user()
                    ->bisnis
                    ->printer()
                    ->where('user_id', $request->user()->id)
                    ->where('perangkat_id',$perangkat->id_perangkat)
                    ->get();

        return response()->json([
            'jenis_pemesanan' => $data->jenisPemesanan ?? [],
            'kategori_menu' => $data->kategoriMenu ?? [],
            'menu' => $data->menu ?? [],
            'kategori_meja' => $data->kategoriMeja ?? [],
            'meja' => $data->meja ?? [],
            'pelanggan' => $request->user()->bisnis->pelanggan,
            'pajak' => $data->pajakTerpilih ?? [],
            'diskon' => $data->diskon ?? [],
            'biaya_tambahan' => $data->biayaTambahan ?? [],
            'pemesanan' => $data->pemesanan ?? [],
            'penjualan' => $data->penjualan ? PenjualanResource::collection($data->penjualan) : [],
            'printer' => PrinterResource::collection($printer),
            'outlet' => $request->user()->bisnis->outlet()->where('id_outlet', $request->outlet_id)->first() ?? []
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
