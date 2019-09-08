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
                    'supplier_id' => $this->supplier_id,
                    'user' => [
                                    'nama' => $this->user->name ?? '',
                                    'email' => $this->user->email ?? '',
                                    'telpon' => $this->user->telpon ?? '',
                                    'alamat' => $this->user->alamat ?? ''
                    ],
                    'no_order' => $this->no_order,
                    'tanggal' => date('Y-m-d, h:i', strtotime($this->created_at)),
                    'nama_outlet' => $this->outlet->nama_outlet ?? '',
                    'nama_supplier' => $this->supplier->nama ?? '',
                    'catatan' => $this->catatan,
                    'status' => ucfirst($this->status),
                    'tipe_item' => ucfirst($this->tipe_item),
                    'total' => $this->total,
                    'entry' => PesananPembelianEntry::collection($this->entry),
            ];
    }
} 