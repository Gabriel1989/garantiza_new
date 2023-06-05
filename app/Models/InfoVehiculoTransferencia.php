<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InfoVehiculoTransferencia extends Model
{
    protected $table = 'info_vehiculo_transferencia';

    public function transferencia(){
        return $this->belongsTo(Transferencia::class,'transferencia_id','id');
    }
}
