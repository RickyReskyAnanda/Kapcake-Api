<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class KategoriMenuToMenuController extends Controller
{
    public function index(Request $request){
        return $request->user()->bisnis
        			->menu()
        			->where('nama_menu','like','%'.$request->nama_menu.'%')
        			->get();
    }

    public function store(Request $request){
    	$data = $request->validate([
    		'data' => 'required',
            'id_kategori_menu' => 'required|integer'
    	]);
        DB::beginTransaction();
        try {
            foreach($data['data'] as $d)
                $request->user()->bisnis
                    ->menu()
                    ->where('id_menu', $d['menu_id'])
                    ->update(['kategori_menu_id' => $d['kategori_menu_id'] ]);
                    
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }
}
