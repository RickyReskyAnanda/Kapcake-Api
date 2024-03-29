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
                    ->where('outlet_id', $request->has('outlet_id') ? $request->outlet_id : '')
                    ->where(function($q) use ($request){
                        $q->where('nama_pajak', 'like', '%'.$request->pencarian.'%');
                        $q->orWhere('jumlah', 'like', '%'.$request->pencarian.'%');
                    })
                    ->latest()
                    ->paginate();
        else
            $data = $request->user()->bisnis
                    ->pajak()
                    ->where('outlet_id', $request->has('outlet_id') ? $request->outlet_id : '0' )
                    ->orderBy('nama_pajak','asc')
                    ->get();

        return PajakResource::collection($data);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Pajak::class);
        
        $data = $request->validate($this->validation());
        DB::beginTransaction();
        try {   
                $request->user()->bisnis
                            ->pajak()
                            ->create($data);
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
                ->update($data);

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
            'outlet_id' => 'nullable|numeric',
        ];
    }
}
