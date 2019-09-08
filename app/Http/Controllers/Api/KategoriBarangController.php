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
                    ->with('outlet')
                    ->where(function($q) use ($request){
                        if($request->has('outlet_id') && $request->outlet_id != '' && $request->outlet_id != 0)
                            $q->where('outlet_id', $request->outlet_id);
                        if($request->has('pencarian'))
                            $q->where('nama_kategori_barang','like', '%'.request()->pencarian.'%');
                    })
                    ->latest()
                    ->paginate();
        }
        else
            $data = $request->user()->bisnis
                    ->kategoriBarang()
                    ->where('outlet_id', $request->has('outlet_id') ? $request->outlet_id : 0)    
                    ->orderBy('nama_kategori_barang','asc')
                    ->get();

        return KategoriBarangResource::collection($data);
    }

    public function store(Request $request)
    {
        $this->authorize('create', KategoriBarang::class);

        $data = $request->validate($this->validation());

        DB::beginTransaction();
        try {   
                $request->user()->bisnis
                            ->kategoriBarang()
                            ->create($data);
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
            $kategoriBarang ->update($data);
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
            'outlet_id' => 'nullable',
        ];
    }
}
