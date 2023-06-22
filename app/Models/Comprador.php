<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Comprador extends Model{
    protected $table = 'compradores';

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
        return DB::table('compradores')
            ->where('compradores.transferencia_id', '=', $id)
            ->select('compradores.*')
            ->get();
    }

    public function comunas(){
        return $this->belongsTo(Comuna::class, 'comuna','Codigo');
    }
}