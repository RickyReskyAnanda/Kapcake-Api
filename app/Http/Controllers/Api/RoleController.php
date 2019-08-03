<?php

namespace App\Http\Controllers\Api;

use DB;
use App\Role;
use App\Aplikasi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\RoleIndex as RoleIndexResource;

class RoleController extends Controller
{
	public function index(Request $request)
	{
        $this->authorize('view', Role::class);

		if(isset($request->paginate) && $request->paginate == 'true')
			$data = $request->user()->bisnis
				->role()
                    // ->where(function($q){
                    //     if(isset(request()->pencarian))
                    //         $q->where('nama_kategori_menu', request()->pencarian);
                    // })
				->paginate();
		else
			$data = $request->user()->bisnis
				->role()
                    // ->where(function($q){
                    //     if(isset(request()->pencarian))
                    //         $q->where('nama_kategori_menu', request()->pencarian);
                    // })
				->get();

        return RoleIndexResource::collection($data);
	}

    public function store(Request $request)
    {
        $this->authorize('create', Role::class);

    	$data = $request->validate($this->validation());
        DB::beginTransaction();
        try {
        	$role = $request->user()->bisnis
        	->role()
        	->create($data['data']);

        	foreach ($data['entry'] as $d) {
        		$aplikasi = $role
    		    		->aplikasi()
    		    		->create([
    		    			'aplikasi_id' => $d['id_aplikasi'],
    		    			'is_aktif' => $d['is_aktif'],
    		    		]);
        		foreach($d['otorisasi'] as $o){
        			$otorisasi = $aplikasi
    				    			->otorisasi()
    				    			->create([
    				    				'role_id' => $aplikasi['role_id'],
    				    				'otorisasi_id' => $o['id_otorisasi'],
    				    				'is_aktif' => $o['is_aktif']
    				    			]);

        			foreach ($o['child'] as $c) {
        				$otorisasi
    	    				->child()
    	    				->create([
    		    				'role_id' => $otorisasi['role_id'],
    		    				'role_aplikasi_id' => $otorisasi['role_aplikasi_id'],
    		    				'otorisasi_id' => $c['id_otorisasi'],
    		    				'is_aktif' => $c['is_aktif']
    	    				]);
        			}
        		}
        	}
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        $this->authorize('show', $role);

    	return $role->load('aplikasi.otorisasi.child');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $this->authorize('update', $role);

    	$data = $request->validate($this->validation());

        DB::beginTransaction();
        try {
    	$role->update($data['data']);

    	$idAplikasi = [];
    	foreach ($data['entry'] as $d) {
    		$aplikasi = $role
		    		->aplikasi()
		    		->firstOrNew(
		    			['aplikasi_id' 	=> $d['id_aplikasi']] 
				  	);
		  		$aplikasi->is_aktif 	= $d['is_aktif']; 
			  	$aplikasi->aplikasi_id = $d['id_aplikasi'];
			  	$aplikasi->save();
		   	array_push($idAplikasi, $d['id_aplikasi']);

    		$idOtorisasi = [];
    		foreach($d['otorisasi'] as $o){
    			$otorisasi = $aplikasi
				    			->otorisasi()
				    			->firstOrNew(
				    				['otorisasi_id' 	=> $o['id_otorisasi']]
				    			);
				$otorisasi->role_id = $aplikasi['role_id'];
				$otorisasi->otorisasi_id = $o['id_otorisasi'];
				$otorisasi->is_aktif = $o['is_aktif'];
				$otorisasi->save();

			   	array_push($idOtorisasi, $o['id_otorisasi']);

			   	$idChild = [];
    			foreach ($o['child'] as $c) {
    				$child = $otorisasi
	    				->child()
	    				->firstOrNew(
			    			['otorisasi_id' => $c['id_otorisasi']]
			    		);

	    			$child->role_id = $otorisasi['role_id'];
	    			$child->role_aplikasi_id = $otorisasi['role_aplikasi_id'];
	    			$child->otorisasi_id = $c['id_otorisasi'];
	    			$child->is_aktif = $c['is_aktif'];

	    			$child->save();
				   	array_push($idChild, $c['id_otorisasi']);
    			}
                $otorisasi
                ->child()
                ->whereNotIn('otorisasi_id', $idChild)
                ->delete();
    		}

			$aplikasi
			->otorisasi()
            ->whereNotIn('otorisasi_id', $idOtorisasi)
            ->delete();
    	}

    	$role
    		->aplikasi()
            ->whereNotIn('aplikasi_id', $idAplikasi)
            ->delete();
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function destroy(Role $role)
    {
        $this->authorize('delete', $role);

    	DB::beginTransaction();
    	try {
    		$role->delete();
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
    		'entry' => 'required',
    	];
    }
}
