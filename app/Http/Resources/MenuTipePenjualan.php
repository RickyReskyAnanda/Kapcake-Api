<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuTipePenjualan extends JsonResource
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
            'id' => $this->tipePenjualan->id_tipe_penjualan,
            'nama' => $this->tipePenjualan->nama_tipe_penjualan,
        ];
    }
}
