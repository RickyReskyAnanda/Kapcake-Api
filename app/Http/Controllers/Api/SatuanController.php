<?php

namespace App\Http\Controllers\Api;

use DB;
use App\Satuan;
use App\Http\Resources\Satuan as SatuanResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SatuanController extends Controller
{
    public function index(Request $request)
    {
        if(isset($request->paginate) && $request->paginate == 'true')
            $data = $request->user()->bisnis
                    ->satuan()
                    ->paginate();
        else
            $data = $request->user()->bisnis
                    ->satuan()
                    ->get();

        return SatuanResource::collection($data);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->user()->bisnis
                    ->satuan()
                    ->create($request->validate($this->validation()));
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function show(Satuan $satuan)
    {
        return $satuan;
    }

    public function update(Request $request, Satuan $satuan)
    {
        DB::beginTransaction();
        try {
            $satuan->update($request->validate($this->validation()));
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function destroy(Satuan $satuan)
    {
        DB::beginTransaction();
        try {
            $satuan->delete();
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    private function validation(){
        return [
            'nama_kategori_menu' => 'required|string'
        ];
    }
}
