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
                'tanggal' => date('Y-m-d, h:i', strtotime($this->created_at)),
                'outlet_asal' => $this->outletAsal->nama_outlet,
                'outlet_tujuan' => $this->outletTujuan->nama_outlet,
                'catatan' => $this->catatan,
                'tipe_item' => ucfirst($this->tipe_item),
                'jumlah_item' => $this->jumlah_item,
                'jumlah_transfer' => $this->jumlah_transfer,
                'entry' => TransferEntry::collection($this->entry),
            ];
    }
}
