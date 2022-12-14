<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolicitudesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->id();
            $table->text('observaciones')->nullable();
            $table->unsignedBigInteger('estado_id')->comment('Estado de la solicitud');
            $table->unsignedBigInteger('user_id')->comment('Usuario que crea la solicitud');
            $table->unsignedBigInteger('sucursal_id')->comment('Sucursal en la que se crea la solicitud');
            $table->unsignedBigInteger('tipoVehiculos_id');
            
            $table->tinyInteger('creditoDirecto')->nullable()->comment('Indica si es crédito directo (boolean)');
            $table->tinyInteger('prenda')->nullable()->comment('Indica si es prenda (boolean)');
            $table->smallInteger('termino_1')->nullable()->comment('Selección de primer termino para PPU');
            $table->smallInteger('termino_2')->nullable()->comment('Selección de segundo termino para PPU');
            $table->smallInteger('termino_3')->nullable()->comment('Selección de tercer termino para PPU');
            
            $table->timestamps();

            $table->foreign('estado_id')->references('id')->on('estados')->onDelete('restrict');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('sucursal_id')->references('id')->on('sucursales')->onDelete('restrict');
            $table->foreign('tipoVehiculos_id')->references('id')->on('tipo_vehiculos')->onDelete('restrict');                
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solicitudes');
    }
}
