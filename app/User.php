<?php

namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
class User extends Authenticatable
{
    use Notifiable, SoftDeletes;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bisnis_id',
        'role_id',
        'name',
        'email',
        'password',
        'telpon',
        'alamat',
        'pin',
        'is_aktif_pin',
        'status',
        'is_super_admin',
        'is_selesai_registrasi',
        'outlet_terpilih_id',
        'jenis_item_terpilih',
        'activation_token',
        'api_token',
        'is_active',
    ];

    protected $dates = ['deleted_at'];
    protected $hidden = [
        'password', 'remember_token', 'activation_token', 'created_at', 'updated_at','deleted_at'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public function bisnis(){
        return $this->belongsTo(Bisnis::class,'bisnis_id');
    }
    
    public function outlet(){
        return $this->hasMany(OutletUser::class, 'user_id');
    }

    public function role(){
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function roleBackoffice(){
        return ($this->role
                    ->aplikasi()
                    ->where('aplikasi_id',1)
                    ->with('otorisasi.child')
                    ->first())->otorisasi;
    }

    public function isSuperAdmin(){
        return $this->is_super_admin == 1 ? true : false ;
    }

    
    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            if(auth()->user()){
                $user = auth()->user();
                $model->bisnis_id = $user->bisnis_id;
            }
        });
        static::deleting(function ($model) {
            $model->outlet()->delete();
            $model->role()->delete();
        });


    }
}
