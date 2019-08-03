<?php

namespace App;

use App\Events\BisnisCreated;
use Illuminate\Database\Eloquent\Model;

class Bisnis extends Model
{
    protected $table = 'bisnis';
    protected $primaryKey = 'id_bisnis';

    protected $guarded = [];

    protected $dispatchesEvents = [
        'created' => BisnisCreated::class
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function kategoriMenu(){
    	return $this->hasMany(KategoriMenu::class, 'bisnis_id');
    }

    public function menu(){
    	return $this->hasMany(Menu::class,'bisnis_id');
    }

    public function itemTambahan(){
    	return $this->hasMany(ItemTambahan::class, 'bisnis_id');
    }

    public function pajak(){
        return $this->hasMany(Pajak::class, 'bisnis_id');
    }

    public function biayaTambahan(){
        return $this->hasMany(BiayaTambahan::class, 'bisnis_id');
    }

    public function tipePenjualan(){
        return $this->hasMany(TipePenjualan::class, 'bisnis_id');
    }

    public function bahanDapur(){
        return $this->hasMany(BahanDapur::class, 'bisnis_id');
    }

    public function kategoriBahanDapur(){
        return $this->hasMany(KategoriBahanDapur::class, 'bisnis_id');
    }

    public function kategoriBahanDapurPaten(){
        return $this->kategoriBahanDapur()->where('is_paten', 1)->first();
    }

    public function barang(){
        return $this->hasMany(Barang::class, 'bisnis_id');
    }

    public function kategoriBarang(){
        return $this->hasMany(KategoriBarang::class, 'bisnis_id');
    }

    public function kategoriBarangPaten(){
        return $this->kategoriBarang()->where('is_paten', 1)->first();
    }

    public function inventoriMenu(){
        return $this->hasMany(InventoriMenu::class,'bisnis_id');
    }

    public function inventoriBahanDapur(){
        return $this->hasMany(InventoriBahanDapur::class,'bisnis_id');
    }

    public function inventoriBarang(){
        return $this->hasMany(InventoriBarang::class,'bisnis_id');
    }

    public function supplier(){
        return $this->hasMany(Supplier::class, 'bisnis_id');
    }

    public function pesananPembelian(){
        return $this->hasMany(PesananPembelian::class, 'bisnis_id');
    }

    public function penyesuaianStok(){
        return $this->hasMany(PenyesuaianStok::class, 'bisnis_id');
    }

    public function transfer(){
        return $this->hasMany(Transfer::class, 'bisnis_id');
    }

    public function staf(){
        return $this->hasMany(User::class, 'bisnis_id')->where('is_super_admin','0');
    }

    public function role(){
        return $this->hasMany(Role::class, 'bisnis_id');
    }

    public function pelanggan(){
        return $this->hasMany(Pelanggan::class, 'bisnis_id');
    }

    public function feedback(){
        return $this->hasMany(Feedback::class, 'bisnis_id');
    }

    public function kategoriMeja(){
        return $this->hasMany(KategoriMeja::class, 'bisnis_id');
    }

    public function meja(){
        return $this->hasMany(Meja::class,'bisnis_id');
    }

    public function outlet(){
        return $this->hasMany(Outlet::class,'bisnis_id');
    }

    public function akunBank(){
        return $this->hasMany(AkunBank::class, 'bisnis_id');
    }

    ///////////////// KHUSUS ///////////////////
    public function logo(){
        return $this->hasMany(GambarBisnis::class, 'bisnis_id');
    }



    /////////////////// UMUM //////////////////////////
    public function satuan(){
        return $this->hasMany(Satuan::class, 'bisnis_id');
    }    


    ////////////////// KHUSUS KASIR ////////////////////////
    public function pemesanan(){
        return $this->hasMany(Pemesanan::class, 'bisnis_id');
    }

    public function penjualan(){
        return $this->hasMany(Penjualan::class, 'bisnis_id');
    }

    public function penjualanItem(){
        return $this->hasMany(PenjualanItem::class, 'bisnis_id');
    }

}
