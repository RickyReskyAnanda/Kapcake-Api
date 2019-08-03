<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AkunBank extends Model
{
    protected $table = 'akun_bank';
    protected $primaryKey = 'id_akun_bank';

    protected $guarded = [];

    public function bank(){
        return $this->belongsTo(Bank::class,'bank_id');
    }

    public function outlet(){
        return $this->hasMany(OutletAkunBank::class, 'akun_bank_id');
    }

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $user = auth()->user();
            $model->bisnis_id = $user->bisnis_id;
        });
    }
}
