<?php

namespace App\Http\Controllers\Api;

use DB;
use App\Transfer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransferIndex as TransferIndexResource;
use App\Http\Resources\TransferShow as TransferShowResource;

class TransferController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view', Transfer::class);

        if(isset($request->paginate) && $request->paginate == 'true'){
            $data = $request->user()->bisnis
                    ->transfer()
                    ->latest()
                     ->where(function($q) use ($request){
                        if($request->has('outlet_id') && $request->outlet_id !== '0' )
                            $q->where(function($q) use ($request){
                                $q->where('outlet_asal_id', $request->outlet_id);
                                $q->orWhere('outlet_tujuan_id', $request->outlet_id);
                            });
                        if($request->has('jenis_item'))
                            $q->where('tipe_item', $request->jenis_item);
                        if($request->has('tanggal_awal') && $request->has('tanggal_akhir'))
                            $q->whereBetween('created_at', [$request->tanggal_awal.' 00:00:00', $request->tanggal_akhir.' 23:59:59']);
                    })
                    ->paginate();
        return TransferIndexResource::collection($data);
        }
    }

    public function store(Request $request)
    {
        $this->authorize('create', Transfer::class);
        
        $data = $request->validate($this->validation());
        DB::beginTransaction();
        try {   
            $transfer = $request->user()->bisnis
                            ->transfer()
                            ->create($data['data']);
            foreach ($data['entry'] as $d) {
                $transfer
                    ->entry()
                    ->create($d);
            }
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function show(Transfer $transfer)
    {
        $this->authorize('show', $transfer);
        $transfer->load('entry');
        return new TransferShowResource($transfer);
    }

    public function validation(){
        return [
            'data' => 'required',
            'entry' => 'required'
        ];
    }
}
