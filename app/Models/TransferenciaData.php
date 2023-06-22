<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Symfony\Polyfill\Intl\Idn\Info;
use Illuminate\Support\Facades\DB;

class TransferenciaData extends Model
{
    protected $table = 'transferencias_data';

    public function transferencia(){
        return $this->belongsTo(Transferencia::class, 'transferencia_id','id');
    }

    public function tipoDocumento(){
        return $this->belongsTo(Tipo_Documento::class, 'tipo_documento_id','id');
    }

    public function naturaleza(){
        return $this->belongsTo(NaturalezaActo::class, 'naturaleza_id','id');
    }

    public function lugar(){
        return $this->belongsTo(Comuna::class,'lugar_id', 'Codigo');
    }

}