<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KategoriMejaShow extends JsonResource
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
                    'id' => $this->id_kategori_meja,
                    'nama' => $this->nama_kategori_meja,
                    'is_aktif' => $this->is_aktif ,
            ];
    }
}
