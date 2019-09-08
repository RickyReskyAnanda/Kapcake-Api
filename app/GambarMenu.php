<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class GambarMenu extends Model
{
    protected $table = 'gambar_menu';
    protected $primaryKey = 'id_gambar_menu';

    protected $guarded = [];

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $user = auth()->user();
            $model->bisnis_id = $user->bisnis_id;
        });

        static::deleting(function ($model) {
            
            unlink($model->access_path);
        });
    }
}
