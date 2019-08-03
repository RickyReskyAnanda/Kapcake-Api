<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VariasiMenuTipePenjualan extends Model
{
    protected $table = 'variasi_menu_tipe_penjualan';
    protected $primaryKey = 'id_variasi_menu_tipe_penjualan';

    protected $guarded = [];

    public function tipePenjualan(){
        return $this->belongsTo(TipePenjualan::class,'tipe_penjualan_id');
    }

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $user = auth()->user();
            $model->bisnis_id = $user->bisnis_id;
        });
    }
}
