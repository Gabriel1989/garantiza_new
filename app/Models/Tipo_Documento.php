<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tipo_Documento extends Model
{
    protected $table = 'tipo_documentos';

    protected $fillable = ['name', 'extension'];
    protected $guarded = ['id'];
}
