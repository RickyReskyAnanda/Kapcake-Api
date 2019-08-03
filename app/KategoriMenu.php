<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KategoriMenu extends Model
{
    protected $table = 'kategori_menu';
    protected $primaryKey = 'id_kategori_menu';

    protected $guarded = [];

    public function menu(){
        return $this->hasMany(Menu::class, 'kategori_menu_id');
    }

    public function outlet(){
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }

    public function setNamaKategoriMenuAttribute($value)
    {
        $this->attributes['nama_kategori_menu'] = ucfirst($value);
    }
    
    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $user = auth()->user();
            if($model->bisnis_id == null)
                $model->bisnis_id = $user->bisnis_id;
        });

        // static::deleting(function ($model) {
        //     $model->variasi()->delete();
        //     $model->itemTambahan()->delete();
        // });
    }
}
