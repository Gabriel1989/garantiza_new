<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paras', function (Blueprint $table) {
            $table->id();
            $table->string('rut', 15)->comment('Rut de Para');
            $table->string('nombre', 63)->comment('Nombre de Para');
            $table->string('aPaterno', 20)->comment('Apellido Paterno de Para');
            $table->string('aMaterno', 20)->comment('Apellido Materno de Para');
            $table->string('tipo', 1)->comment('Tipo de persona - N:Natural; J:Jurídico; E:Extranjero; O:Comunidad');
            $table->string('calle', 45)->comment('Calle de la dirección de Para');
            $table->string('numero', 9)->comment('Nro de la dirección de Para');
            $table->string('rDomicilio', 45)->nullable()->comment('Resto de la dirección de Para');
            $table->integer('comuna')->comment('Comuna de la dirección de Para');
            $table->string('email', 100)->comment('Email de Para');
            $table->string('telefono', 10)->comment('Email de Para');

            $table->unsignedBigInteger('solicitud_id');
            $table->timestamps();

            $table->foreign('solicitud_id')->references('id')->on('solicitudes')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paras');
    }
}
