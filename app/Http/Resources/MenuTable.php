<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuTable extends JsonResource
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
                'id' => $this->id_menu,
                'kategori' => $this->kategori->nama_kategori_menu,
                // 'gambar' => $this->gambar ? $this->gambar->path : '',
                'nama' => $this->nama_menu,
                'tipe_penjualan' => $this->tipePenjualanData(),
                'total_variasi' => $this->totalVariasi() > 1 ?$this->totalVariasi(). ' Varian' : '-',
                'harga' => $this->hargaData(),
                'stok' => $this->totalStok(),
                'stok_rendah' => $this->totalStokRendah(),
        ];
    }

    private function tipePenjualanData(){
        $jumlah = $this->tipePenjualan->count();
        if($jumlah == 0 )
            return '-';
        elseif($jumlah == 1 )
            return $this->tipePenjualan[0]->tipePenjualan->nama_tipe_penjualan ?? '';
        elseif($jumlah > 1)
            return $jumlah.' Tipe Penjualan'; 
    }

    private function hargaData(){
        if($this->is_tipe_penjualan == 1){
            $jumlahHarga = 0;
            foreach($this->variasi as $variasi){
                if($variasi->tipePenjualan)
                    $jumlahHarga += $variasi->tipePenjualan->count();
            }
            return $jumlahHarga;
        }
    }

}
