<?php

namespace App\Http\Controllers\api;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Diskon as DiskonResource;
use App\Diskon;

class DiskonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $this->authorize('view', Diskon::class);

        if(isset($request->paginate) && $request->paginate == 'true')
            $data = $request->user()->bisnis
                    ->diskon()
                    ->where('outlet_id', $request->has('outlet_id') ? $request->outlet_id : '')
                    ->where(function($q) use ($request){
                        $q->where('nama_diskon', 'like', '%'.$request->pencarian.'%');
                        $q->orWhere('jumlah', 'like', '%'.$request->pencarian.'%');
                    })
                    ->latest()
                    ->paginate();
        else
            $data = $request->user()->bisnis
                    ->diskon()
                    ->where('nama_diskon', $request->has('outlet_id') ? $request->outlet_id : '0' )
                    ->orderBy('nama_diskon','asc')
                    ->get();

        return DiskonResource::collection($data);
    }

    public function store(Request $request)
    {
        // $this->authorize('create', Diskon::class);
        
        $data = $request->validate($this->validation());
        DB::beginTransaction();
        try {   
                $request->user()->bisnis
                            ->diskon()
                            ->create($data);
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            Debugbar::addThrowable($e);
            return response('error',500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Diskon $diskon)
    {
        // $this->authorize('show', $diskon);
        return new DiskonResource($diskon);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Diskon $diskon)
    {
        // $this->authorize('update', $diskon);

        $data = $request->validate($this->validation());

        DB::beginTransaction();
        try {   
            $diskon
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
    public function destroy(Diskon $diskon)
    {
        // $this->authorize('delete', $diskon);

        DB::beginTransaction();
        try {
            $diskon->delete();
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function validation(){
        return [
            'nama_diskon' => 'required|max:50',
            'jumlah' => 'required|numeric|max:100',
            'outlet_id' => 'nullable|numeric',
        ];
    }
}
