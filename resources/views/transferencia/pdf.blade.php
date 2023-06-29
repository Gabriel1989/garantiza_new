<?php
setlocale(LC_TIME, 'es_CL');
?>
<style>
    .page-break {
        page-break-after: always;
    }
</style>
<?php

use App\Helpers\Funciones;
//dd($transferencia);
?>
<div class="pdf" style="width:700px;">
    <div class="row">
        <div class="col-md-12">
            <img style="width:150px;height:50px;float:left;"
                src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/img/LogoGarantiza.jpeg')) }}"
                class="img-responsive" />
        </div>
        <div class="col-md-12">
            <?php
            $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
            $fecha = \Carbon\Carbon::parse(date('d-m-Y'));
            $mes = $meses[$fecha->format('n') - 1];
            $fecha_spanish = $fecha->format('d') . ' de ' . $mes . ' de ' . $fecha->format('Y');
            ?>
            <span style="float:right;font-weight:bold;">Solicitud de Transferencia N°{{ $transferencia->id }} <br><br>
                {{ $fecha_spanish }}</span>
        </div>
        <br>
        <br>
        <div class="col-md-12">
            <div style="text-align:center;width:100%;">
                <h4>COMPROBANTE DE SOLICITUD TRANSFERENCIA VEHÍCULOS GARANTIZA</h4>
            </div>

            <div class="row">
                <div class="col-md-6" style="display: inline-block; width: 48%; vertical-align: top;">
                    <fieldset style="margin-right: 10px;">
                        <legend>DATOS DE LA SOLICITUD</legend>
                        <div class="form-group">
                            <label>Notaria:</label>
                            {{ $transferencia->notaria->name }}
                        </div>

                        <div class="form-group">
                            <label>Usuario:</label>
                            {{ $transferencia->usuario->name }} ({{ $transferencia->usuario->email }})
                        </div>
                    </fieldset>
                </div>

                @if($transferencia->vehiculo != null)
                <div class="col-md-6" style="display: inline-block; width: 48%; vertical-align: top;">
                    <fieldset>
                        <legend>DATOS DEL VEHÍCULO</legend>
                        <div class="form-group">
                            <label>Tipo Vehículo:</label>
                            {{ $transferencia->vehiculo->tipo_vehiculo }}
                        </div>

                        <div class="form-group">
                            <label>PPU:</label>
                            {{ $transferencia->vehiculo->ppu }}-@if(isset($transferencia->data_transferencia)){{$transferencia->data_transferencia->dv_ppu}}@endif
                        </div>

                        <div class="form-group">
                            <label>Año:</label>
                            {{ $transferencia->vehiculo->anio }}
                        </div>

                        <div class="form-group">
                            <label>Marca:</label>
                            {{ $transferencia->vehiculo->marca }}
                        </div>

                        <div class="form-group">
                            <label>Modelo:</label>
                            {{ $transferencia->vehiculo->modelo }}
                        </div>

                        <div class="form-group">
                            <label>Chasis:</label>
                            {{ $transferencia->vehiculo->chasis }}
                        </div>

                        <div class="form-group">
                            <label>Motor:</label>
                            {{ $transferencia->vehiculo->motor }}
                        </div>

                        <div class="form-group">
                            <label>Serie:</label>
                            {{ $transferencia->vehiculo->serie }}
                        </div>

                        <div class="form-group">
                            <label>VIN:</label>
                            {{ $transferencia->vehiculo->vin }}
                        </div>

                        <div class="form-group">
                            <label>Color:</label>
                            {{ $transferencia->vehiculo->color }}
                        </div>
                    </fieldset>
                </div>
                @endif
            </div>

            <div class="row">
                @if($transferencia->comprador != null)
                <div class="col-md-6" style="display: inline-block; width: 48%; vertical-align: top;">
                    <fieldset style="margin-right: 10px;">
                        <legend>DATOS DEL COMPRADOR</legend>
                        <div class="form-group">
                            <label>RUT:</label>
                            {{ $transferencia->comprador->rut }}
                        </div>
                        <div class="form-group">
                            <label>Nombre:</label>
                            {{ $transferencia->comprador->nombre }}
                        </div>

                        <div class="form-group">
                            <label>Apellido Paterno:</label>
                            {{ $transferencia->comprador->aPaterno }}
                        </div>

                        <div class="form-group">
                            <label>Apellido Materno:</label>
                            {{ $transferencia->comprador->aMaterno }}
                        </div>

                        <div class="form-group">
                            <label>Tipo Persona:</label>
                            <?php
                            switch ($transferencia->comprador->tipo) {
                                case 'N':
                                    echo 'Natural';
                                    break;
                                case 'J':
                                    echo 'Jurídica';
                                    break;
                                case 'E':
                                    echo 'Extranjero';
                                    break;
                                case 'O':
                                    echo 'Comunidad';
                                    break;
                            }
                            
                            ?>
                        </div>

                        <div class="form-group">
                            <label>Dirección:</label>
                            {{ $transferencia->comprador->calle }}
                        </div>

                        <div class="form-group">
                            <label>N°:</label>
                            {{ $transferencia->comprador->numero }}
                        </div>

                        <div class="form-group">
                            <label>Info adicional:</label>
                            {{ $transferencia->comprador->rDomicilio }}
                        </div>

                        <div class="form-group">
                            <label>Comuna:</label>
                            {{ $transferencia->comprador->comunas->Nombre }}
                        </div>

                        <div class="form-group">
                            <label>Email:</label>
                            {{ $transferencia->comprador->email }}
                        </div>

                        <div class="form-group">
                            <label>Teléfono:</label>
                            {{ $transferencia->comprador->telefono }}
                        </div>
                    </fieldset>
                </div>
                @endif

                @if($transferencia->vendedor != null)
                <div class="col-md-6" style="display: inline-block; width: 48%; vertical-align: top;">
                    <fieldset>
                        <legend>DATOS DEL VENDEDOR</legend>
                        <div class="form-group">
                            <label>RUT:</label>
                            {{ $transferencia->vendedor->rut }}
                        </div>

                        <div class="form-group">
                            <label>Nombre:</label>
                            {{ $transferencia->vendedor->nombre }}
                        </div>

                        <div class="form-group">
                            <label>Apellido Paterno:</label>
                            {{ $transferencia->vendedor->aPaterno }}
                        </div>

                        <div class="form-group">
                            <label>Apellido Materno:</label>
                            {{ $transferencia->vendedor->aMaterno }}
                        </div>

                        <div class="form-group">
                            <label>Tipo Persona:</label>
                            <?php
                            switch ($transferencia->vendedor->tipo) {
                                case 'N':
                                    echo 'Natural';
                                    break;
                                case 'J':
                                    echo 'Jurídica';
                                    break;
                                case 'E':
                                    echo 'Extranjero';
                                    break;
                                case 'O':
                                    echo 'Comunidad';
                                    break;
                            }
                            
                            ?>
                        </div>

                        <div class="form-group">
                            <label>Email:</label>
                            {{ $transferencia->vendedor->email }}
                        </div>
                    </fieldset>

                </div>
                @endif
            </div>

            <div class="row">
                @if($transferencia->data_transferencia != null)
                <div class="col-md-6" style="display: inline-block; width: 48%; vertical-align: top;">
                    <fieldset style="margin-right: 10px;">
                        <legend>DATOS DE LA TRANSFERENCIA</legend>
                        <div class="form-group">
                            <label>Tipo Documento transferencia:</label>
                            {{ $transferencia->data_transferencia->tipoDocumento->name }}
                        </div>

                        <div class="form-group">
                            <label>Naturaleza del acto:</label>
                            {{ $transferencia->data_transferencia->naturaleza->nombre }}
                        </div>

                        <div class="form-group">
                            <label>N° Documento:</label>
                            {{ $transferencia->data_transferencia->num_doc }}
                        </div>

                        @if($transferencia->data_transferencia->rut_emisor != '')
                        <div class="form-group">
                            <label>RUT emisor:</label>

                            {{ number_format($transferencia->data_transferencia->rut_emisor,0,',','.') }}-{{ Funciones::calcularDigitoVerificador($transferencia->data_transferencia->rut_emisor) }}
                        </div>
                        @endif
                        <div class="form-group">
                            <label>Fecha emisión documento:</label>
                            {{ date('d-m-Y', strtotime($transferencia->data_transferencia->fecha_emision)) }}
                        </div>

                        <div class="form-group">
                            <label>Lugar:</label>
                            {{ strtoupper($transferencia->data_transferencia->lugar->Nombre) }}
                        </div>

                        <div class="form-group">
                            <label>Total venta:</label>
                            {{ $transferencia->data_transferencia->moneda }}{{ number_format($transferencia->data_transferencia->total_venta,0,',','.') }}
                        </div>

                        <div class="form-group">
                            <label>Kilometraje vehículo:</label>
                            {{ number_format($transferencia->data_transferencia->kilometraje,0,',','.') }} km
                        </div>
                        <h4>Datos del pago de impuesto a la transferencia</h4>
                        <div class="form-group">
                            <label>Código CID del pago:</label>
                            {{ strtoupper($transferencia->data_transferencia->codigo_cid) }}
                        </div>
                        <div class="form-group">
                            <label>Monto pagado:</label>
                            ${{ number_format($transferencia->data_transferencia->monto_impuesto,0,',','.') }}
                        </div>
                    </fieldset>

                </div>
                @endif

                @if($transferencia->estipulante != null)
                <div class="col-md-6" style="display: inline-block; width: 48%; vertical-align: top;">
                    <fieldset style="margin-right: 10px;">
                        <legend>DATOS DEL ESTIPULANTE</legend>
                        <div class="form-group">
                            <label>RUT:</label>
                            {{ $transferencia->estipulante->rut }}
                        </div>
                        <div class="form-group">
                            <label>Nombre:</label>
                            {{ $transferencia->estipulante->nombre }}
                        </div>

                        <div class="form-group">
                            <label>Apellido Paterno:</label>
                            {{ $transferencia->estipulante->aPaterno }}
                        </div>

                        <div class="form-group">
                            <label>Apellido Materno:</label>
                            {{ $transferencia->estipulante->aMaterno }}
                        </div>

                        <div class="form-group">
                            <label>Tipo Persona:</label>
                            <?php
                            switch ($transferencia->estipulante->tipo) {
                                case 'N':
                                    echo 'Natural';
                                    break;
                                case 'J':
                                    echo 'Jurídica';
                                    break;
                                case 'E':
                                    echo 'Extranjero';
                                    break;
                                case 'O':
                                    echo 'Comunidad';
                                    break;
                            }
                            
                            ?>
                        </div>

                        <div class="form-group">
                            <label>Dirección:</label>
                            {{ $transferencia->estipulante->calle }}
                        </div>

                        <div class="form-group">
                            <label>N°:</label>
                            {{ $transferencia->estipulante->numero }}
                        </div>

                        <div class="form-group">
                            <label>Info adicional:</label>
                            {{ $transferencia->estipulante->rDomicilio }}
                        </div>

                        <div class="form-group">
                            <label>Comuna:</label>
                            {{ $transferencia->estipulante->comunas->Nombre }}
                        </div>

                        <div class="form-group">
                            <label>Email:</label>
                            {{ $transferencia->estipulante->email }}
                        </div>

                        <div class="form-group">
                            <label>Teléfono:</label>
                            {{ $transferencia->estipulante->telefono }}
                        </div>

                        <div class="form-group">
                            <label>Compra con crédito:</label>
                            {{ ($transferencia->estipulante->esProhibicion == 1)? 'SI' : 'NO' }}
                        </div>
                    </fieldset>
                </div>
                @endif
            </div>

            @if($transferencia->transferencia_rc != null || sizeof($transferencia->documentos) > 0 || $transferencia->limitacion != null || 
            $transferencia->limitacion_rc != null)
            <div class="page-break"></div>
            <div class="row">
                @if($transferencia->transferencia_rc != null)
                    <div class="col-md-6" style="display: inline-block; width: 48%; vertical-align: top;">
                        <fieldset>
                            <legend>DATOS DE LA TRANSFERENCIA ENVIADA A RC </legend>
                            <div class="form-group">
                                <label>PPU:</label>
                                {{ $transferencia->transferencia_rc->ppu }}
                            </div>

                            <div class="form-group">
                                <label>Oficina:</label>
                                {{ $transferencia->transferencia_rc->oficina }}
                            </div>

                            <div class="form-group">
                                <label>Número Solicitud:</label>
                                {{ $transferencia->transferencia_rc->numeroSol }}
                            </div>

                            <div class="form-group">
                                <label>Fecha:</label>
                                {{ date('d-m-Y', strtotime($transferencia->transferencia_rc->fecha)) }}
                            </div>

                            <div class="form-group">
                                <label>Hora:</label>
                                {{ date('H:i', strtotime($transferencia->transferencia_rc->hora)) }}
                            </div>
                        </fieldset>
                        
                    </div>
                @endif

                @if(sizeof($transferencia->documentos) > 0)
                    <div class="col-md-6" style="display: inline-block; width: 48%; vertical-align: top;">
                        <fieldset>
                            <legend>DOCUMENTOS DE LA SOLICITUD</legend>
                            @foreach($transferencia->documentos as $docs)
                                <div class="form-group">
                                    <label>Nombre:</label>
                                    {{ str_replace('public/','',$docs->name) }}
                                </div>

                                <div class="form-group">
                                    <label>Tipo:</label>
                                    {{ $docs->description }}
                                </div>

                            @endforeach
                        </fieldset>
                    </div>
                @endif
            </div>

            @if ($transferencia->limitacion != null)
            <div class="row">
                @if ($transferencia->limitacion != null)
                    <div class="col-md-6" style="display: inline-block; width: 48%; vertical-align: top;">
                        <fieldset>
                            <legend>DATOS DE LA LIMITACIÓN/PROHIBICIÓN</legend>

                            <div class="form-group">
                                <label>Acreedor:</label>
                                {{ $transferencia->limitacion->acreedor->nombre }}
                            </div>

                            <div class="form-group">
                                <label>Folio:</label>
                                {{ $transferencia->limitacion->folio }}
                            </div>

                            <div class="form-group">
                                <label>Autorizante:</label>
                                {{ $transferencia->limitacion->autorizante }}
                            </div>

                            <div class="form-group">
                                <label>Tipo Documento:</label>
                                {{ $transferencia->limitacion->tipodocumento->name }}
                            </div>
                        </fieldset>
                    </div>
                @endif

                @if($transferencia->limitacion_rc != null)
                    <div class="col-md-6" style="display: inline-block; width: 48%; vertical-align: top;">
                        <fieldset>
                            <legend>DATOS DE LA LIMITACIÓN/PROHIBICIÓN ENVIADA A RC</legend>
                            <div class="form-group">
                                <label>PPU:</label>
                                {{ $transferencia->limitacion_rc->ppu }}
                            </div>

                            <div class="form-group">
                                <label>Oficina:</label>
                                {{ $transferencia->limitacion_rc->oficina }}
                            </div>

                            <div class="form-group">
                                <label>Número Solicitud:</label>
                                {{ $transferencia->limitacion_rc->numSol }}
                            </div>

                            <div class="form-group">
                                <label>Fecha:</label>
                                {{ date('d-m-Y', strtotime($transferencia->limitacion_rc->fecha)) }}
                            </div>

                            <div class="form-group">
                                <label>Hora:</label>
                                {{ date('H:i', strtotime($transferencia->limitacion_rc->hora)) }}
                            </div>

                        </fieldset>
                    </div>
                @endif
            </div>
            @endif

            <div style="position:absolute;top:550;left:330;border: 3px solid #000;;width:165px;">
                <span style="text-align:center;margin-left:15px;font-weight:bold;font-size:20px;white-space:nowrap;">GARANTIZA</span>
                <br>
                <span style="font-size:9px;text-align:center;margin-left:15px;font-weight:bold;">MERCED 280, PISO 6 SANTIAGO</span>
                <br>
                <span style="border: 2px solid #000;text-align:center;margin-left:15px;padding:0px 15px 0px 15px;font-weight:bold;font-size:20px;">{{date("d-m-Y")}}</span>
                <br>
                <span style="font-size:14px;text-align:center;margin-left:15px;font-weight:bold;white-space:pre;">CONVENIO STEV</span>
            </div>

            <div style="float:right;position: absolute;top:650;">
                MERCED N° 280, PISO 6
                <br>
                STGO. CENTRO
                <br>
                MESA CENTRAL (56 2) 2763 5000
                <br>
                www.garantiza.cl
            </div>
            @else

            <div style="position:absolute;top:550;left:330;border: 3px solid #000;;width:165px;">
                <span style="text-align:center;margin-left:15px;font-weight:bold;font-size:20px;white-space:nowrap;">GARANTIZA</span>
                <br>
                <span style="font-size:9px;text-align:center;margin-left:15px;font-weight:bold;">MERCED 280, PISO 6 SANTIAGO</span>
                <br>
                <span style="border: 2px solid #000;text-align:center;margin-left:15px;padding:0px 15px 0px 15px;font-weight:bold;font-size:20px;">{{date("d-m-Y")}}</span>
                <br>
                <span style="font-size:14px;text-align:center;margin-left:15px;font-weight:bold;white-space:pre;">CONVENIO STEV</span>
            </div>

            <div style="float:right;position: absolute;top:650;">
                MERCED N° 280, PISO 6
                <br>
                STGO. CENTRO
                <br>
                MESA CENTRAL (56 2) 2763 5000
                <br>
                www.garantiza.cl
            </div>


            @endif
        </div>
    </div>
</div>
