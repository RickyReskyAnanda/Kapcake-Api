<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StafIndex extends JsonResource
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
                    'id' => $this->id,
                    'nama' => $this->name,
                    'role' => $this->role->nama_role ?? '',
                    'total_outlet' => $this->outlet->count() .' Outlet',
            ];
    }
}
