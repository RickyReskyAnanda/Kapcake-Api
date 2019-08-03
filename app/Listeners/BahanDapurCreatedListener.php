<?php

namespace App\Listeners;

use App\Events\BahanDapurCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use DB;
use App\InventoriBahanDapur;

class BahanDapurCreatedListener
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
     * @param  BahanDapurCreated  $event
     * @return void
     */
    public function handle(BahanDapurCreated $event)
    {
        DB::beginTransaction();
        try {
            if($event->bahanDapur->is_inventarisasi == 1)
                InventoriBahanDapur::create([
                    'bisnis_id' => $event->bahanDapur->bisnis_id,
                    'outlet_id' => $event->bahanDapur->outlet_id,
                    'bahan_dapur_id' => $event->bahanDapur->id_bahan_dapur,
                    'kategori_bahan_dapur_id' => $event->bahanDapur->kategori_bahan_dapur_id,
                    'tanggal' => date('Y-m-d'),
                    'stok_awal' => $event->bahanDapur->stok,
                    'pesanan_pembelian' => 0,
                    'pemakaian' => 0,
                    'transfer' => 0,
                    'penyesuaian_stok' => 0,
                    'stok_akhir' => $event->bahanDapur->stok,
                ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response('error', 500);
        }
    }
}
