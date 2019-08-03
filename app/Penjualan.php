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
}
