<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    protected $table = 'sucursales';
    protected $fillable = ['name', 'concesionaria_id', 'region', 'comuna', 'calle', 'numero'];
    protected $guarded = ['id'];

    public function concesionaria(){
        return $this->belongsTo(Concesionaria::class);
    }
}
