<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemTambahan extends Model
{
    protected $table = 'item_tambahan';
    protected $primaryKey = 'id_item_tambahan';

    protected $guarded = [];

    public function outlet(){
        return $this->belongsTo(Outlet::class,'outlet_id');
    }
    
    public function menu(){
    	return $this->hasMany(ItemTambahanMenu::class,'item_tambahan_id');
    }

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $user = auth()->user();
            $model->bisnis_id = $user->bisnis_id;
        });

        static::deleting(function ($model) {
            $model->menu()->delete();
        });
    }
}
