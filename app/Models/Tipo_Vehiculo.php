<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tipo_Vehiculo extends Model
{
    protected $table = 'tipo_vehiculos';

    protected $fillable = ['name'];
    protected $guarded = ['id'];
}
