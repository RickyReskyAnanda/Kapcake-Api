<?php

namespace App\Http\Controllers\Api;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TipePenjualan;
use App\Http\Resources\TipePenjualan as TipePenjualanResource;

class TipePenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view', TipePenjualan::class);

        if(isset($request->paginate) && $request->paginate == 'true')
            $data = $request->user()->bisnis
                    ->tipePenjualan()
                    ->where(function($q){
                        $q->where('outlet_id', auth()->user()->outlet_terpilih_id);
                        if(isset(request()->pencarian))
                            $q->where('nama_tipe_penjualan', 'like', '%'.request()->pencarian.'%');
                    })->paginate();
        else
            $data = $request->user()->bisnis
                    ->tipePenjualan()
                    ->where(function($q){
                    //     if(isset(request()->pencarian))
                            $q->where('outlet_id', auth()->user()->outlet_terpilih_id);
                    })->get();

        return TipePenjualanResource::collection($data);
    }

    public function store(Request $request)
    {
        $this->authorize('create', TipePenjualan::class);

        $data = $request->validate($this->validation());
        DB::beginTransaction();
        try {   
            foreach ($data['outlet'] as $o) {
                $tipePenjualan = $request->user()->bisnis
                                ->tipePenjualan()
                                ->create([
                                    'outlet_id' => $o['outlet_id'],
                                    'nama_tipe_penjualan' => $data['nama_tipe_penjualan'],
                                ]);
                
                foreach ($data['biaya_tambahan'] as $d) {
                    $tipePenjualan
                        ->biayaTambahan()
                        ->create($d + $o);
                }
            }

            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(TipePenjualan $tipePenjualan)
    {
        $this->authorize('show', $tipePenjualan);
        $tipePenjualan->load('biayaTambahan');
        return new TipePenjualanResource($tipePenjualan);
    }

    public function update(Request $request, TipePenjualan $tipePenjualan)
    {
        $this->authorize('update', $tipePenjualan);

        $data = $request->validate($this->validation());

        DB::beginTransaction();
        try {   
            $tipePenjualan
                ->update([
                    'nama_tipe_penjualan' => $data['nama_tipe_penjualan'],
                ]);
            $idTipePenjualanBiayaTambahan = [];
            foreach ($data['biaya_tambahan'] as $d) {
                $opsi = $tipePenjualan
                    ->biayaTambahan()
                    ->updateOrCreate(['biaya_tambahan_id' => $d['id'], 'outlet_id' => $tipePenjualan->outlet_id]);
                array_push($idTipePenjualanBiayaTambahan, $opsi['id_tipe_penjualan_biaya_tambahan']); 
            }
            $tipePenjualan
                ->biayaTambahan()
                ->whereNotIn('id_tipe_penjualan_biaya_tambahan', $idTipePenjualanBiayaTambahan)
                ->delete();

            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function destroy(TipePenjualan $tipePenjualan)
    {
        $this->authorize('delete', $tipePenjualan);

        DB::beginTransaction();
        try {
            $tipePenjualan->delete();
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function validation(){
        return [
            'nama_tipe_penjualan' => 'required|max:50',
            'biaya_tambahan' => 'nullable',
            'outlet' => 'nullable',
        ];
    }
}
