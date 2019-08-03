<?php

namespace App\Http\Controllers\Api;

use DB;
use App\BahanDapur;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Image\BlobImageConvertion;
use App\Http\Resources\BahanDapur as BahanDapurResource;

class BahanDapurController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view', BahanDapur::class);

        if(isset($request->paginate) && $request->paginate == 'true')
            $data = $request->user()->bisnis
                    ->bahanDapur()
                    ->with('kategori', 'satuan')
                    ->where('outlet_id', auth()->user()->outlet_terpilih_id)
                    ->where('kategori_bahan_dapur_id', $request->kategori_bahan_dapur_id > 0 ? '=' :'!=' , $request->kategori_bahan_dapur_id )
                    ->where(function($q){
                        $q->whereHas('kategori', function($q){
                            $q->where('nama_kategori_bahan_dapur', 'like', '%'.request()->pencarian.'%');
                        });
                        $q->orWhereHas('satuan', function($q){
                            $q->where('satuan', 'like', '%'.request()->pencarian.'%');
                        });
                        $q->orWhere('nama_bahan_dapur', 'like', '%'.request()->pencarian.'%');
                    })
                    ->paginate();
        // else
        //     $data = $request->user()->bisnis
        //             ->perlengkapan()
        //             ->with('kategori', 'satuan')
        //             ->where('outlet_id', request()->outlet_id)
        //             ->where(function($q){
        //                 $q->whereHas('kategori', function($q){
        //                     $q->where('nama_kategori_perlengkapan', 'like', '%'.request()->pencarian.'%');
        //                 });
        //                 $q->orWhereHas('satuan', function($q){
        //                     $q->where('satuan', 'like', '%'.request()->pencarian.'%');
        //                 });
        //             })
        //             ->get();
        return BahanDapurResource::collection($data);
    }

    public function store(Request $request)
    {
        $this->authorize('create', BahanDapur::class);

        $data = $request->validate($this->validation());
        DB::beginTransaction();
        try {   
            foreach ($data['outlet'] as $d) {
                $request->user()->bisnis
                    ->bahanDapur()
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

    public function show(BahanDapur $bahanDapur)
    {
        $this->authorize('view', BahanDapur::class);

        return $bahanDapur;
    }

    public function update(Request $request, BahanDapur $bahanDapur)
    {
        $this->authorize('update', $bahanDapur);

        $data = $request->validate($this->validation());

        DB::beginTransaction();
        try {   
            $bahanDapur
                ->update($data['data']);

            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function destroy(BahanDapur $bahanDapur)
    {
        $this->authorize('delete', $bahanDapur);

        DB::beginTransaction();
        try {
            $bahanDapur->delete();
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
