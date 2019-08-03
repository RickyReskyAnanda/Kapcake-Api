<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PesananPembelianIndex extends JsonResource
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
                    'id' => $this->id_pesanan_pembelian,
                    'no_order' => zeroFill($this->id_pesanan_pembelian),
                    'tanggal' => dateFormat($this->created_at),
                    'supplier' => $this->supplier->nama ?? '',
                    'total' => 'Rp. '. number_format($this->total),
                    'status' => ucfirst($this->status),
            ];
    }
}
