<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PesananPembelian extends Model
{
    protected $table = 'pesanan_pembelian';
    protected $primaryKey = 'id_pesanan_pembelian';

    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function outlet(){
        return $this->belongsTo(Outlet::class,'outlet_id');
    }

    public function supplier(){
        return $this->belongsTo(Supplier::class,'supplier_id');
    }

    public function entry(){
    	return $this->hasMany(PesananPembelianEntry::class, 'pesanan_pembelian_id');
    }

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $user = auth()->user();
            $model->user_id = $user->id;
            $model->bisnis_id = $user->bisnis_id;
        });
        static::created(function ($model) {
            $user = auth()->user();
            $model->no_order = zeroFill($model->id_pesanan_pembelian);
            $model->save();
        });

        static::updated(function ($model) {
            if($model->status == 'selesai'){
                foreach($model->entry as $entry){
                    $item = $entry->item;
                    $item->stok += $entry->stok_dipesan;
                    $item->save();
                }
            }
        });
    }
}
