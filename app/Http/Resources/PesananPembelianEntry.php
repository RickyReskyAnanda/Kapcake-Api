<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PesananPembelianEntry extends JsonResource
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
            $namaItem = $this->item->nama_variasi_menu ?? 'aa';
        }elseif($this->tipe_item == 'bahan_dapur'){
            $namaItem = $this->item->nama_bahan_dapur ?? 'bb';
        }elseif($this->tipe_item == 'barang'){
            $namaItem = $this->item->nama_barang ?? 'cc';
        }

        return [
            'id' => $this->id_pesanan_pembelian_entry,
            'item_id' => $this->item_id,
            'tipe_item' => $this->tipe_item,
            'nama_item' => $namaItem,
            'stok' =>$this->stok,
            'stok_dipesan' =>$this->stok_dipesan,
            'harga_satuan' => $this->harga_satuan,
            'harga_total' => $this->harga_total,
        ];
    }
}
