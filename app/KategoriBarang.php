<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KategoriBarang extends Model
{
    protected $table = 'kategori_barang';
    protected $primaryKey = 'id_kategori_barang';

    protected $guarded = [];

    public function barang(){
    	return $this->hasMany(Barang::class, 'kategori_barang_id');
    }

    public function outlet(){
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }

    public function setNamaKategoriBarangAttribute($value)
    {
        $this->attributes['nama_kategori_barang'] = ucfirst($value);
    }

    public function jumlahBarang(){
        return $this->barang()->where('outlet_id', $this->outlet_id)->count();
    }

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $user = auth()->user();
            if($model->bisnis_id == null)
                $model->bisnis_id = $user->bisnis_id;
        });
    }
}
