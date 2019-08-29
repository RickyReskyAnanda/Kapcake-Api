<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PenyesuaianStokIndex extends JsonResource
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
                    'outlet' => $this->outlet->nama_outlet ?? '-',
                    'tanggal' => dateFormat($this->created_at),
                    'catatan' => ucfirst($this->catatan),
                    'total_item' => $this->entry->count(). ' Item',
                    'selisih_stok' => $this->selisih_stok,
            ];
    }
}
