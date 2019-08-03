<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GambarPerlengkapan extends Model
{
    protected $table = 'gambar_perlengkapan';
    protected $primaryKey = 'id_gambar_perlengkapan';

    protected $guarded = [];

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $user = auth()->user();
            $model->bisnis_id = $user->bisnis_id;
        });
    }
}
