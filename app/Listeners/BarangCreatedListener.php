<?php

namespace App\Listeners;

use App\Events\BarangCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use DB;
use App\InventoriBarang;

class BarangCreatedListener
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
     * @param  BarangCreated  $event
     * @return void
     */
    public function handle(BarangCreated $event)
    {
        DB::beginTransaction();
        try {
            if($event->barang->is_inventarisasi == 1)
                InventoriBarang::create([
                    'bisnis_id' => $event->barang->bisnis_id,
                    'outlet_id' => $event->barang->outlet_id,
                    'barang_id' => $event->barang->id_barang,
                    'kategori_barang_id' => $event->barang->kategori_barang_id,
                    'tanggal' => date('Y-m-d'),
                    'stok_awal' => $event->barang->stok,
                    'pesanan_pembelian' => 0,
                    'pemakaian' => 0,
                    'transfer' => 0,
                    'penyesuaian_stok' => 0,
                    'stok_akhir' => $event->barang->stok,
                ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response('error', 500);
        }
    }
}
