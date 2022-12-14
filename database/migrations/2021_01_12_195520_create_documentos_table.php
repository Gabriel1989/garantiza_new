<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200)->comment('Path del documento');
            $table->string('type', 5)->comment('Tipo (extensión) de documento');
            $table->string('description', 50)->comment('Descripción del documento');
            $table->unsignedBigInteger('solicitud_id');            
            $table->timestamp('added_at')->useCurrent();
            
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
        Schema::dropIfExists('documentos');
    }
}
