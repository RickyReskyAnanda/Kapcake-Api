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
            $data =  $request->user()
                    ->bisnis
                    ->kategoriMenu()
                    ->where(function($q) use ($request){
                        $q->where('is_paten', 0);
                        $q->where('outlet_id', $request->has('outlet_id') ? $request->outlet_id : '0');
                        if($request->has('pencarian'))
                            $q->where('nama_kategori_menu','like', '%'.$request->pencarian.'%');
                    })
                    ->orderBy('nama_kategori_menu','asc')
                    ->paginate(10);
        else
            $data =  $request->user()
                    ->bisnis
                    ->kategoriMenu()
                    ->where('outlet_id', $request->has('outlet_id') ? $request->outlet_id : 0)    
                    ->orderBy('nama_kategori_menu','asc')
                    ->get();

        return KategoriMenuResource::collection($data);
    }

    public function store(Request $request)
    {
        $this->authorize('create', KategoriMenu::class);
        
        $data = $request->validate($this->validation());
        DB::beginTransaction();
        try {   
                $request->user()->bisnis
                            ->kategoriMenu()
                            ->create($data);
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
            $kategoriMenu ->update($data);
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
            'outlet_id' => 'nullable',
        ];
    }
}
