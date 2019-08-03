<?php

namespace App\Http\Controllers\Api;
use DB;
use App\KategoriMenu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\KategoriMenu as KategoriMenuResource;

class KategoriMenuController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view', KategoriMenu::class);

        if(isset($request->paginate) && $request->paginate == 'true')
            $data =  $request->user()->bisnis
                    ->kategoriMenu()
                    ->where(function($q){
                        $q->where('is_paten', 0);
                        $q->where('outlet_id', auth()->user()->outlet_terpilih_id);
                        $q->where('nama_kategori_menu','like', '%'.request()->pencarian.'%');
                    })->paginate();
        else
            $data =  $request->user()->bisnis
                    ->kategoriMenu()
                    // ->where(function($q){
                    //     if(isset(request()->pencarian))
                    //         $q->where('nama_kategori_menu', request()->pencarian);
                    // })
                    ->get();

        return KategoriMenuResource::collection($data);
    }

    public function store(Request $request)
    {
        $this->authorize('create', KategoriMenu::class);
        
        $data = $request->validate($this->validation());
        DB::beginTransaction();
        try {   
            foreach($request->outlet as $o)
                $kategoriMenu = $request->user()->bisnis
                            ->kategoriMenu()
                            ->create([
                                'outlet_id' => $o['outlet_id'],
                                'nama_kategori_menu' => $data['nama_kategori_menu']
                            ]);
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function show(KategoriMenu $kategoriMenu)
    {
        $this->authorize('show', $kategoriMenu);
        
        return $kategoriMenu;
    }

    public function update(Request $request, KategoriMenu $kategoriMenu)
    {
        $this->authorize('update', $kategoriMenu);

        $data = $request->validate($this->validation());

        DB::beginTransaction();
        try {   
            $kategoriMenu
                ->update([
                    'nama_kategori_menu' => $data['nama_kategori_menu']
                ]);

            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function destroy(KategoriMenu $kategoriMenu)
    {
        $this->authorize('delete', $kategoriMenu);

        DB::beginTransaction();
        try {
            $kategoriMenu->delete();
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function validation(){
        return [
            'nama_kategori_menu' => 'required|max:255',
            'outlet' => 'nullable',
        ];
    }
}
