<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransferEntry extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $namaItem = '';
        if($this->tipe_item == 'menu'){
            $namaItem = $this->item->nama_variasi_menu ?? '';
        }elseif($this->tipe_item == 'bahan_dapur'){
            $namaItem = $this->item->nama_bahan_dapur ?? '';
        }elseif($this->tipe_item == 'barang'){
            $namaItem = $this->item->nama_barang ?? '';
        }

        return [
            'nama_item' => $namaItem,
            'stok' =>$this->stok,
            'jumlah_transfer' =>$this->jumlah_transfer,
            'sisa_stok' =>$this->sisa_stok,
        ];
    }
}
