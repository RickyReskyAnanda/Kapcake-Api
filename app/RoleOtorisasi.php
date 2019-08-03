<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoleOtorisasi extends Model
{
    protected $table = 'role_otorisasi';
    protected $primaryKey = 'id_role_otorisasi';

    protected $guarded = [];

    public function role(){
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function aplikasi(){
        return $this->belongsTo(RoleAplikasi::class, 'role_aplikasi_id');
    }

    public function parent(){
        return $this->belongsTo(RoleOtorisasi::class,'parent_id');
    }

    public function child(){
    	return $this->hasMany(RoleOtorisasi::class, 'parent_id');
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
