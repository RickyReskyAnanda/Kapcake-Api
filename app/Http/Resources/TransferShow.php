<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransferShow extends JsonResource
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
                'id' => $this->id_transfer,
                'outlet_asal' => $this->outletAsal->nama_outlet,
                'outlet_tujuan' => $this->outletTujuan->nama_outlet,
                'catatan' => $this->catatan,
                'tipe_item' => $this->tipe_item,
                'total' => $this->total,
                'entry' => TransferEntry::collection($this->entry),
            ];
    }
}
