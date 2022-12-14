<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tipo_Carroceria extends Model
{
    protected $table = 'tipo_carrocerias';
    protected $fillable = ['name'];
    protected $guarded = ['id'];
}
