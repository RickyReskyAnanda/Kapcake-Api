<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemTambahanMenu extends Model
{
    protected $table = 'item_tambahan_menu';
    protected $primaryKey = 'id_item_tambahan_menu';

    protected $guarded = [];

    public function menu(){
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function itemTambahan(){
        return $this->belongsTo(ItemTambahan::class, 'item_tambahan_id');
    }

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $user = auth()->user();
            $model->bisnis_id = $user->bisnis_id;
        });
    }
}
