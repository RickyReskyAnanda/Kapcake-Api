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

            $variasi = VariasiMenu::where('kode_unik_variasi_menu', $model->item->kode_unik_variasi_menu ?? '-')
                        ->where('outlet_id', $model->parent->outlet_tujuan_id ?? 0)
                        ->first();
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
        });

    }
}
