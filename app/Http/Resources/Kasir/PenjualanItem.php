<?php

namespace App\Http\Resources\Kasir;

use Illuminate\Http\Resources\Json\JsonResource;

class PenjualanItem extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
            $urlGambar = null;
            if(isset($this->menu) == true)
                if(isset($this->menu->gambar) == true)
                    if(isset($this->menu->gambar[1]->link) == true)
                        $urlGambar = $this->menu->gambar[1]->link;

            return [
                'id_penjualan_item' => $this->id_penjualan_item,
                'catatan' => $this->catatan,
                'harga' => $this->harga,
                'jumlah' => $this->jumlah,
                'jumlah_diskon' => $this->jumlah_diskon,
                'jumlah_refund' => $this->jumlah_refund,
                'kategori_menu_id' => $this->kategori_menu_id,
                'nama_diskon' => $this->nama_diskon,
                'nama_kategori_menu' => $this->nama_kategori_menu,
                'nama_menu' => $this->nama_menu,
                'nama_tipe_penjualan' => $this->nama_tipe_penjualan,
                'nama_variasi_menu' => $this->nama_variasi_menu,
                'subtotal' => $this->subtotal,
                'tipe_penjualan_id' => $this->tipe_penjualan_id,
                'total' => $this->total,
                'total_diskon' => $this->total_diskon,
                'total_refund' => $this->total_refund,
                'gambar' => $urlGambar
            ];
    }
}
