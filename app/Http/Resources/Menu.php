<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Menu extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
                'id' => (int)$this->id_menu,
                'kategori' => new MenuKategori($this->kategori),
                'nama' => $this->nama_menu,
                'is_tipe_penjualan' => (int)$this->is_tipe_penjualan,
                'is_inventarisasi' => (int)$this->is_inventarisasi,
                'variasi' => MenuVariasi::collection($this->variasi),
                'tipe_penjualan' => MenuTipePenjualan::collection($this->tipePenjualan),
                'item_tambahan' => MenuItemTambahan::collection($this->itemTambahan),
                'keterangan' => $this->keterangan,
        ];
    }
}
