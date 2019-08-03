<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OutletAkunBank extends Model
{
    protected $table = 'outlet_akun_bank';
    protected $primaryKey = 'id_outlet_akun_bank';

    protected $guarded = [];

    public function outlet(){
    	return $this->belongsTo(Outlet::class,'outlet_id');
    }

    public function akunBank(){
        return $this->belongsTo(AkunBank::class,'akun_bank_id');
    }

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            if(auth()->user()){
                $user = auth()->user();
                $model->bisnis_id = $user->bisnis_id;
            }
        });
    }
}
