<?php

namespace App\Http\Controllers\Api;

use DB;
use App\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view', Supplier::class);
        if(isset($request->paginate) && $request->paginate == 'true')
            return $request->user()->bisnis
                    ->supplier()
                    ->where(function($q){
                        if(isset(request()->pencarian)){
                            $q->where('nama', 'like', '%'.request()->pencarian.'%');
                            $q->orWhere('alamat', 'like', '%'.request()->pencarian.'%');
                            $q->orWhere('nomor_telpon', 'like', '%'.request()->pencarian.'%');
                            $q->orWhere('email', 'like', '%'.request()->pencarian.'%');
                        }
                    })
                    ->paginate();
        else
            return $request->user()->bisnis
                    ->supplier()
                    // ->where(function($q){
                    //     if(isset(request()->pencarian))
                    //         $q->where('nama_kategori_menu', request()->pencarian);
                    // })
                    ->get();
    }

    public function store(Request $request)
    {
        $this->authorize('create', Supplier::class);
        
        $data = $request->validate($this->validation());
        DB::beginTransaction();
        try {
            $request->user()->bisnis
                ->supplier()
                ->create($data);
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function show(Supplier $supplier)
    {
        $this->authorize('show', $supplier);

        return $supplier;
    }

    public function update(Request $request, Supplier $supplier)
    {
        $this->authorize('update', $supplier);
     
        $data = $request->validate($this->validation());

        DB::beginTransaction();
        try {   
            $supplier->update($data);

            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function destroy(Supplier $supplier)
    {
        $this->authorize('delete', $supplier);

        DB::beginTransaction();
        try {
            $supplier->delete();
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function validation(){
        return [
            'nama' => 'required|string|max:100',
            'alamat' => 'required|string|max:500',
            'nomor_telpon' => 'required|string|max:20',
            'email' => 'nullable|email|max:100',
            'kode_pos' => 'nullable|string|min:5|max:6',
        ];
    }
}
