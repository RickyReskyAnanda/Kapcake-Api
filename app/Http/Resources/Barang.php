<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Barang extends JsonResource
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
                'id' => $this->id_barang,
                'kategori' => $this->kategori->nama_kategori_barang ?? "Tidak Dikategorikan",
                'satuan' => $this->satuan->satuan ?? "-",
                'nama' => $this->nama_barang,
                'stok' => $this->stok,
                'stok_rendah' => $this->jumlah_stok_rendah,
                'keterangan' => $this->keterangan,
        ];
    }
}
