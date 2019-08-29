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
                    'nama' => $this->nama_menu,
                    'kategori' => $this->nama_kategori_menu,
                    'variasi' => $this->nama_variasi_menu,
                    'stok_awal' => $this->stok_awal,
                    'penjualan' => $this->penjualan,
                    'transfer' => $this->transfer,
                    'penyesuaian_stok' => $this->penyesuaian_stok,
                    'stok_akhir' => $this->stok_akhir,
            ];
    }
}
