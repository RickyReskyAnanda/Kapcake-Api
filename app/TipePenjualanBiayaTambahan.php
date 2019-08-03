<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipePenjualanBiayaTambahan extends Model
{
    protected $table = 'tipe_penjualan_biaya_tambahan';
    protected $primaryKey = 'id_tipe_penjualan_biaya_tambahan';

    protected $guarded = [];

    public function tipePenjualan(){
    	return $this->belongsTo(TipePenjualan::class,'tipe_penjualan_id');
    }
    public function biayaTambahan(){
        return $this->belongsTo(BiayaTambahan::class,'biaya_tambahan_id');
    }

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $user = auth()->user();
            $model->bisnis_id = $user->bisnis_id;
        });
    }
}
