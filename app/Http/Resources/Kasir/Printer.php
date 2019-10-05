<?php

namespace App\Http\Resources\Kasir;

use Illuminate\Http\Resources\Json\JsonResource;

class Printer extends JsonResource
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
            'id_printer' => $this->id_printer,
            'perangkat_id' => $this->perangkat_id,
            'outlet_id' => $this->outlet_id,
            'nama_printer' => $this->nama_printer,
            'mac' => $this->mac,
            'jenis_printer' => $this->jenis_printer,
            'lebar_kertas' => $this->lebar_kertas,
            'is_uploaded' => 1,
            'is_hapus' => 0,
            'is_connected' => 0,
        ];
    }
}
