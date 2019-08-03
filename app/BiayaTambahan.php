<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BiayaTambahan extends Model
{
    protected $table = 'biaya_tambahan';
    protected $primaryKey = 'id_biaya_tambahan';

    protected $guarded = [];

    public function outlet(){
    	return $this->outlet(Outlet::class,'outlet_id');
    }
}
