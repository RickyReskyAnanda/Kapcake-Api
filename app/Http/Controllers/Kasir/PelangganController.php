<?php

namespace App\Http\Controllers\Kasir;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Outlet;

class PelangganController extends Controller
{
    public function index(Request $request){
        return [];
    }
    public function store(Request $request)
    {
        $data = $request->validate($this->validation());
        // DB::beginTransaction();
        // try {   
            foreach ($data['pelanggan'] as $key => $value) {
                $request->user()->bisnis->pelanggan()->updateOrCreate(
                        [   
                            'id_pelanggan' =>$value['id_pelanggan'],
                        ],
                        [
                            'email' => $value['email'],
                            'nama_pelanggan' => $value['nama_pelanggan'],
                            'no_hp' => $value['no_hp'],
                        ]);
            }
        //     DB::commit();
            $pelanggan = $request->user()->bisnis
                                ->pelanggan()
                                ->latest()
                                ->get();
            return response($pelanggan, 200);
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return response('error',500);
        // }
    }

    public function validation(){
        return [
            'pelanggan' => 'required',
        ];
    }
}
