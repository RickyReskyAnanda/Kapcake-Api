<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KategoriBahanDapur extends Model
{
    protected $table = 'kategori_bahan_dapur';
    protected $primaryKey = 'id_kategori_bahan_dapur';

    protected $guarded = [];

    public function bahanDapur(){
    	return $this->hasMany(BahanDapur::class, 'kategori_bahan_dapur_id');
    }

    public function outlet(){
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $user = auth()->user();
            if($model->bisnis_id == null)
                $model->bisnis_id = $user->bisnis_id;
        });

        // static::deleting(function ($model) {
        //     $model->variasi()->delete();
        //     $model->itemTambahan()->delete();
        // });
    }
}
