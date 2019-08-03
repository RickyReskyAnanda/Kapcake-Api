<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AssignKategoriBarang extends JsonResource
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
            'id' => $this->id_barang,
            'kategori_id' => $this->kategori_barang_id,
            'nama' => $this->nama_barang,
        ];
    }
}
