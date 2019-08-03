<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Events\VariasiMenuCreated;

class VariasiMenu extends Model
{
    protected $table = 'variasi_menu';
    protected $primaryKey = 'id_variasi_menu';

    protected $guarded = [];

    protected $dispatchesEvents = [
        'created' => VariasiMenuCreated::class
    ];

    public function menu(){
        return $this->belongsTo(Menu::class,'menu_id');
    }

    public function tipePenjualan(){
        return $this->hasMany(VariasiMenuTipePenjualan::class, 'variasi_menu_id');
    }

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $user = auth()->user();
            $model->bisnis_id = $user->bisnis_id;
            if($model->is_inventarisasi == 0){
                $model->stok = 0;
                $model->stok_rendah = 0;
            }
        });

        static::deleting(function ($model) {
            $model->tipePenjualan()->delete();
        });
    }
}
