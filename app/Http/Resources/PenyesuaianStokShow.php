<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PenyesuaianStokShow extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
            return [
                    'id' => $this->id_penyesuaian_stok,
                    'tanggal' => dateFormat($this->created_at),
                    'outlet' => new Outlet($this->outlet),
                    'outlet_id' => $this->outlet_id,
                    'catatan' => ucfirst($this->catatan),
                    'selisih_stok' => $this->selisih_stok,
                    'entry' => PenyesuaianStokEntry::collection($this->entry)
            ];
    }
}
