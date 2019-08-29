<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransferEntry extends Model
{
    protected $table = 'transfer_entry';
    protected $primaryKey = 'id_transfer_entry';

    protected $guarded = [];

    public function item(){
        if($this->tipe_item == 'menu') return $this->belongsTo(VariasiMenu::class, 'item_id');
        else if($this->tipe_item == 'bahan_dapur') return $this->belongsTo(BahanDapur::class, 'item_id');
        else if($this->tipe_item == 'barang') return $this->belongsTo(Barang::class, 'item_id');
    }

    public function parent(){
        return $this->belongsTo(Transfer::class, 'transfer_id');
    }

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $user = auth()->user();
            $model->bisnis_id = $user->bisnis_id;
        });

        static::created(function ($model) {
            $itemVariasi = $model->item;
            $itemVariasi->stok -= $model->jumlah_transfer;
            $itemVariasi->save();    

            ////// VARIASI MENU ///////
            if($model->tipe_item == 'menu'){
                if($model->item_id != null && $model->parent != null){
                    $variasi = VariasiMenu::where('id_variasi_menu', $model->item_id)
                            ->where('outlet_id', $model->parent->outlet_tujuan_id)
                            ->count();
                    if($variasi){
                        $variasi->stok += $model->jumlah_transfer;
                        $variasi->save();
                    }else{
                        VariasiMenu::create([
                            'kode_unik_variasi_menu' => $model->item->kode_unik_variasi_menu,
                            'bisnis_id' => $model->item->bisnis_id,
                            'outlet_id' => $model->parent->outlet_tujuan_id,
                            'kategori_menu_id' => $model->item->kategori_menu_id,
                            'menu_id' => $model->item->menu_id,
                            'nama_variasi_menu' => $model->item->nama_variasi_menu,
                            'harga' => $model->item->harga,
                            'sku' => $model->item->sku,
                            'stok' => $model->jumlah_transfer,
                            'stok_rendah' => $model->item->stok_rendah,
                            'is_inventarisasi' => 1,
                        ]);
                    }
                }
            }
            ////// BARANG ///////
            elseif($model->tipe_item == 'barang'){
                if($model->item_id != null && $model->parent != null){
                    $barang = Barang::where('id_barang', $model->item_id)
                            ->where('outlet_id', $model->parent->outlet_tujuan_id)
                            ->count();
                    if($barang > 0){
                        $barang->stok += $model->jumlah_transfer;
                        $barang->save();
                    }else{
                        Barang::create([
                            'kode_unik_barang' => $model->item->kode_unik_barang,
                            'bisnis_id' => $model->item->bisnis_id,
                            'outlet_id' => $model->parent->outlet_tujuan_id,
                            'kategori_barang_id' => $model->item->kategori_barang_id,
                            'nama_barang' => $model->item->nama_barang,
                            'satuan_id' => $model->item->satuan_id,
                            'stok' => $model->jumlah_transfer,
                            'stok_rendah' => $model->item->stok_rendah,
                            'is_inventarisasi' => 1,
                        ]);
                    }
                }
            }

            elseif($model->tipe_item == 'bahan_dapur'){
                if($model->item_id != null && $model->parent != null){
                    $bahanDapur = BahanDapur::where('id_bahan_dapur', $model->item_id)
                            ->where('outlet_id', $model->parent->outlet_tujuan_id)
                            ->count();
                    if($bahanDapur > 0){
                        $bahanDapur->stok += $model->jumlah_transfer;
                        $bahanDapur->save();
                    }else{
                        BahanDapur::create([
                            'kode_unik_bahan_dapur' => $model->item->kode_unik_bahan_dapur,
                            'bisnis_id' => $model->item->bisnis_id,
                            'outlet_id' => $model->parent->outlet_tujuan_id,
                            'kategori_bahan_dapur_id' => $model->item->kategori_bahan_dapur_id,
                            'nama_bahan_dapur' => $model->item->nama_bahan_dapur,
                            'satuan_id' => $model->item->satuan_id,
                            'stok' => $model->jumlah_transfer,
                            'stok_rendah' => $model->item->stok_rendah,
                            'is_inventarisasi' => 1,
                        ]);
                    }
                }
            }
        });

    }
}
