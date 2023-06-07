<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NaturalezaActo extends Model
{
    protected $table = 'naturalezas_actos';    

    public function naturalezaDoc(){
        return $this->hasMany(NaturalezaDoc::class, 'naturaleza_id','id');
    }
}