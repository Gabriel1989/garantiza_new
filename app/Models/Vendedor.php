<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Vendedor extends Model
{
    protected $table = 'vendedores';    

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
        return DB::table('vendedores')
            ->where('vendedores.transferencia_id', '=', $id)
            ->select('vendedores.*')
            ->get();
    }
}
