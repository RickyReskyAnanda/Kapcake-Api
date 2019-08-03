<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Events\BahanDapurCreated;
class BahanDapur extends Model
{
    protected $table = 'bahan_dapur';
    protected $primaryKey = 'id_bahan_dapur';

    protected $guarded = [];

    protected $dispatchesEvents = [
        'created' => BahanDapurCreated::class
    ];
    
    public function kategori(){
    	return $this->belongsTo(KategoriBahanDapur::class,'kategori_bahan_dapur_id');
    }

    public function outlet(){
        return $this->belongsTo(Outlet::class,'outlet_id');
    }

    public function satuan(){
        return $this->belongsTo(Satuan::class,'satuan_id');
    }

    public function setKategoriBahanDapurIdAttribute($value)
    {
        if((int)$value == 0)
            $this->attributes['kategori_bahan_dapur_id'] = auth()->user()->bisnis->kategoriBahanDapurPaten()->id_kategori_bahan_dapur;
        else
            $this->attributes['kategori_bahan_dapur_id'] = $value;
    }
    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $user = auth()->user();
            $model->bisnis_id = $user->bisnis_id;
        });
    }
}
