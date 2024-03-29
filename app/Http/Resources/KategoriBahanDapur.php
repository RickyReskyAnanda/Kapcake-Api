<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KategoriBahanDapur extends JsonResource
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
                    'id' => $this->id_kategori_bahan_dapur,
                    'nama' => $this->nama_kategori_bahan_dapur,
                    'outlet' => $this->outlet->nama_outlet ?? '',
                    'total_bahan_dapur' => $this->bahanDapur->count(). ' Barang Ditandai',
            ];
    }
}
