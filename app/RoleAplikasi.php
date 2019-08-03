<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoleAplikasi extends Model
{
    protected $table = 'role_aplikasi';
    protected $primaryKey = 'id_role_aplikasi';

    protected $guarded = [];

    public function role(){
        return $this->belongsTo(Role::class,'role_id');
    }

    public function aplikasi(){
        return $this->belongsTo(Aplikasi::class, 'aplikasi_id');
    }

    public function otorisasi(){
    	return $this->hasMany(RoleOtorisasi::class,'role_aplikasi_id')->where('parent_id',0);
    }

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $user = auth()->user();
            if($model->bisnis_id == null)
                $model->bisnis_id = $user->bisnis_id;
        });
    }
}
