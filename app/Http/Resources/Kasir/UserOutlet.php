<?php

namespace App\Http\Resources\Kasir;

use Illuminate\Http\Resources\Json\JsonResource;

class UserOutlet extends JsonResource
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
                'id' => $this->outlet_id,
                'nama' => $this->outlet->nama_outlet ?? '',
            ];
    }
}
