<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Events\PesananPembelianEntrySaved;

class PesananPembelianEntry extends Model
{
    protected $table = 'pesanan_pembelian_entry';
    protected $primaryKey = 'id_pesanan_pembelian_entry';

    protected $guarded = [];

    // protected $dispatchesEvents = [
    //     'created' => PesananPembelianEntrySaved::class,
    //     'updated' => PesananPembelianEntrySaved::class,
    // ];

    public function item(){
        if($this->tipe_item == 'menu') return $this->belongsTo(VariasiMenu::class, 'item_id');
        else if($this->tipe_item == 'bahan_dapur') return $this->belongsTo(BahanDapur::class, 'item_id');
        else if($this->tipe_item == 'barang') return $this->belongsTo(Barang::class, 'item_id');
    }

    public function pesananPembelian(){
        return $this->belongsTo(PesananPembelian::class, 'pesanan_pembelian_id');
    }

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $user = auth()->user();
            $model->bisnis_id = $user->bisnis_id;
        });
    }
}
