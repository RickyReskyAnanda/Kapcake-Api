<?php

namespace App\Http\Controllers\Api;

use DB;
use App\Outlet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OutletController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view', Outlet::class);

        if(isset($request->paginate) && $request->paginate == 'true')
            return $request->user()->bisnis
                    ->outlet()
                    // ->where(function($q){
                    //     if(isset(request()->pencarian))
                    //         $q->where('nama_kategori_menu', request()->pencarian);
                    // })
                    ->paginate();
        else
            return $request->user()->bisnis
                    ->outlet()
                    // ->where(function($q){
                    //     if(isset(request()->pencarian))
                    //         $q->where('nama_kategori_menu', request()->pencarian);
                    // })
                    ->get();
    }

    public function store(Request $request)
    {
        $this->authorize('create', Outlet::class);
        
        $data = $request->validate($this->validation());
        DB::beginTransaction();
        try {   
            $outlet = $request->user()->bisnis
                            ->outlet()
                            ->create($data);
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function show(Outlet $outlet)
    {
        $this->authorize('show', $outlet);

        return $outlet;
    }

    public function update(Request $request, Outlet $outlet)
    {
        $this->authorize('update', $outlet);

        $data = $request->validate($this->validation());

        DB::beginTransaction();
        try {   
            $outlet->update($data);
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function destroy(Outlet $outlet)
    {
        $this->authorize('delete', $outlet);
        
        DB::beginTransaction();
        try {
            $outlet->delete();
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function validation(){
        return [
            'nama_outlet' => 'required|max:255',
            'telpon' => 'required',
            'email' => 'required',
            'kota' => 'nullable',
            'provinsi' => 'nullable',
            'kode_pos' => 'nullable',
            'alamat' => 'nullable',
            'catatan' => 'nullable',
        ];
    }
}
