<?php

namespace App\Http\Controllers\Api;

use DB;
use App\KategoriBarang;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\KategoriBarang as KategoriBarangResource;

class KategoriBarangController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view', KategoriBarang::class);
        if(request()->has('paginate') && $request->paginate == 'true'){
            $data = $request->user()->bisnis
                    ->kategoriBarang()
                    ->where(function($q){
                        $q->where('is_paten', 0);
                        $q->where('outlet_id', auth()->user()->outlet_terpilih_id);
                        $q->where('nama_kategori_barang','like', '%'.request()->pencarian.'%');
                    })->paginate();
            return KategoriBarangResource::collection($data);
        }
        else
            return $data = $request->user()->bisnis
                    ->kategoriBarang()
                    ->where('outlet_id', auth()->user()->outlet_terpilih_id)    
                    ->get();
                    
    }

    public function store(Request $request)
    {
        $this->authorize('create', KategoriBarang::class);

        $data = $request->validate($this->validation());

        DB::beginTransaction();
        try {   
            foreach($request->outlet as $o)
                $kategoriBarang = $request->user()->bisnis
                            ->kategoriBarang()
                            ->create([
                                'outlet_id' => $o['outlet_id'],
                                'nama_kategori_barang' => $data['nama_kategori_barang']
                            ]);
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function show(KategoriBarang $kategoriBarang)
    {
        $this->authorize('show', $kategoriBarang);

        return $kategoriBarang;
    }

    public function update(Request $request, KategoriBarang $kategoriBarang)
    {
        $this->authorize('update', $kategoriBarang);

        $data = $request->validate($this->validation());

        DB::beginTransaction();
        try {   
            $kategoriBarang
                ->update([
                    'nama_kategori_barang' => $data['nama_kategori_barang']
                ]);

            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function destroy(KategoriBarang $kategoriBarang)
    {
        $this->authorize('delete', $kategoriBarang);

        DB::beginTransaction();
        try {
            $kategoriBarang->delete();
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function validation(){
        return [
            'nama_kategori_barang' => 'required|max:50',
            'outlet' => 'nullable',
        ];
    }
}
