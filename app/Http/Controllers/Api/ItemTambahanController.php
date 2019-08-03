<?php

namespace App\Http\Controllers\Api;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ItemTambahan as ItemTambahanResource;
use App\ItemTambahan;
class ItemTambahanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view', ItemTambahan::class);

        if(isset($request->paginate) && $request->paginate == 'true')
            $data = $request->user()->bisnis
                    ->itemTambahan()
                    ->where('outlet_id', auth()->user()->outlet_terpilih_id)
                    ->where(function($q){
                        if(isset(request()->pencarian))
                            $q->where('nama_item_tambahan', 'like', '%'.request()->pencarian.'%');
                            $q->orWhere('harga', 'like', '%'.request()->pencarian.'%');
                    })
                    ->paginate();
        else
            $data = $request->user()->bisnis
                    ->itemTambahan()
                    ->get();
        return ItemTambahanResource::collection($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $this->authorize('create', ItemTambahan::class);

        $data = $request->validate($this->validation());

        DB::beginTransaction();
        try { 
            foreach ($data['outlet'] as $outlet) {
                $itemTambahan = $request->user()->bisnis
                                ->itemTambahan()
                                ->create([
                                    'outlet_id' => $outlet['outlet_id'],
                                    'nama_item_tambahan' => $data['nama_item_tambahan'],
                                    'harga' => $data['harga']
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
    public function show(ItemTambahan $itemTambahan)
    {
        $this->authorize('show', $itemTambahan);

        return new ItemTambahanResource($itemTambahan);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ItemTambahan $itemTambahan)
    {
        $this->authorize('show', $itemTambahan);

        $data = $request->validate($this->validation());

        DB::beginTransaction();
        try {   
            $itemTambahan
                ->update([
                    'nama_item_tambahan' => $data['nama_item_tambahan'],
                    'harga' => $data['harga']
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
    public function destroy(ItemTambahan $itemTambahan)
    {
        $this->authorize('delete', $itemTambahan);
        
        DB::beginTransaction();
        try {
            $itemTambahan->delete();
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function validation(){
        return [
            'nama_item_tambahan' => 'required|max:50',
            'harga' => 'required',
            'outlet' => 'nullable',
        ];
    }
}
