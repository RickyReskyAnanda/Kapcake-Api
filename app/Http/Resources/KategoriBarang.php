<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KategoriBarang extends JsonResource
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
                'id' => $this->id_kategori_barang,
                'nama' => $this->nama_kategori_barang,
                'outlet' => $this->outlet->nama_outlet ?? '',
                'total_barang' => $this->barang->count(),
        ];
    }
}
