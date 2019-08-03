<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OutletUser extends Model
{
    protected $table = 'outlet_user';
    protected $primaryKey = 'id_outlet_user';

    protected $guarded = [];

    public function outlet(){
    	return $this->belongsTo(Outlet::class,'outlet_id');
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
