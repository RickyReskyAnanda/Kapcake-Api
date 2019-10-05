<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Printer extends Model
{
    protected $table = 'printer';
    protected $primaryKey = 'id_printer';

    protected $guarded = [];

    public function outlet(){
    	return $this->belongsTo(Outlet::class,'outlet_id');
    }

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $user = auth()->user();
            $model->user_id = $user->id;
        });
    }
    
}
