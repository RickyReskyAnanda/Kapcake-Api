<?php

namespace App\Listeners;
use DB;
use App\KategoriMenu;
use App\KategoriBahanDapur;
use App\KategoriBarang;
use App\TipePenjualan;
use App\Outlet;
use App\OutletUser;
use App\Role;
use App\Aplikasi;
use App\Events\BisnisCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BisnisCreatedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  BisnisCreated  $event
     * @return void
     */
    public function handle(BisnisCreated $event)
    {
        DB::beginTransaction();
        try {
            $outlet = Outlet::create([
                'bisnis_id' => $event->bisnis->id_bisnis,
                'nama_outlet' => 'Outlet 1' ,
                'provinsi' => $event->bisnis->provinsi ,
                'kode_pos' => '00000' ,
            ]);
            KategoriMenu::create([
                'bisnis_id' => $event->bisnis->id_bisnis,
                'outlet_id' => $outlet->id_outlet,
                'nama_kategori_menu' => 'Tidak Dikategorikan' ,
            ]);
            
            KategoriBahanDapur::create([
                'bisnis_id' => $event->bisnis->id_bisnis,
                'outlet_id' => $outlet->id_outlet,
                'nama_kategori_bahan_dapur' => 'Tidak Dikategorikan' ,
            ]);

            KategoriBarang::create([
                'bisnis_id' => $event->bisnis->id_bisnis,
                'outlet_id' => $outlet->id_outlet,
                'nama_kategori_barang' => 'Tidak Dikategorikan' ,
            ]);

            TipePenjualan::create([
                'bisnis_id' => $event->bisnis->id_bisnis,
                'outlet_id' => $outlet->id_outlet,
                'nama_tipe_penjualan' => 'Dine In' ,
                'is_aktif' => '1' ,
                'is_paten' => '1' 
            ]);

            OutletUser::create([
                'bisnis_id' => $event->bisnis->id_bisnis,
                'outlet_id' => $outlet->id_outlet,
                'user_id' => $event->bisnis->user_id ,
            ]);

            $administrator = Role::create([
                'bisnis_id' => $event->bisnis->id_bisnis,
                'nama_role' => 'Administrator',
                'is_paten' => 1
            ]);

            $aplikasi = Aplikasi::with('otorisasi','otorisasi.child')->get();
            foreach($aplikasi as $a){
                $app = $administrator
                    ->aplikasi()
                    ->create([
                        'bisnis_id' => $event->bisnis->id_bisnis,
                        'aplikasi_id' => $a->id_aplikasi,
                        'is_aktif' => 1
                    ]);

                foreach($a->otorisasi as $otorisasi){
                    $oto = $app
                    ->otorisasi()
                    ->create([
                        'bisnis_id' => $event->bisnis->id_bisnis,
                        'role_id' => $app->role_id,
                        'parent_id' => 0,
                        'otorisasi_id' => $otorisasi->id_otorisasi,
                        'is_aktif' => $otorisasi->is_aktif,
                    ]);

                    foreach ($otorisasi->child as $child) {
                        $oto
                        ->child()
                        ->create([
                            'bisnis_id' => $event->bisnis->id_bisnis,
                            'role_id' => $app->role_id,
                            'role_aplikasi_id' => $oto->role_aplikasi_id,
                            'otorisasi_id' => $child->id_otorisasi,
                            'is_aktif' => $otorisasi->is_aktif,
                        ]);
                    }

                }
            }

            $kasir = Role::create([
                'bisnis_id' => $event->bisnis->id_bisnis,
                'nama_role' => 'Kasir',
                'is_paten' => 1
            ]);


            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response('error', 500);
        }
    }
}
