<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Perangkat extends JsonResource
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
                'id' => $this->id_perangkat,
                'nama' => $this->nama_perangkat,
                'nama_outlet' => $this->outlet->nama_outlet ?? 'Outlet',
                'masa_aktif' => date('Y-m-d', strtotime($this->updated_at)),
                'status' => $this->is_aktif,
            ];
    }
}
