<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InventoriMenu extends JsonResource
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
                    'id' => $this->id_inventori_menu,
                    'nama' => $this->menu->nama_menu ?? '',
                    'kategori' => $this->kategoriMenu->nama_kategori_menu ?? '',
                    'variasi' => $this->variasiMenu->nama_variasi_menu ?? '',
                    'stok_awal' => $this->stok_awal,
                    'penjualan' => $this->penjualan,
                    'transfer' => $this->transfer,
                    'penyesuaian_stok' => $this->penyesuaian_stok,
                    'stok_akhir' => $this->stok_akhir
            ];
    }
}
