<?php

namespace App\Http\Resources\Kasir;

use Illuminate\Http\Resources\Json\JsonResource;
class UserLogin extends JsonResource
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
            'id'  => $this->id,
            'bisnis_id' => $this->bisnis_id,
            'nama' => $this->name,
            'email' => $this->email,
            'telpon' => $this->telpon,
            'alamat' => $this->alamat,
            'is_super_admin' => $this->is_super_admin,
            'pin' => $this->pin,
            'outlet' => UserOutlet::collection($this->outlet),
        ];
    }
}
