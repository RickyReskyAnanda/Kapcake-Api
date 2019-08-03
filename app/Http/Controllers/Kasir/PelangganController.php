<?php

namespace App\Http\Controllers\Kasir;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Outlet;

class PelangganController extends Controller
{
    
    public function store(Request $request)
    {
        $data = $request->validate($this->validation());
        DB::beginTransaction();
        try {   
            $pelanggan = $request->user()->bisnis->pelanggan()->create($data);
            DB::commit();
            return response($pelanggan,200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function validation(){
        return [
            'nama_pelanggan' => 'required',
            'email' => 'nullable',
            'no_hp' => 'nullable',
        ];
    }
}
