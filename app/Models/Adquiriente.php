<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Adquiriente extends Model
{
    protected $table = 'adquirientes';

    protected $fillable = ['rut', 
                           'nombre', 
                           'aPaterno', 
                           'aMaterno', 
                           'tipo', 
                           'calle', 
                           'numero', 
                           'rDomicilio', 
                           'comuna',
                           'telefono',
                           'email',
                           'solicitud_id',
                        ];

    protected $guarded = ['id'];
    
    public static function getSolicitud($id){
        return DB::table('adquirientes')
            ->where('adquirientes.solicitud_id', '=', $id)
            ->select('adquirientes.*')
            ->get();
    }
}
