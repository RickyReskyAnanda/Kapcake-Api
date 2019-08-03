<?php

namespace App\Http\Controllers\Api;
use DB;
use App\KategoriBahanDapur;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\KategoriBahanDapur as KategoriBahanDapurResource;

class KategoriBahanDapurController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view', KategoriBahanDapur::class);

        if(isset($request->paginate) && $request->paginate == 'true')
            $data =  $request->user()->bisnis
                    ->kategoriBahanDapur()
                    ->where(function($q){
                        $q->where('is_paten', 0);
                        $q->where('outlet_id', auth()->user()->outlet_terpilih_id);
                        $q->where('nama_kategori_bahan_dapur','like', '%'.request()->pencarian.'%');
                    })->paginate();
        else
            $data =  $request->user()->bisnis
                    ->kategoriBahanDapur()
                    // ->where(function($q){
                    //     if(isset(request()->pencarian))
                    //         $q->where('nama_kategori_menu', request()->pencarian);
                    // })
                    ->get();

        return KategoriBahanDapurResource::collection($data);
    }

    public function store(Request $request)
    {
        $this->authorize('create', KategoriBahanDapur::class);
        
        $data = $request->validate($this->validation());
        DB::beginTransaction();
        try {   
            foreach($request->outlet as $o)
                $kategoriBahanDapur = $request->user()->bisnis
                            ->kategoriBahanDapur()
                            ->create([
                                'outlet_id' => $o['outlet_id'],
                                'nama_kategori_bahan_dapur' => $data['nama_kategori_bahan_dapur']
                            ]);
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
        
        return $kategoriBahanDapur;
    }

    public function update(Request $request, KategoriBahanDapur $kategoriBahanDapur)
    {
        $this->authorize('update', $kategoriBahanDapur);

        $data = $request->validate($this->validation());

        DB::beginTransaction();
        try {   
            $kategoriBahanDapur
                ->update([
                    'nama_kategori_bahan_dapur' => $data['nama_kategori_bahan_dapur']
                ]);

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
            'outlet' => 'nullable',
        ];
    }
}
