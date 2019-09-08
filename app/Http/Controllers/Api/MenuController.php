<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Menu;
use App\Http\Resources\MenuTable as MenuTableResource;
use App\Http\Resources\Menu as MenuResource;
use App\Image\BlobImageConvertion;

class MenuController extends Controller
{

    public function index(Request $request)
    {
        $this->authorize('view', Menu::class);

        if(isset($request->paginate) && $request->paginate == 'true')
            $data = $request->user()->bisnis
        ->menu()
        ->with('kategori')
        ->where('outlet_id', $request->outlet_id)
        ->where('kategori_menu_id', $request->kategori_menu_id > 0 ? '=' :'!=' , $request->kategori_menu_id )
        ->where(function($q){
            $q->whereHas('kategori', function($q){
                $q->where('nama_kategori_menu', 'like', '%'.request()->pencarian.'%');
            });
            $q->orWhere('nama_menu', 'like', '%'.request()->pencarian.'%');
        })
        ->latest()
        ->paginate();
        else
            $data = $request->user()->bisnis
                    ->menu()
                    ->with('kategori','gambar')
                    // ->where('outlet_id', $request->outlet_id)
                    // ->where('kategori_menu_id', $request->kategori_menu_id > 0 ? '=' :'!=' , $request->kategori_menu_id )
                    ->where(function($q){
                        $q->whereHas('kategori', function($q){
                            $q->where('nama_kategori_menu', 'like', '%'.request()->pencarian.'%');
                        });
                        $q->orWhere('nama_menu', 'like', '%'.request()->pencarian.'%');
                    })
                    ->get();
        return MenuTableResource::collection($data);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Menu::class);

        $data = $request->validate($this->validation());
        DB::beginTransaction();
        try {
                $gambar = [];
                if($data['gambar'] ){
                    $gambar = BlobImageConvertion::image($data['gambar'], 'menu');
                }

                $menu = Menu::create( $data['data']);
                

                foreach ($data['variasi'] as  $variasi) {
                    $variasiMenu = $menu->variasi()->create([
                        'outlet_id' => $menu->outlet_id,
                        'kategori_menu_id' => $variasi['kategori_menu_id'],
                        'nama_variasi_menu' => $variasi['nama_variasi_menu'],
                        'harga' => $variasi['harga'],
                        'sku' => $variasi['sku'],
                        'stok' => $variasi['stok'],
                        'stok_rendah' => $variasi['stok_rendah'],
                        'is_inventarisasi' => $variasi['is_inventarisasi'],
                    ]);
                    if(isset($variasi['tipe_penjualan']) && $data['data']['is_tipe_penjualan'] == 1) 
                        foreach($variasi['tipe_penjualan'] as $variasiTipePenjualan){
                            $variasiMenu->tipePenjualan()->create($variasiTipePenjualan);
                        }

                }

                if(isset($data['tipe_penjualan']))
                    foreach ($data['tipe_penjualan'] as $d) {
                        $menu->tipePenjualan()->create($d);
                    }

                if(isset($data['item_tambahan']))
                    foreach ($data['item_tambahan'] as $d) {
                        $menu->itemTambahan()->create($d);
                    }

                foreach ($gambar as $key => $d) {
                    $menu->gambar()->create($d);
                }

            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
        
    }

    public function show(Menu $menu)
    {
        $this->authorize('show', $menu);
        $menu->load('kategori','variasi','itemTambahan', 'tipePenjualan', 'thumbGambar');
        return new MenuResource($menu);
    }

