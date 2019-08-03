<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemBarang extends JsonResource
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
                'id' => (int)$this->id_bahan_dapur,
                'nama' => $this->nama_bahan_dapur,
                'stok' => $this->stok,
        ];
    }
}
