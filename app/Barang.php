<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'id_barang';

    protected $guarded = [];

    
    public function kategori(){
    	return $this->belongsTo(KategoriBarang::class, 'kategori_barang_id');
    }

    public function satuan(){
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }

    public function outlet(){
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }

    public function setNamaBarangAttribute($value)
    {
        $this->attributes['nama_barang'] = ucfirst($value);
    }

    public function setKategoriBarangIdAttribute($value)
    {
        if((int)$value == 0)
            $this->attributes['kategori_barang_id'] = auth()->user()->bisnis->kategoriBarangPaten()->id_kategori_barang;
        else
            $this->attributes['kategori_barang_id'] = $value;
    }

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $user = auth()->user();
            $model->bisnis_id = $user->bisnis_id;
        });
    }
}
