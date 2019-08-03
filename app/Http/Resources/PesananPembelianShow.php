<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PesananPembelianShow extends JsonResource
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
                    'outlet_id' => $this->outlet_id,
                    'outlet' => new Outlet($this->outlet),
                    'supplier_id' => $this->supplier_id,
                    'supplier' => new Supplier($this->supplier),
                    'catatan' => $this->catatan,
                    'status' => $this->status,
                    'tipe_item' => $this->tipe_item,
                    'total' => $this->total,
                    'entry' => PesananPembelianEntry::collection($this->entry),
            ];
    }
}
