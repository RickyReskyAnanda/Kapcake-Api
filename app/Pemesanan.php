<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    protected $table = 'pemesanan';
    protected $primaryKey = 'id_pemesanan';

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
    	return $this->hasMany(PemesananItem::class,'pemesanan_id');
    }
}
