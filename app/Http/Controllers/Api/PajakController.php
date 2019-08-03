<?php

namespace App\Http\Controllers\api;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Pajak as PajakResource;
use App\Pajak;

class PajakController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view', Pajak::class);

        if(isset($request->paginate) && $request->paginate == 'true')
            $data = $request->user()->bisnis
                    ->pajak()
                    ->where('outlet_id', auth()->user()->outlet_terpilih_id)
                    ->where(function($q){
                        if(isset(request()->pencarian))
                            $q->where('nama_pajak', 'like', '%'.request()->pencarian.'%');
                            $q->orWhere('jumlah', 'like', '%'.request()->pencarian.'%');
                    })
                    ->paginate();
        else
            $data = $request->user()->bisnis
                    ->pajak()
                    // ->where(function($q){
                    //     if(isset(request()->pencarian))
                    //         $q->where('nama_kategori_menu', request()->pencarian);
                    // })
                    ->get();

        return PajakResource::collection($data);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Pajak::class);
        
        $data = $request->validate($this->validation());
        DB::beginTransaction();
        try {   
            foreach ($data['outlet'] as $outlet) {
                $request->user()->bisnis
                            ->pajak()
                            ->create([
                                'outlet_id' => $outlet['outlet_id'],
                                'nama_pajak' => $data['nama_pajak'],
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
    public function show(Pajak $pajak)
    {
        $this->authorize('show', $pajak);
        return new PajakResource($pajak);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pajak $pajak)
    {
        $this->authorize('update', $pajak);

        $data = $request->validate($this->validation());

        DB::beginTransaction();
        try {   
            $pajak
                ->update([
                    'nama_pajak' => $data['nama_pajak'],
                    'jumlah' => $data['jumlah'],
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
    public function destroy(Pajak $pajak)
    {
        $this->authorize('delete', $pajak);

        DB::beginTransaction();
        try {
            $pajak->delete();
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function validation(){
        return [
            'nama_pajak' => 'required|max:50',
            'jumlah' => 'required|numeric|max:100',
            'outlet' => 'nullable',
        ];
    }
}
