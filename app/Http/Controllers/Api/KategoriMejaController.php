<?php

namespace App\Http\Controllers\Api;

use DB;
use App\KategoriMeja;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\KategoriMejaIndex as KategoriMejaIndexResource;
use App\Http\Resources\KategoriMejaShow as KategoriMejaShowResource;

class KategoriMejaController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view', KategoriMeja::class);

        if(isset($request->paginate) && $request->paginate == 'true')
            $data =  $request->user()->bisnis
                    ->kategoriMeja()
                    ->where(function($q) use ($request){
                        if($request->has('outlet_id') && $request->outlet_id != 0)
                            $q->where('outlet_id', $request->outlet_id);
                        if($request->has('pencarian'))
                            $q->where('nama_kategori_meja', request()->pencarian);
                    })
                    ->paginate();
        else
            $data = $request->user()
                    ->bisnis
                    ->kategoriMeja()
                    ->where('outlet_id', $request->has('outlet_id') ? $request->outlet_id : 0)    
                    ->where('is_aktif', 1)
                    ->get();
        return KategoriMejaIndexResource::collection($data);
    }

    public function store(Request $request)
    {
        $this->authorize('create', KategoriMeja::class);

        $data = $request->validate($this->validation());
        DB::beginTransaction();
        try {   
            $request->user()->bisnis
                            ->kategoriMeja()
                            ->create($data);
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function show(KategoriMeja $kategoriMeja)
    {
        $this->authorize('show',$kategoriMeja);

        return new KategoriMejaShowResource($kategoriMeja);
    }

    public function update(Request $request, KategoriMeja $kategoriMeja)
    {
        $this->authorize('update', $kategoriMeja);

        $data = $request->validate($this->validation());

        DB::beginTransaction();
        try {   
            $kategoriMeja->update($data);

            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function destroy(KategoriMeja $kategoriMeja)
    {
        $this->authorize('delete', $kategoriMeja);

        DB::beginTransaction();
        try {
            $kategoriMeja->delete();
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function validation(){
        return [
            'nama_kategori_meja' => 'required|max:255',
            'is_aktif' => 'required|numeric',
        ];
    }
}
