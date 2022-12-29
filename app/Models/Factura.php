<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Factura extends Model
{
    protected $table = 'factura_data';
    protected $fillable = ['nro_chasis', 'nro_vin'];
    

    
}