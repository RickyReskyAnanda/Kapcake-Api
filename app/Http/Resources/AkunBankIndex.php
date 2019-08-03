<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AkunBankIndex extends JsonResource
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
                    'id' => $this->id_akun_bank,
                    'nama' => $this->bank->nama_bank,
                    'no_rek' => $this->nomor_rekening,
                    'an' => $this->pemilik_akun,
                    'keterangan' => $this->keterangan,
                    'total_outlet' => $this->outlet->count(),
            ];
    }
}
