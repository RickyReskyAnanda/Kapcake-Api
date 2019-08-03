<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BankIndex as BankIndexResource;

class BankController extends Controller
{
    public function index(Request $request)
    {
        $data =  \App\Bank::orderBy('nama_bank','asc')->get();
        return BankIndexResource::collection($data);
    }
}
