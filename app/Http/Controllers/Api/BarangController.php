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
                    ->with('kategori', 'satuan')
                    ->where('outlet_id', $request->outlet_id)
                    ->where('kategori_barang_id', $request->kategori_barang_id > 0 ? '=' :'!=' , $request->kategori_barang_id )
                    ->where(function($q){
                        $q->whereHas('kategori', function($q){
                            $q->where('nama_kategori_barang', 'like', '%'.request()->pencarian.'%');
                        });
                        $q->orWhereHas('satuan', function($q){
                            $q->where('satuan', 'like', '%'.request()->pencarian.'%');
                        });
                        $q->orWhere('nama_barang', 'like', '%'.request()->pencarian.'%');
                    })
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
        return barangResource::collection($data);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Barang::class);

        $data = $request->validate($this->validation());
        DB::beginTransaction();
        try {   
            foreach ($data['outlet'] as $d) {
                $barang = $request->user()->bisnis
                                ->barang()
                                ->create(
                                    $data['data'] + $d
                                );
            }
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
            $barang
                ->update($data['data']);
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
            'data' => 'required',
            'outlet' => 'nullable',
        ];
    }
}
