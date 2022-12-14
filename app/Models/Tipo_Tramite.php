<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tipo_Tramite extends Model
{
    protected $table = 'tipo_tramites';

    protected $fillable = ['name'];
    protected $guarded = ['id'];
}
