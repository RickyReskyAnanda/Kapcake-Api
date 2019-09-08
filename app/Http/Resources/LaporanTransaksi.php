<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LaporanTransaksi extends JsonResource
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
                'kode_pemesanan' => $this->kode_pemesanan,
                'nama_user' => $this->nama_user,
                'subtotal' => $this->subtotal,
                'total' => $this->total,
                'total_item' => $this->total_item,
                'tanggal' => $this->tanggal,
                'waktu' => date('h:i',strtotime($this->waktu)),
            ];
    }
}
