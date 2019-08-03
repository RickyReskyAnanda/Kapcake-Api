<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    protected $table = 'transfer';
    protected $primaryKey = 'id_transfer';

    protected $guarded = [];
    
    public function outletAsal(){
    	return $this->belongsTo(Outlet::class,'outlet_asal_id');
    }

    public function outletTujuan(){
        return $this->belongsTo(Outlet::class,'outlet_tujuan_id');
    }

    public function entry(){
        return $this->hasMany(TransferEntry::class,'transfer_id');
    }
    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $user = auth()->user();
            $model->bisnis_id = $user->bisnis_id;
        });

    }
}
