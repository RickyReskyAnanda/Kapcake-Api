<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuTipePenjualan extends Model
{
    protected $table = 'menu_tipe_penjualan';
    protected $primaryKey = 'id_menu_tipe_penjualan';

    protected $guarded = [];

    public function menu(){
    	return $this->belongsTo(Menu::class,'menu_id');
    }

    public function tipePenjualan(){
    	return $this->belongsTo(TipePenjualan::class,'tipe_penjualan_id');
    }

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $user = auth()->user();
            $model->bisnis_id = $user->bisnis_id;
        });

        static::deleting(function ($model) {
            $model->variasi()->delete();
            $model->itemTambahan()->delete();
            $model->gambar()->delete();
        });
    }
}
