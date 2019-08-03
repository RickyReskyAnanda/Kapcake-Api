<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    protected $table = 'satuan';
    protected $primaryKey = 'id_satuan';

    protected $guarded = [];

    public function bisnis(){
    	return $this->belongsTo(Bisnis::class,'bisnis_id')->where('bisnis_id', '!=', 0);
    }
}
