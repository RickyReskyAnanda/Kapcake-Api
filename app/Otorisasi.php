<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Otorisasi extends Model
{
    protected $table = 'otorisasi';
    protected $primaryKey = 'id_otorisasi';

    protected $guarded = [];

    public function child(){
    	return $this->hasMany(Otorisasi::class,'parent_id');
    }
}
