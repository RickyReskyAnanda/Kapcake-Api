<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    protected $table = 'outlet';
    protected $primaryKey = 'id_outlet';

    protected $guarded = [];

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $user = auth()->user();
            if($model->bisnis_id == null)
                $model->bisnis_id = $user->bisnis_id;
        });
    }

    public function kategoriMenu(){
        return $this->hasMany(KategoriMenu::class,'outlet_id')->where('bisnis_id', auth()->user()->bisnis_id);
    }
    public function menu(){
        return $this->hasMany(Menu::class,'outlet_id')->where('bisnis_id', auth()->user()->bisnis_id);
    }

    public function meja(){
        return $this->hasMany(Meja::class,'outlet_id')->where('bisnis_id', auth()->user()->bisnis_id);
    }

    public function kategoriMeja(){
        return $this->hasMany(KategoriMeja::class,'outlet_id')->where('bisnis_id', auth()->user()->bisnis_id);
    }

    public function pajak(){
        return $this->hasOne(Pajak::class,'outlet_id')->where('bisnis_id', auth()->user()->bisnis_id);
    }

    public function pajakTerpilih(){
        return $this->belongsTo(Pajak::class,'pajak_id')->where('bisnis_id', auth()->user()->bisnis_id);
    }

    public function diskon(){
        return $this->hasMany(Diskon::class,'outlet_id')->where('bisnis_id', auth()->user()->bisnis_id);
    }

    public function biayaTambahan(){
        return $this->hasMany(BiayaTambahan::class,'outlet_id')->where('bisnis_id', auth()->user()->bisnis_id);
    }

    public function jenisPemesanan(){
        return $this->hasMany(TipePenjualan::class,'outlet_id')->where('bisnis_id', auth()->user()->bisnis_id);
    }


    ////////////////// KHUSUS KASIR ////////////////////////
    public function pemesanan(){
        return $this->hasMany(Pemesanan::class, 'outlet_id');
    }

    public function penjualan(){
        return $this->hasMany(Penjualan::class, 'outlet_id');
    }

}
