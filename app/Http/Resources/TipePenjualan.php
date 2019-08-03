<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TipePenjualan extends JsonResource
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
                    'id' => $this->id_tipe_penjualan,
                    'nama' => $this->nama_tipe_penjualan,
                    'is_aktif' => $this->is_aktif == 1 ? 'Aktif':'Nonaktif',
                    'total_biaya_tambahan' => $this->biayaTambahan->count(),
                    'biaya_tambahan' => TipePenjualanBiayaTambahan::collection($this->biayaTambahan),
            ];
    }
}
