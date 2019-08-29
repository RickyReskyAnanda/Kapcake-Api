<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\PenyesuaianStok;
use App\Http\Resources\PenyesuaianStokIndex as PenyesuaianStokIndexResource;
use App\Http\Resources\PenyesuaianStokShow as PenyesuaianStokShowResource;

class PenyesuaianStokController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view', PenyesuaianStok::class);

        if(isset($request->paginate) && $request->paginate == 'true')
            $data = $request->user()->bisnis
                    ->penyesuaianStok()
                     ->where(function($q) use ($request){
                        if($request->has('outlet_id') && $request->outlet_id !== '0' )
                            $q->where('outlet_id', $request->outlet_id);
                        if($request->has('jenis_item'))
                            $q->where('tipe_item', $request->jenis_item);
                        if($request->has('status_pesanan_pembelian') && $request->status_pesanan_pembelian != '')
                            $q->where('status', $request->status_pesanan_pembelian);
                        if($request->has('tanggal_awal') && $request->has('tanggal_akhir'))
                            $q->whereBetween('created_at', [$request->tanggal_awal.' 00:00:00', $request->tanggal_akhir.' 23:59:59']);
                        if($request->has('pencarian') && $request->pencarian != '')
                            $q->where('id_pesanan_pembelian', 'like','%'.$request->pencarian.'%');
                    })
                    ->paginate(10);
        return PenyesuaianStokIndexResource::collection($data);
    }

    public function store(Request $request)
    {
        $this->authorize('create', PenyesuaianStok::class);
        
        $data = $request->validate($this->validation());
        DB::beginTransaction();
        try {   
            $penyesuaianStok = $request->user()->bisnis
                            ->penyesuaianStok()
                            ->create($data['data']);
            
            foreach ($data['entry'] as $d) {
                $penyesuaianStok
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

    public function show(PenyesuaianStok $penyesuaianStok)
    {
        $this->authorize('show', $penyesuaianStok);
        return new PenyesuaianStokShowResource($penyesuaianStok->load('entry'));
    }

    public function validation(){
        return [
            'data' => 'required',
            'entry' => 'required',
        ];
    }
}