    public function update(Request $request, Menu $menu)
    {
        $this->authorize('update', $menu);

        $data = $request->validate($this->validation());
        DB::beginTransaction();
        try {   
                $gambar = [];
                if($data['gambar'] ){
                    $gambar = BlobImageConvertion::image($data['gambar'], 'menu');
                }

                $menu->update($data['data']);

                $variasiMenuId = [];
                foreach ($data['variasi'] as  $variasi) {
                    $variasiMenu = $menu->variasi()->find($variasi['id']??0);
                    if($variasiMenu){
                        $variasiMenu->update([
                            'kategori_menu_id' => $menu['kategori_menu_id'],
                            'nama_variasi_menu' => $variasi['nama_variasi_menu'],
                            'harga' => $variasi['harga'],
                            'sku' => $variasi['sku'],
                            'stok' => $variasi['stok'],
                            'stok_rendah' => $variasi['stok_rendah'],
                            'is_inventarisasi' => $variasi['is_inventarisasi'] ?? 0,
                        ]);

                        $variasiTipePenjualanId = [];
                        if(isset($variasi['tipe_penjualan']) && $data['data']['is_tipe_penjualan'] == 1){
                            foreach($variasi['tipe_penjualan'] as $variasiTipePenjualan){
                                $tipePenjualanVariasi = $variasiMenu->tipePenjualan()->find($variasiTipePenjualan['id']);
                                if($tipePenjualanVariasi){
                                    $tipePenjualanVariasi->update([
                                        'harga' => $variasiTipePenjualan['harga']
                                    ]);
                                    array_push($variasiTipePenjualanId, $tipePenjualanVariasi->id_variasi_menu_tipe_penjualan);
                                }
                            }
                        }
                        $variasiMenu
                            ->tipePenjualan()
                            ->whereNotIn('id_variasi_menu_tipe_penjualan', $variasiTipePenjualanId)
                            ->delete();

                    }else{
                        $variasiMenu = $menu->variasi()->create([
                            'outlet_id' => $menu['outlet_id'],
                            'kategori_menu_id' => $variasi['kategori_menu_id'],
                            'nama_variasi_menu' => $variasi['nama_variasi_menu'],
                            'harga' => $variasi['harga'],
                            'sku' => $variasi['sku'],
                            'stok' => $variasi['stok'],
                            'stok_rendah' => $variasi['stok_rendah'],
                            'is_inventarisasi' => $variasi['is_inventarisasi'],
                        ]);
                        if(isset($variasi['tipe_penjualan']) && $data['data']['is_tipe_penjualan'] == 1) 
                            foreach($variasi['tipe_penjualan'] as $variasiTipePenjualan){
                                $variasiMenu->tipePenjualan()->create($variasiTipePenjualan);
                            }
                    }
                    array_push($variasiMenuId, $variasiMenu['id_variasi_menu']);
                }
                $menu
                        ->variasi()
                        ->whereNotIn('id_variasi_menu', $variasiMenuId)
                        ->delete();

                $tipePenjualanId = [];
                if(isset($data['tipe_penjualan']))
                    foreach ($data['tipe_penjualan'] as $d) {
                        $tipePenjualan = $menu->tipePenjualan()->updateOrCreate($d);
                        array_push($tipePenjualanId, $tipePenjualan->id_menu_tipe_penjualan); 
                    }
                $menu
                    ->tipePenjualan()
                    ->whereNotIn('id_menu_tipe_penjualan', $tipePenjualanId)
                    ->delete();

                $itemTambahanId = [];
                if(isset($data['item_tambahan']))
                    foreach ($data['item_tambahan'] as $d) {
                        $itemTambahan = $menu->itemTambahan()->updateOrCreate($d);
                        array_push($itemTambahanId, $itemTambahan->id_item_tambahan_menu); 
                    }
                $menu
                    ->itemTambahan()
                    ->whereNotIn('id_item_tambahan_menu', $itemTambahanId)
                    ->delete();
                if(count($gambar) > 0 ){
                    if(isset($menu->thumbGambar)) $menu->thumbGambar->delete();
                    if(isset($menu->oriGambar)) $menu->oriGambar->delete();
                    foreach ($gambar as $key => $d) {
                        $menu->gambar()->create($d);
                    }
                }

            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    public function destroy(Menu $menu)
    {
        $this->authorize('delete', $menu);
        
        DB::beginTransaction();
        try {   
            $menu->delete();
            DB::commit();
            return response('success',200);
        } catch (\Exception $e) {
            DB::rollback();
            return response('error',500);
        }
    }

    private function validation(){
        return [
            'data.nama_menu' => 'required', 
            'data.kategori_menu_id' => 'required', 
            'data.is_tipe_penjualan' => 'required|numeric',
            'data.is_inventarisasi' => 'required|numeric',
            'data.keterangan' => 'nullable', 
            'data.outlet_id' => 'nullable', 

            'gambar' => 'nullable', 
            'tipe_penjualan' => 'nullable', 
            'variasi' => 'nullable', 
            'item_tambahan' => 'nullable', 
        ];
    }

}
