<?php

namespace App\Listeners;

use App\Events\VariasiMenuCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use DB;
use App\InventoriMenu;

class VariasiMenuCreatedListener
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
     * @param  VariasiMenuCreate  $event
     * @return void
     */
    public function handle(VariasiMenuCreated $event)
    {
        DB::beginTransaction();
        try {
            if($event->variasiMenu->is_inventarisasi == 1)
                InventoriMenu::create([
                    'bisnis_id' => $event->variasiMenu->bisnis_id,
                    'outlet_id' => $event->variasiMenu->outlet_id,
                    'menu_id' => $event->variasiMenu->menu_id,
                    'variasi_menu_id' => $event->variasiMenu->id_variasi_menu,
                    'tanggal' => date('Y-m-d'),
                    'stok_awal' => $event->variasiMenu->stok,
                    'penjualan' => 0,
                    'transfer' => 0,
                    'penyesuaian_stok' => 0,
                    'stok_akhir' => $event->variasiMenu->stok,
                ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response('error', 500);
        }
    }
}
