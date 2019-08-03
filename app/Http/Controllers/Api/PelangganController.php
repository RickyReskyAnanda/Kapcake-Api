<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view', Pelanggan::class);

        if(isset($request->paginate) && $request->paginate == 'true')
            return $request->user()->bisnis
                    ->pelanggan()
                    // ->where(function($q){
                    //     if(isset(request()->pencarian))
                    //         $q->where('nama_kategori_menu', request()->pencarian);
                    // })
                    ->paginate();
        else
            return $request->user()->bisnis
                    ->pelanggan()
                    // ->where(function($q){
                    //     if(isset(request()->pencarian))
                    //         $q->where('nama_kategori_menu', request()->pencarian);
                    // })
                    ->get();
    }

    public function store(Request $request)
    {
        $this->authorize('create', Pelanggan::class);
        
        $data = $request->validate($this->validation());
        DB::beginTransaction();
        try {   
            $pelanggan = $request->user()->bisnis
                            ->pelanggan()
                            ->create([
                                'nama_pelanggan' => $data['nama_pelanggan'],
                                'jumlah' => $data['jumlah']
                            ]);
            foreach ($data['outlet'] as $d) {
                $pelanggan
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

    public function show(pelanggan $pelanggan)
    {
        $this->authorize('show', $pelanggan);

        return $pelanggan->load('outlet');
    }

    public function update(Request $request, pelanggan $pelanggan)
    {
        $this->authorize('update', $pelanggan);
        
        $data = $request->validate($this->validation());

        DB::beginTransaction();
        try {   
            $pelanggan
                ->update([
                    'nama_pelanggan' => $data['nama_pelanggan'],
                    'jumlah' => $data['jumlah']
                ]);

            $idOutlet = [];
            foreach ($data['outlet'] as $d) {
                $opsi = $pelanggan
                    ->outlet()
                    ->updateOrCreate($d, $d);
                array_push($idOutlet, $opsi['id_outlet_pelanggan']); 
            }
            $pelanggan
                ->outlet()
                ->whereNotIn('id_outlet_pelanggan', $idOutlet)
                ->delete();

            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function destroy(pelanggan $pelanggan)
    {
        $this->authorize('delete', $pelanggan);

        DB::beginTransaction();
        try {
            $pelanggan->delete();
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function validation(){
        return [
            'nama_pelanggan' => 'required|max:50',
            'jumlah' => 'required|numeric|max:100',
            'outlet' => 'required',
        ];
    }
}
