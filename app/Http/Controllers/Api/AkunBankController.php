<?php

namespace App\Http\Controllers\Api;

use DB;
use App\AkunBank;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\AkunBankIndex as AkunBankIndexResource;

class AkunBankController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view', AkunBank::class);

        if(isset($request->paginate) && $request->paginate == 'true')
            $data = $request->user()->bisnis
                    ->akunBank()
                    ->with('bank', 'outlet')
                    ->where(function($q) use ($request){
                        if($request->has('outlet_id') && $request->outlet_id != 0)
                            $q->whereHas('outlet', function($q) use ($request){
                                $q->where('outlet_id',$request->outlet_id);
                            });

                        $q->where('nomor_rekening', 'like', '%'.$request->pencarian.'%');
                        $q->where('pemilik_akun', 'like', '%'.$request->pencarian.'%');
                        $q->where('keterangan', 'like', '%'.$request->pencarian.'%');
                    })
                    ->paginate();
        else
            $data = $request->user()->bisnis
                    ->akunBank()
                    ->where(function($q) use ($request){
                        if($request->has('outlet_id') && $request->outlet_id != 0)
                            $q->whereHas('outlet', function($q) use ($request){
                                $q->where('outlet_id',$request->outlet_id);
                            });

                        $q->where('nomor_rekening', 'like', '%'.$request->pencarian.'%');
                        $q->where('pemilik_akun', 'like', '%'.$request->pencarian.'%');
                        $q->where('keterangan', 'like', '%'.$request->pencarian.'%');
                    })
                    ->get();
        return AkunBankIndexResource::collection($data);
    }

    public function store(Request $request)
    {
        $this->authorize('create', AkunBank::class);
        
        $data = $request->validate($this->validation());
        DB::beginTransaction();
        try {   
            $akunBank = $request->user()->bisnis
                            ->akunBank()
                            ->create($data['data']);

            foreach ($data['outlet'] as $d) {
                $akunBank
                    ->outlet()
                    ->create($d);
            }
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function show(AkunBank $akunBank)
    {
        $this->authorize('view', $akunBank);

        return $akunBank->load('outlet');
    }

    public function update(Request $request, AkunBank $akunBank)
    {
        $this->authorize('update', $akunBank);

        $data = $request->validate($this->validation());

        DB::beginTransaction();
        try {   
            $akunBank->update($data['data']);

            $idOutlet = [];
            foreach ($data['outlet'] as $d) {
                $opsi = $akunBank
                    ->outlet()
                    ->updateOrCreate($d, $d);
                array_push($idOutlet, $opsi['id_outlet_akun_bank']); 
            }
            $akunBank
                ->outlet()
                ->whereNotIn('id_outlet_akun_bank', $idOutlet)
                ->delete();

            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function validation(){
        return [
            'data' => 'required',
            'outlet' => 'required',
        ];
    }
}
