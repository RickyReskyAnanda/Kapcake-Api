<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoriBahanDapur extends Model
{
    protected $table = 'inventori_bahan_dapur';
    protected $primaryKey = 'id_inventori_bahan_dapur';
    protected $guarded = [];


    public function outlet(){
    	return $this->belongsTo(Outlet::class, 'outlet_id');
    }

    public function kategoriBahanDapur(){
        return $this->belongsTo(KategoriBahanDapur::class, 'kategori_bahan_dapur_id');
    }

    public function bahanDapur(){
    	return $this->belongsTo(BahanDapur::class, 'bahan_dapur_id');
    }

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $user = auth()->user();
            $model->bisnis_id = $user->bisnis_id;
        });
    }
}
