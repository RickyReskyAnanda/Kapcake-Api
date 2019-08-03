<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KategoriMenu extends JsonResource
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
                    'id' => $this->id_kategori_menu,
                    'nama' => $this->nama_kategori_menu,
                    'outlet' => $this->outlet->nama_outlet ?? '',
                    'total_menu' => $this->menu->count(),
            ];
    }
}
