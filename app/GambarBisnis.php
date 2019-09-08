<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GambarBisnis extends Model
{
    protected $table = 'gambar_bisnis';
    protected $primaryKey = 'id_gambar_bisnis';

    protected $guarded = [];

    public static function boot() {
        parent::boot();
        static::creating(function ($model) {
            $model->link = asset($model->access_path);
        });

        static::deleting(function ($model) {
        	unlink($model->access_path);
        });
    }
}
