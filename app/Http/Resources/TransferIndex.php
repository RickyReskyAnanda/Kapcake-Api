<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransferIndex extends JsonResource
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
                'tanggal' => dateFormat($this->created_at),
                'outlet_asal' => $this->outletAsal->nama_outlet ?? '',
                'outlet_tujuan' => $this->outletTujuan->nama_outlet ?? '',
                'catatan' => ucfirst($this->catatan),
                'total_item' => $this->entry->count(). ' Item',
                'jumlah_transfer' => $this->jumlah_transfer,
        ];
    }
}
