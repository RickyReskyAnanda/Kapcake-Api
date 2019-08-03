<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InventoriBarang extends JsonResource
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
                    'id' => $this->id_inventori_barang,
                    'nama' => $this->barang->nama_barang ?? '',
                    'kategori' => $this->kategoriBarang->nama_kategori_barang ?? '',
                    'stok_awal' => $this->stok_awal,
                    'penjualan' => $this->penjualan,
                    'transfer' => $this->transfer,
                    'penyesuaian_stok' => $this->penyesuaian_stok,
                    'stok_akhir' => $this->stok_akhir
            ];
    }
}
