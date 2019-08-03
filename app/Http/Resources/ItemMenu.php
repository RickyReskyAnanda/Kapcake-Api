<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemMenu extends JsonResource
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
                'id' => (int)$this->id_variasi_menu,
                'nama' => $this->variasi->count() >  1 ? $this->nama_menu. ' - ' .$this->nama_variasi_menu : $this->nama_menu,
                'stok' => $this->stok,
        ];
    }
}
