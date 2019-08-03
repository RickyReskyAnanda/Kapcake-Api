<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KategoriMeja extends Model
{
    protected $table = 'kategori_meja';
    protected $primaryKey = 'id_kategori_meja';

    protected $guarded = [];

    public function meja(){
    	return $this->hasMany(Meja::class, 'kategori_meja_id');
    }

    public function outlet(){
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $user = auth()->user();
            $model->bisnis_id = $user->bisnis_id;
            $model->outlet_id = $user->outlet_terpilih_id;
        });

    }
}
