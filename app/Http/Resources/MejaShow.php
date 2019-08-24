<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MejaShow extends JsonResource
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
                    'kategori_meja_id' => $this->kategori_meja_id ,
                    'pax' => $this->pax ,
                    'bentuk' => $this->bentuk ,
            ];
    }
}
