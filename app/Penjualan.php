<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $table = 'penjualan';
    protected $primaryKey = 'id_penjualan';

    protected $guarded = [];

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $user = auth()->user();
            $model->user_id = $user->id;
            $model->nama_user = $user->name;
        });
    }

    public function item(){
    	return $this->hasMany(PenjualanItem::class,'penjualan_id');
    }

    public function kasir(){
        return $this->belongsTo(User::class,'id', 'user_id');
    }

    public function kasirTransfer(){
        return $this->belongsTo(User::class,'id','user_transfer_id');
    }

    public function diskon(){
        return $this->belongsTo(Diskon::class, 'diskon_id');
    }

    public function pajak(){
        return $this->belongsTo(Pajak::class, 'pajak_id');
    }

    public function biayaTambahan(){
        return $this->belongsTo(BiayaTambahan::class, 'biaya_tambahan_id');
    }
}
