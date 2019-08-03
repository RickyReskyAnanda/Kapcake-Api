<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PenyesuaianStokEntry extends Model
{
    protected $table = 'penyesuaian_stok_entry';
    protected $primaryKey = 'id_penyesuaian_stok_entry';

    protected $guarded = [];

    
    public function item(){
        if($this->tipe_item == 'menu') return $this->belongsTo(VariasiMenu::class, 'item_id');
        else if($this->tipe_item == 'bahan_dapur') return $this->belongsTo(BahanDapur::class, 'item_id');
        else if($this->tipe_item == 'barang') return $this->belongsTo(Barang::class, 'item_id');
    }

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $user = auth()->user();
            $model->bisnis_id = $user->bisnis_id;
        });

        static::created(function ($model) {
            $itemVariasi = $model->item;
            $itemVariasi->stok = $model->stok_aktual;
            $itemVariasi->save();           
        });
    }
}
