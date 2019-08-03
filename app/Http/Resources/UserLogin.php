<?php

namespace App\Http\Resources;

// use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
// use App\Http\Resources\Bisnis;
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
                'nama' => $this->name,
                'email' => $this->email,
                'telpon' => $this->telpon,
                'alamat' => $this->alamat,
                'outlet_terpilih_id' => $this->outlet_terpilih_id,
                'jenis_item_terpilih' => $this->jenis_item_terpilih,
                'akses_outlet' => UsersOutlet::collection($this->outlet),
                'role' => $this->role,
            
        ];
    }
}
