<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipePenjualan extends Model
{
    protected $table = 'tipe_penjualan';
    protected $primaryKey = 'id_tipe_penjualan';

    protected $guarded = [];

    public function biayaTambahan(){
    	return $this->hasMany(TipePenjualanBiayaTambahan::class, 'tipe_penjualan_id');
    }

    public function outlet(){
    	return $this->belongsTo(Outlet::class,'outlet_id');
    }

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $user = auth()->user();
            $model->bisnis_id = $user->bisnis_id;
        });

        static::deleting(function ($model) {
            $model->biayaTambahan()->delete();
        });
    }
}
