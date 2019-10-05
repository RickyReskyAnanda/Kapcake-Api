<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Perangkat as PerangkatResource;
use App\Perangkat;
use DB;

class PerangkatController extends Controller
{
    public function index(Request $request)
    {
        // $this->authorize('view', Perangkat::class);

        // if(isset($request->paginate) && $request->paginate == 'true')
            $data = $request->user()
                    ->bisnis
                    ->perangkat()
                    ->where(function($q) use ($request){
                        if($request->has('status') && $request->status != '')
                            $q->where('is_aktif', $request->status);
                        if($request->has('pencarian') && $request->pencarian != '')
                            $q->where('nama_perangkat', 'like','%'.$request->pencarian.'%');

                        if($request->has('outlet_id') && $request->outlet_id != 0)
                            $q->where('outlet_id', $request->outlet_id);
                    })
                    ->paginate();
            return PerangkatResource::collection($data);
    }

    public function show(Perangkat $perangkat)
    {
        // $this->authorize('show', $perangkat);
        return new PerangkatResource($perangkat);
    }

    public function update(Request $request, Perangkat $perangkat)
    {
        // $this->authorize('update', $perangkat);
        
        $data = $request->validate($this->validation());

        DB::beginTransaction();
        try {   
            $perangkat
                ->update([
                    'nama_perangkat' => $data['nama_perangkat'],
                ]);
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function validation(){
        return [
            'nama_perangkat' => 'required|max:50',
        ];
    }
}
