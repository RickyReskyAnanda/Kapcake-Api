<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AssignKategoriBahanDapur extends JsonResource
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
            'id' => $this->id_bahan_dapur,
            'kategori_id' => $this->kategori_bahan_dapur_id,
            'nama' => $this->nama_bahan_dapur,
        ];
    }
}
