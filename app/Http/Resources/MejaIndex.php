<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MejaIndex extends JsonResource
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
                    'id' => $this->id_meja,
                    'nama' => $this->nama_meja,
                    'pax' => $this->pax,
                    'kategori' => new KategoriMejaShow($this->kategoriMeja),
            ];
    }
}
