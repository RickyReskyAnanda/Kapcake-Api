<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OutletPrinter extends Model
{
    protected $table = 'outlet_printer';
    protected $primaryKey = 'id_outlet_printer';

    protected $guarded = [];
}
