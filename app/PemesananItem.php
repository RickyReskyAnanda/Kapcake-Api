<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PemesananItem extends Model
{
     protected $table = 'pemesanan_item';
    protected $primaryKey = 'id_pemesanan_item';

    protected $guarded = [];

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $user = auth()->user();
            $model->user_id = $user->id;
            $model->bisnis_id = $user->bisnis_id;
        });
    }

    public function outlet(){
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }

    public function pemesanan(){
        return $this->belongsTo(Pemesanan::class,'pemesanan_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function menu(){
        return $this->belongsTo(Menu::class,'menu_id');
    }

    public function kategoriMenu(){
        return $this->belongsTo(KategoriMenu::class,'kategori_menu_id');
    }

    public function variasiMenu(){
        return $this->belongsTo(VariasiMenu::class,'variasi_menu_id');
    }

    public function tipePenjualan(){
        return $this->belongsTo(TipePenjualan::class,'tipe_penjualan_id');
    }

    public function bisnis(){
        return $this->belongsTo(Bisnis::class,'bisnis_id');
    }
}
