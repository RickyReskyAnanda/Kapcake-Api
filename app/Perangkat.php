<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Perangkat extends Model
{
    protected $table = 'perangkat';
    protected $primaryKey = 'id_perangkat';

    protected $guarded = [];

    public function outlet(){
    	return $this->belongsTo(Outlet::class, 'outlet_id');
    }
}
