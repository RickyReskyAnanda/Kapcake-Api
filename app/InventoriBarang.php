<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoriBarang extends Model
{
    protected $table = 'inventori_barang';
    protected $primaryKey = 'id_inventori_barang';
    protected $guarded = [];


    public function outlet(){
    	return $this->belongsTo(Outlet::class, 'outlet_id');
    }

    public function kategoriBarang(){
        return $this->belongsTo(KategoriBarang::class, 'kategori_barang_id');
    }

    public function barang(){
    	return $this->belongsTo(Barang::class, 'barang_id');
    }

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $user = auth()->user();
            $model->bisnis_id = $user->bisnis_id;
        });
    }
}
