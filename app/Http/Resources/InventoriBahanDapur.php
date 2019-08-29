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
                    'nama' => $this->nama_bahan_dapur ?? '',
                    'kategori' => $this->nama_kategori_bahan_dapur ?? '',
                    'variasi' => '',
                    'stok_awal' => $this->stok_awal,
                    'pemakaian' => $this->pemakaian,
                    'transfer' => $this->transfer,
                    'penyesuaian_stok' => $this->penyesuaian_stok,
                    'stok_akhir' => $this->stok_akhir
            ];
    }
}
