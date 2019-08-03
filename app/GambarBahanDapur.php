<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GambarBahanDapur extends Model
{
    protected $table = 'gambar_bahan_dapur';
    protected $primaryKey = 'id_gambar_bahan_dapur';

    protected $guarded = [];

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $user = auth()->user();
            $model->bisnis_id = $user->bisnis_id;
        });
    }
}
