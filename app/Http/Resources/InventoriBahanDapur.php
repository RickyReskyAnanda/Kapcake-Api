<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InventoriBahanDapur extends JsonResource
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
                    'id' => $this->id_inventori_bahan_dapur,
                    'nama' => $this->bahanDapur->nama_bahan_dapur ?? '',
                    'kategori' => $this->kategoriBahanDapur->nama_kategori_bahan_dapur ?? '',
                    'variasi' => '',
                    'stok_awal' => $this->stok_awal,
                    'penjualan' => $this->penjualan,
                    'transfer' => $this->transfer,
                    'penyesuaian_stok' => $this->penyesuaian_stok,
                    'stok_akhir' => $this->stok_akhir
            ];
    }
}
