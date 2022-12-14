<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTipoTramitesSolicitudesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_tramites_solicitudes', function (Blueprint $table) {
            $table->unsignedBigInteger('tipoTramite_id');
            $table->unsignedBigInteger('solicitud_id');

            $table->foreign('tipoTramite_id')->references('id')->on('tipo_Tramites')->onDelete('restrict');
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
        Schema::dropIfExists('tipo_tramites_solicitudes');
    }
}
