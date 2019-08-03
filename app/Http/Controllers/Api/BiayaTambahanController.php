<?php

namespace App\Http\Controllers\api;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BiayaTambahan as BiayaTambahanResource;
use App\BiayaTambahan;

class BiayaTambahanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view', BiayaTambahan::class);

        if(isset($request->paginate) && $request->paginate == 'true')
            $data = $request->user()->bisnis
                    ->biayaTambahan()
                    ->where(function($q){
                        $q->where('outlet_id', auth()->user()->outlet_terpilih_id);
                        $q->where('nama_biaya_tambahan', 'like', '%'.request()->pencarian.'%');
                    })->paginate();
        else
            $data = $request->user()->bisnis
                    ->biayaTambahan()
                    ->where(function($q){
                        $q->where('outlet_id', auth()->user()->outlet_terpilih_id);
                    })
                    ->get();
        return BiayaTambahanResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $this->authorize('create', BiayaTambahan::class);

        $data = $request->validate($this->validation());
        DB::beginTransaction();
        try {   
            foreach ($data['outlet'] as $d) {
                $biayaTambahan = $request->user()->bisnis
                            ->biayaTambahan()
                            ->create([
                                'outlet_id' => $d['outlet_id'],
                                'nama_biaya_tambahan' => $data['nama_biaya_tambahan'],
                                'jumlah' => $data['jumlah']
                            ]);
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
    public function show(BiayaTambahan $biayaTambahan)
    {
        $this->authorize('show', $biayaTambahan);

        return new BiayaTambahanResource($biayaTambahan);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BiayaTambahan $biayaTambahan)
    {
        $this->authorize('update', $biayaTambahan);

        $data = $request->validate($this->validation());

        DB::beginTransaction();
        try {   
            $biayaTambahan
                ->update([
                    'nama_biaya_tambahan' => $data['nama_biaya_tambahan'],
                    'jumlah' => $data['jumlah']
                ]);

            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(BiayaTambahan $biayaTambahan)
    {
        $this->authorize('delete', $biayaTambahan);
        
        DB::beginTransaction();
        try {
            $biayaTambahan->delete();
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function validation(){
        return [
            'nama_biaya_tambahan' => 'required|max:50',
            'jumlah' => 'required|numeric|max:100',
            'outlet' => 'nullable',
        ];
    }
}
