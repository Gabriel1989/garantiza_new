<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Estipulante extends Model
{
    protected $table = 'estipulantes';

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
                           'transferencia_id',
                        ];

    protected $guarded = ['id'];

    public static function getSolicitud($id){
        return DB::table('estipulantes')
            ->where('estipulantes.transferencia_id', '=', $id)
            ->select('estipulantes.*')
            ->get();
    }
}
