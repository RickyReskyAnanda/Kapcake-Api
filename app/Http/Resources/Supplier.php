<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Supplier extends JsonResource
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
                    'id' => $this->id_supplier,
                    'nama' => $this->nama,
                    'alamat' => $this->alamat,
                    'nomor_telpon' => $this->nomor_telpon,
                    'email' => $this->email,
                    'kode_pos' => $this->kode_pos,
            ];
    }
}
