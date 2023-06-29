<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    protected $table = 'documentos';
    public $timestamps = false;

    public function documento_rc_coteja(){
        return $this->hasOne(EnvioDocumentoCotejaRC::class, 'documento_id','id');
    }
}
