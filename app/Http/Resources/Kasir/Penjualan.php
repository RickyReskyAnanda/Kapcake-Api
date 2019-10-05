<?php

namespace App\Http\Resources\Kasir;

use Illuminate\Http\Resources\Json\JsonResource;

class Penjualan extends JsonResource
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
                    'id_penjualan' => $this->id_penjualan,
                    'bisnis_id' => $this->bisnis_id,
                    'outlet_id' => $this->outlet_id,
                    
                    'kode_pemesanan' => $this->kode_pemesanan,
                    'no_pemesanan' => $this->no_pemesanan,
                    
                    'user_id' => $this->user_id,
                    'nama_user' => $this->nama_user,
                    
                    'user_transfer_id' => $this->user_transfer_id,
                    
                    'pelanggan_id' => $this->pelanggan_id,
                    'nama_pelanggan' => $this->nama_pelanggan,

                    'is_pembulatan' => $this->is_pembulatan,

                    'kategori_meja_id' => $this->kategori_meja_id,
                    'meja_id' => $this->meja_id,
                    'nama_kategori_meja' => $this->nama_kategori_meja,
                    'nama_meja' => $this->nama_meja,
                    
                    'nama_user_transfer' => $this->nama_user_transfer,
                    'status' => ucfirst($this->status),
                    'subtotal' => $this->subtotal,
                    
                    'diskon_id' => $this->diskon_id,
                    'nama_diskon' => $this->nama_diskon,
                    'jumlah_diskon' => $this->jumlah_diskon,
                    'total_diskon' => $this->total_diskon,
                    
                    'biaya_tambahan_id' => $this->biaya_tambahan_id,
                    'nama_biaya_tambahan' => $this->nama_biaya_tambahan,
                    'jumlah_biaya_tambahan' => $this->jumlah_biaya_tambahan,
                    'total_biaya_tambahan' => $this->total_biaya_tambahan,
                    
                    'pajak_id' => $this->pajak_id,
                    'nama_pajak' => $this->nama_pajak,
                    'jumlah_pajak' => $this->jumlah_pajak,
                    'total_pajak' => $this->total_pajak,
                    
                    'total_pembulatan' => $this->total_pembulatan,
                    
                    'total' => $this->total,
                    
                    'tunai' => $this->tunai,
                    'kembalian' => $this->kembalian,
                    
                    'catatan' => $this->catatan,
                    'tanggal_proses' => $this->tanggal_proses,
                    'tanggal_simpan' => $this->tanggal_simpan,
                    'waktu_proses' => $this->waktu_proses,
                    'waktu_simpan' => $this->waktu_simpan,
                    
                    'total_item' => $this->total_item,

                    'item' => PenjualanItem::collection($this->item),
            ];
    }
}
