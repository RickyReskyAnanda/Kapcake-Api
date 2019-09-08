<?php

namespace App\Http\Controllers\Api;

use DB;
use App\Barang;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Barang as BarangResource;

class BarangController extends Controller
{
    public function index(Request $request)
    {   
        $this->authorize('view', Barang::class);
        if(isset($request->paginate) && $request->paginate == 'true')
            $data = $request->user()->bisnis
                    ->barang()
                    ->with('kategori', 'satuan','outlet')
                    ->where(function($q) use ($request){
                        if($request->has('outlet_id') && $request->outlet_id != '' && $request->outlet_id != 0)
                            $q->where('outlet_id', $request->outlet_id);

                        if($request->has('kategori_barang_id') && $request->kategori_barang_id != '' && $request->kategori_barang_id != 0)
                            $q->where('kategori_barang_id', $request->kategori_barang_id);

                        if($request->has('pencarian')){
                            $q->where(function($q) use ($request){
                                $q->whereHas('kategori', function($q) use ($request){
                                    $q->where('nama_kategori_barang', 'like', '%'.$request->pencarian.'%');
                                });
                                $q->orWhereHas('satuan', function($q) use ($request){
                                    $q->where('satuan', 'like', '%'.$request->pencarian.'%');
                                });
                                $q->orWhere('nama_barang', 'like', '%'.$request->pencarian.'%');
                            });
                        }
                    })
                    ->orderBy('created_at','desc')
                    ->paginate();
        // else
        //     $data = $request->user()->bisnis
        //             ->barang()
        //             ->with('kategori', 'satuan')
        //             ->where('outlet_id', request()->outlet_id)
        //             ->where(function($q){
        //                 $q->whereHas('kategori', function($q){
        //                     $q->where('nama_kategori_barang', 'like', '%'.request()->pencarian.'%');
        //                 });
        //                 $q->orWhereHas('satuan', function($q){
        //                     $q->where('satuan', 'like', '%'.request()->pencarian.'%');
        //                 });
        //             })
        //             ->get();
        return BarangResource::collection($data);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Barang::class);

        $data = $request->validate($this->validation());
        DB::beginTransaction();
        try {   
                $barang = $request->user()->bisnis
                                ->barang()
                                ->create($data);
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function show(Barang $barang)
    {
        $this->authorize('show', $barang);
        return $barang;
    }

    public function update(Request $request, Barang $barang)
    {
        $this->authorize('update', $barang);

        $data = $request->validate($this->validation());

        DB::beginTransaction();
        try {   
            $barang->update($data);
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function destroy(Barang $barang)
    {
        $this->authorize('delete', $barang);

        DB::beginTransaction();
        try {
            $barang->delete();
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function validation(){
        return [
            'nama_barang' => 'required',
            'kategori_barang_id' => 'required',
            'is_inventarisasi' => 'required',
            'stok' => 'required',
            'stok_rendah' => 'required',
            'satuan_id' => 'required',
            'keterangan' => 'required',
            'outlet_id' => 'nullable',
        ];
    }
}
