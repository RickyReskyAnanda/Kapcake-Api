<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\AkunBank' => 'App\Policies\AkunBankPolicy',
        'App\BahanDapur' => 'App\Policies\BahanDapurPolicy',
        'App\BiayaTambahan' => 'App\Policies\BiayaTambahanPolicy',
        'App\ItemTambahan' => 'App\Policies\ItemTambahanPolicy',
        'App\KategoriMenu' => 'App\Policies\KategoriMenuPolicy',
        'App\KategoriBahanDapur' => 'App\Policies\KategoriBahanDapurPolicy',
        'App\KategoriMeja' => 'App\Policies\KategoriMejaPolicy',
        'App\KategoriBarang' => 'App\Policies\KategoriBarangPolicy',
        'App\Menu' => 'App\Policies\MenuPolicy',
        'App\Outlet' => 'App\Policies\OutletPolicy',
        'App\Pajak' => 'App\Policies\PajakPolicy',
        'App\Pelanggan' => 'App\Policies\PelangganPolicy',
        'App\PenyesuaianStok' => 'App\Policies\PenyesuaianStokPolicy',
        'App\Barang' => 'App\Policies\BarangPolicy',
        'App\PesananPembelian' => 'App\Policies\PesananPembelianPolicy',
        'App\Role' => 'App\Policies\RolePolicy',
        'App\Staf' => 'App\Policies\StafPolicy',
        'App\Supplier' => 'App\Policies\SupplierPolicy',
        'App\TipePenjualan' => 'App\Policies\TipePenjualanPolicy',
        'App\Transfer' => 'App\Policies\TransferPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
