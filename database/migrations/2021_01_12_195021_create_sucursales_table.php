<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSucursalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sucursales', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->comment('Nombre de sucursal');
            $table->unsignedBigInteger('concesionaria_id');
            $table->integer('region');
            $table->integer('comuna');
            $table->string('calle', 45)->comment('Calle de la dirección de sucursal');
            $table->string('numero', 9)->comment('Nro de la dirección de sucursal');
            $table->timestamps();

            $table->foreign('concesionaria_id')->references('id')->on('Concesionarias')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sucursales');
    }
}
