<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoriMenu extends Model
{
    protected $table = 'inventori_menu';
    protected $primaryKey = 'id_inventori_menu';
    protected $guarded = [];

    public function outlet(){
    	return $this->belongsTo(Outlet::class, 'outlet_id');
    }

    public function menu(){
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function kategoriMenu(){
    	return $this->belongsTo(KategoriMenu::class, 'kategori_menu_id');
    }

    public function variasiMenu(){
    	return $this->belongsTo(VariasiMenu::class, 'variasi_menu_id');
    }

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $user = auth()->user();
            $model->bisnis_id = $user->bisnis_id;
        });
    }
}
