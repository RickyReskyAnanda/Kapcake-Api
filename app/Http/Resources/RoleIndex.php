<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleIndex extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $namaAplikasi = [];        
        foreach( $this->aplikasi as $a ){
            if($a->is_aktif)
                array_push($namaAplikasi, $a->aplikasi->nama_aplikasi);
        }
        return [
                'id' => $this->id_role,
                'nama' => $this->nama_role,
                'is_paten' => $this->is_paten,
                'total_ditandai' => $this->user->count(),
                'aplikasi' => $namaAplikasi
        ];
    }
}
