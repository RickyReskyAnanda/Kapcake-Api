<?php

namespace App\Http\Controllers\Api;
use DB;
use App\KategoriBahanDapur;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\KategoriBahanDapur as KategoriBahanDapurResource;
use App\Http\Resources\KategoriBahanDapurShow as KategoriBahanDapurShowResource;

class KategoriBahanDapurController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view', KategoriBahanDapur::class);

        if($request->has('paginate') && $request->paginate == 'true')
            $data =  $request->user()->bisnis
                    ->kategoriBahanDapur()
                    ->with('outlet')
                    ->where(function($q) use ($request){
                        if($request->has('outlet_id') && $request->outlet_id != '' && $request->outlet_id != 0)
                            $q->where('outlet_id', $request->outlet_id);
                        if($request->has('pencarian'))
                            $q->where('nama_kategori_bahan_dapur','like', '%'.$request->pencarian.'%');
                    })
                    ->latest()
                    ->paginate();
        else
            $data =  $request->user()->bisnis
                    ->kategoriBahanDapur()
                    ->where('outlet_id', $request->has('outlet_id') ? $request->outlet_id : 0)    
                    ->orderBy('nama_kategori_bahan_dapur','asc')
                    ->get();

        return KategoriBahanDapurResource::collection($data);
    }

    public function store(Request $request)
    {
        $this->authorize('create', KategoriBahanDapur::class);
        
        $data = $request->validate($this->validation());
        DB::beginTransaction();
        try {   
                $request->user()->bisnis
                            ->kategoriBahanDapur()
                            ->create($data);
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function show(KategoriBahanDapur $kategoriBahanDapur)
    {
        $this->authorize('show', $kategoriBahanDapur);
        
        return new KategoriBahanDapurShowResource($kategoriBahanDapur);
    }

    public function update(Request $request, KategoriBahanDapur $kategoriBahanDapur)
    {
        $this->authorize('update', $kategoriBahanDapur);

        $data = $request->validate($this->validation());

        DB::beginTransaction();
        try {   
            $kategoriBahanDapur
                ->update($data);

            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function destroy(KategoriBahanDapur $kategoriBahanDapur)
    {
        $this->authorize('delete', $kategoriBahanDapur);

        DB::beginTransaction();
        try {
            $kategoriBahanDapur->delete();
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    private function validation(){
        return [
            'nama_kategori_bahan_dapur' => 'required|max:255',
            'outlet_id' => 'nullable',
        ];
    }
}
