<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PenyesuaianStokEntry extends JsonResource
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
            $namaItem = ($this->item->menu->nama_menu .($this->item->nama_variasi_menu != '' ?  ' - ' : '').$this->item->nama_variasi_menu) ?? 'aa';
        }elseif($this->tipe_item == 'bahan_dapur'){
            $namaItem = $this->item->nama_bahan_dapur ?? 'bb';
        }elseif($this->tipe_item == 'barang'){
            $namaItem = $this->item->nama_barang ?? 'cc';
        }

        return [
            'item_id' => $this->item_id,
            'nama_item' => $namaItem,
            'stok_sistem' =>$this->stok_sistem,
            'stok_aktual' =>$this->stok_aktual,
            'selisih_stok' => $this->selisih_stok,
        ];
    }
}
