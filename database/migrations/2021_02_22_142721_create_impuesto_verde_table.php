<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImpuestoVerdeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('impuesto_verde', function (Blueprint $table) {
            $table->id();
            $table->string('cid', 30)->comment('Código de identificación de pago de impuesto verde');
            $table->string('cit', 26)->comment('Código informe técnico entregado por el Ministerio de transportes');
            $table->double('mImpuesto')->comment('Monto del impuesto pagado');
            $table->double('tFactura')->comment('Monto total de la factura');

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
        Schema::dropIfExists('impuesto_verde');
    }
}
