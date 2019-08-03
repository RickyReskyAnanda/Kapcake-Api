<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Aplikasi extends Model
{
    protected $table = 'aplikasi';
    protected $primaryKey = 'id_aplikasi';

    protected $guarded = [];

    public function otorisasi(){
    	return $this->hasMany(Otorisasi::class, 'aplikasi_id')->where('parent_id','0');
    }
}
