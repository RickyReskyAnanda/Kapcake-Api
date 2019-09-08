<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';
    protected $primaryKey = 'id_menu';

    protected $guarded = [];

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $user = auth()->user();
            $model->bisnis_id = $user->bisnis_id;
        });

        static::deleting(function ($model) {
            $model->variasi()->delete();
            $model->tipePenjualan()->delete();
            $model->itemTambahan()->delete();
            $model->gambar()->delete();
        });
    }

    public function kategori(){
    	return $this->belongsTo(KategoriMenu::class,'kategori_menu_id');
    }

    public function tipePenjualan(){
        return $this->hasMany(MenuTipePenjualan::class, 'menu_id');
    }

    public function variasi(){
    	return $this->hasMany(VariasiMenu::class, 'menu_id');
    }

    public function itemTambahan(){
    	return $this->hasMany(ItemTambahanMenu::class,'menu_id');
    }

    public function gambar(){
        return $this->hasMany(GambarMenu::class, 'menu_id');
    }

    public function oriGambar(){
        return $this->hasOne(GambarMenu::class, 'menu_id')->where('is_thumbnail',0);
    }
    public function thumbGambar(){
        return $this->hasOne(GambarMenu::class, 'menu_id')->where('is_thumbnail',1);
    }
    public function totalVariasi(){
        return $this->variasi()->count();
    }

    public function totalStok(){
        return $this->is_kelola_inventori == 1 ? $this->variasi()->sum('stok') : '';
    }

    public function totalStokRendah(){
        return $this->is_kelola_inventori == 1 ? $this->variasi()->where('stok_rendah','>=','stok')->count() : '';
    }
}
