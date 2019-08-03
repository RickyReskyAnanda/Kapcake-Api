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
                    // ->where(function($q){
                    //     if(isset(request()->pencarian))
                    //         $q->where('nama_kategori_menu', request()->pencarian);
                    // })
                    ->paginate();
        else
            $data = $request->user()->bisnis
                    ->penyesuaianStok()
                    // ->where(function($q){
                    //     if(isset(request()->pencarian))
                    //         $q->where('nama_kategori_menu', request()->pencarian);
                    // })
                    ->get();
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
