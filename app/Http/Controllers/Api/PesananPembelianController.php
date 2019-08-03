<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\PesananPembelian;
use App\PesananPembelianEntry;
use App\Http\Resources\PesananPembelianIndex as PesananPembelianIndexResource;
use App\Http\Resources\PesananPembelianShow as PesananPembelianShowResource;


class PesananPembelianController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view', PesananPembelian::class);

        if(isset($request->paginate) && $request->paginate == 'true')
            $data = $request->user()->bisnis
                    ->pesananPembelian()
                    ->where('outlet_id', auth()->user()->outlet_terpilih_id)
                    ->where('tipe_item', auth()->user()->jenis_item_terpilih)
                    ->whereBetween('created_at', [$request->tanggal_awal.' 00:00:00', $request->tanggal_akhir.' 23:59:59'])
                    ->paginate();
        else
            $data = $request->user()->bisnis
                    ->pesananPembelian()
                    // ->where(function($q){
                    //     if(isset(request()->pencarian))
                    //         $q->where('nama_kategori_menu', request()->pencarian);
                    // })
                    ->get();
        return PesananPembelianIndexResource::collection($data);
    }

    public function store(Request $request)
    {
        $this->authorize('create', PesananPembelian::class);
        
        $data = $request->validate($this->validation());
        DB::beginTransaction();
        try {   
            $pesananPembelian = $request->user()->bisnis
                            ->pesananPembelian()
                            ->create($data['data']);
            
            $hargaTotal = 0;
            foreach ($data['entry'] as $d) {
                $entry = $pesananPembelian
                    ->entry()
                            
                    ->create($d);
                $hargaTotal +=(float)$entry->harga_total;
            }

            $pesananPembelian->update([ 'total' => $hargaTotal]);

            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function show(PesananPembelian $pesananPembelian)
    {
        $this->authorize('show', $pesananPembelian);
        $pesananPembelian->load('entry');
        return new PesananPembelianShowResource($pesananPembelian);
    }

    public function update(Request $request, PesananPembelian $pesananPembelian)
    {
        $this->authorize('update', $pesananPembelian);

        $data = $request->validate($this->validation());
        DB::beginTransaction();
        try {   
            $idEntry = [];
            $hargaTotal = 0;
            foreach ($data['entry'] as $d) {
            	$entry = [];
            	if(isset($d['id'])){
	                $entry =$pesananPembelian
                        ->entry()
                        ->findOrFail($d['id']);
                    $entry->update([
                            'item_id' => $d['item_id'],
                            'tipe_item' => $d['tipe_item'],
                            'stok' => $d['stok'],
                            'stok_dipesan' => $d['stok_dipesan'],
                            'harga_satuan' => $d['harga_satuan'],
                            'harga_total' => $d['harga_total'],
                        ]);
            	}else{
	            	$entry = $pesananPembelian
	                    ->entry()
	                    ->create([
                            'item_id' => $d['item_id'],
                            'tipe_item' => $d['tipe_item'],
                            'stok' => $d['stok'],
                            'stok_dipesan' => $d['stok_dipesan'],
                            'harga_satuan' => $d['harga_satuan'],
                            'harga_total' => $d['harga_total'],
                        ]);
                }
                array_push($idEntry, $entry['id_pesanan_pembelian_entry']); 
                $hargaTotal += $d['harga_total'];
            }

            $pesananPembelian
                ->entry()
                ->whereNotIn('id_pesanan_pembelian_entry', $idEntry)
                ->delete();

            $pesananPembelian->update($data['data'] + ['total' => $hargaTotal]);
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function validation(){
        return [
            'data' => 'required',
            'entry' => 'required',
        ];
    }
}
