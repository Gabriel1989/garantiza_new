<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NaturalezaDoc extends Model
{
    protected $table = 'naturalezas_x_tipodoc';    

    public function naturalezas(){
        return $this->belongsTo(NaturalezaActo::class,'naturaleza_id','id');
    }

    public function tipodocs(){
        return $this->belongsTo(Tipo_Documento::class,'tipo_documento_id','id');
    }
}