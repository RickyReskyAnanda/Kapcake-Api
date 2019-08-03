<?php

namespace App\Http\Controllers\Api;

use DB;
use App\Transfer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransferIndex as TransferIndexResource;

class TransferController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view', Transfer::class);

        if(isset($request->paginate) && $request->paginate == 'true')
            $data = $request->user()->bisnis
                    ->transfer()
                    ->latest()
                    ->where(function($q){
                        $q->where('outlet_asal_id', auth()->user()->outlet_terpilih_id );
                        $q->orWhere('outlet_tujuan_id', auth()->user()->outlet_terpilih_id );
                    })
                    ->whereBetween('created_at', [$request->tanggal_awal, $request->tanggal_akhir])
                    ->where('tipe_item', auth()->user()->jenis_item_terpilih)
                    ->paginate();
        else
            $data = $request->user()->bisnis
                    ->transfer()
                    ->get();

        return TransferIndexResource::collection($data);
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

        return $transfer->load('entry');
    }

    public function validation(){
        return [
            'data' => 'required',
            'entry' => 'required',
        ];
    }
}
