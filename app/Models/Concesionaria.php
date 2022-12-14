<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Concesionaria extends Model
{
    protected $table = 'concesionarias';
    protected $guarded = ['id'];

    public function sucursales()
    {
        return $this->hasMany(Sucursal::class);
    }
}
