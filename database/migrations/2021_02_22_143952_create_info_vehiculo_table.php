<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInfoVehiculoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('info_vehiculo', function (Blueprint $table) {
            $table->id();
            $table->integer('agnoFabricacion')->comment('Año de fabricación del vehículo');
            $table->integer('asientos')->nullable()->comment('Cantidad de asientos del vehículo');
            $table->decimal('carga', 5, 2)->nullable()->comment('Capacidad de carga del vehículo');
            $table->string('citModelo', 26)->nullable()->comment('Código informe técnico asociado al modelo');
            $table->string('color', 80)->comment('Color del vehículo');
            $table->string('combustible', 45)->comment('Tipo de combustible: NO INFORMADO, GASOLINA, DIESEL, ELECTRICO, ETC.');
            $table->string('marca', 26)->comment('Marca del vehículo');
            $table->string('modelo', 35)->comment('Modelo del vehículo');
            $table->string('nroChasis', 20)->comment('Número del chasis del vehículo');
            $table->string('nroMotor', 20)->comment('Número del motor del vehículo');
            $table->string('nroSerie', 20)->comment('Número del serie del vehículo');
            $table->string('nroVin', 20)->comment('Número del Vin del vehículo');
            $table->string('otraCarroceria', 1)->nullable()->comment('Para vehículos Pesados. Otra carrocería. En el caso que el tipo de carrocería no exista dentro del listado');
            $table->decimal('pbv', 5, 2)->comment('Peso Bruto Vehicular');
            $table->integer('potencia')->nullable()->comment('Para Camión y Tractocamión. Potencia del motor en los camiones');
            $table->integer('puertas')->nullable()->comment('Cantidad de puertas del vehículo');
            $table->string('terminacionPPU', 1)->nullable()->comment('Terminación de patente seleccionada. En caso de no tener, colocar letra A');
            $table->string('tipoCarroceria', 30)->nullable()->comment('Para vehículos Pesados. Corresponde a la carrocería del vehículo');
            $table->string('tipoPotencia', 6)->nullable()->comment('Para vehículos Pesados. Corresponde a la carrocería del vehículo');
            $table->string('tipoTraccion', 4)->nullable()->comment('Corresponde al tipo de tracción. Esto es equivalente a la disposición de ejes');
            $table->string('tipoVehiculo', 50)->comment('Tipo de vehículo: MOTO, BICIMOTO, ETC.');
            $table->string('tCarga', 1)->comment('Tipo de capacidad de carga - K:kilo, T:tonelada');
            $table->string('tPbv', 1)->comment('Tipo de Peso Bruto Vehicular - K:kilo, T:tonelada');
            

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
        Schema::dropIfExists('info_vehiculo');
    }
}
