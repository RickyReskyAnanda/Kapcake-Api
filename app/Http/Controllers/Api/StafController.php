<?php

namespace App\Http\Controllers\Api;

use DB;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\StafIndex as StafIndexResource;

class StafController extends Controller
{
    public function index(Request $request)
    {
        // $this->authorize('view', User::class);

        if(isset($request->paginate) && $request->paginate == 'true')
            $data = $request->user()->bisnis
                    ->staf()
                    // ->where(function($q){
                    //     if(isset(request()->pencarian))
                    //         $q->where('nama_kategori_menu', request()->pencarian);
                    // })
                    ->paginate();
        else
            $data = $request->user()->bisnis
                    ->staf()
                    // ->where(function($q){
                    //     if(isset(request()->pencarian))
                    //         $q->where('nama_kategori_menu', request()->pencarian);
                    // })
                    ->get();
        return StafIndexResource::collection($data);
    }

    public function store(Request $request)
    {
        // $this->authorize('create', User::class);
        
        $data = $request->validate($this->validation());
        // DB::beginTransaction();
        // try {   
            $staf = $request->user()->bisnis
                            ->staf()
                            ->create($data['data']);
            foreach ($data['outlet'] as $d) {
                $staf
                    ->outlet()
                    ->create($d);
            }
        //     DB::commit();
        //     return response('success',200);
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return response('error',500);
        // }
    }

    public function show(User $staf)
    {
        // $this->authorize('show', $staf);

        return $staf->load('outlet');
    }

    public function update(Request $request, User $staf)
    {
        // $this->authorize('update', $staf);

        $data = $request->validate($this->validation());

        DB::beginTransaction();
        try {   
            $staf->update($data['data']);

            $idOutlet = [];
            foreach ($data['outlet'] as $d) {
                $opsi = $staf
                    ->outlet()
                    ->updateOrCreate($d, $d);
                array_push($idOutlet, $opsi['id_outlet_user']); 
            }
            $staf
                ->outlet()
                ->whereNotIn('id_outlet_user', $idOutlet)
                ->delete();

            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function destroy(User $staf)
    {
        // $this->authorize('delete', $staf);
        
        DB::beginTransaction();
        try {
            $staf->delete();
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
