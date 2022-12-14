<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConcesionariasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('concesionarias', function (Blueprint $table) {
            $table->id();
            $table->string('name', 45)->comment('Nombre de la Concecionaria');
            $table->integer('rut')->nullable()->comment('Rut de la Concesionaria');
            $table->char('dv', 1)->nullable()->comment('Digito verificador del rut de la Concesionaria');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('concesionarias');
    }
}
