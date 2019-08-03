<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PenyesuaianStok extends Model
{
    protected $table = 'penyesuaian_stok';
    protected $primaryKey = 'id_penyesuaian_stok';

    protected $guarded = [];

    
    public function outlet(){
    	return $this->belongsTo(Outlet::class,'outlet_id');
    }

    public function entry(){
        return $this->hasMany(PenyesuaianStokEntry::class,'penyesuaian_stok_id');
    }

    

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $user = auth()->user();
            $model->bisnis_id = $user->bisnis_id;
        });
    }
}
