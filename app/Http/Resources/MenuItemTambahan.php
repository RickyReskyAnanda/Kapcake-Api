<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuItemTambahan extends JsonResource
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
                    'id' => $this->itemTambahan->id_item_tambahan,
                    'nama' => $this->itemTambahan->nama_item_tambahan,
            ];
    }
}
