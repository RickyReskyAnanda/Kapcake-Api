<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'role';
    protected $primaryKey = 'id_role';

    protected $guarded = [];

    public function aplikasi(){
    	return $this->hasMany(RoleAplikasi::class,'role_id');
    }

    public function otorisasi(){
    	return $this->hasMany(RoleOtorisasi::class,'role_id');
    }

    public function user(){
        return $this->hasMany(User::class, 'role_id');
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
