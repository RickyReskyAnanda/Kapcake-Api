<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pajak extends Model
{
    protected $table = 'pajak';
    protected $primaryKey = 'id_pajak';

    protected $guarded = [];

    public function outlet(){
    	return $this->hasMany(OutletPajak::class,'pajak_id');
    }
}
