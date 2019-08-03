<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'supplier';
    protected $primaryKey = 'id_supplier';

    protected $guarded = [];

    public function bisnis(){
    	return $this->belongsTo(Bisnis::class,'bisnis_id');
    }
}
