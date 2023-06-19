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
//dd($solicitud->documentos);
//dd($solicitud->paras)
//dd($solicitud);
?>
<div class="pdf" style="width:600px;">
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
            <span style="float:right;font-weight:bold;">Solicitud de Inscripción N°{{ $solicitud->id }} <br><br>
                {{ $fecha_spanish }}</span>

            <h4 style="text-align:center;">COMPROBANTE DE SOLICITUD PRIMERA INSCRIPCIÓN GARANTIZA</h4>

            <div class="row">
                <div class="col-md-6" style="display: inline-block; width: 48%; vertical-align: top;">
                    <fieldset style="margin-right: 10px;">
                        <legend>DATOS DE LA SOLICITUD</legend>
                        @if (isset($solicitud->region))
                            <div class="form-group">
                                <label>Region:</label>
                                {{ $solicitud->region->nombre }}
                            </div>
                        @endif

                        <div class="form-group">
                            <label>Concesionaria:</label>
                            {{ $solicitud->usuario->concesionaria->name }}
                        </div>

                        <div class="form-group">
                            <label>Sucursal:</label>
                            {{ $solicitud->sucursal->name }}
                        </div>

                        <div class="form-group">
                            <label>Usuario:</label>
                            {{ $solicitud->usuario->name }} ({{ $solicitud->usuario->email }})
                        </div>
                    </fieldset>
                </div>

                <div class="col-md-6" style="display: inline-block; width: 48%; vertical-align: top;">
                    <fieldset>
                        <legend>DATOS DE LA FACTURA</legend>
                        <div class="form-group">
                            <label>Razón Social:</label>
                            {{ strtoupper($solicitud->datos_factura->razon_social_emisor) }}
                        </div>

                        <div class="form-group">
                            <label>Rut emisor:</label>
                            {{ number_format($solicitud->datos_factura->rut_emisor, 0, ',', '.') }}-{{ strtoupper(Funciones::calcularDigitoVerificador($solicitud->datos_factura->rut_emisor)) }}
                        </div>

                        <div class="form-group">
                            <label>Rut receptor:</label>
                            {{ number_format($solicitud->datos_factura->rut_receptor, 0, ',', '.') }}-{{ strtoupper(Funciones::calcularDigitoVerificador($solicitud->datos_factura->rut_receptor)) }}
                        </div>

                        <div class="form-group">
                            <label>N° Factura:</label>
                            {{ strtoupper($solicitud->datos_factura->num_factura) }}
                        </div>

                        <div class="form-group">
                            <label>Ciudad:</label>
                            {{ strtoupper($solicitud->datos_factura->ciudad) }}
                        </div>

                        <div class="form-group">
                            <label>Comuna:</label>
                            {{ strtoupper($solicitud->datos_factura->comuna) }}
                        </div>

                        <div class="form-group">
                            <label>Fecha emisión:</label>
                            {{ date('d-m-Y', strtotime($solicitud->datos_factura->fecha_emision)) }}
                        </div>

                        <div class="form-group">
                            <label>Monto total factura:</label>
                            ${{ number_format($solicitud->datos_factura->monto_total_factura, 0, ',', '.') }}
                        </div>

                        <div class="form-group">
                            <label>IVA:</label>
                            ${{ number_format($solicitud->datos_factura->monto_total_factura - floor($solicitud->datos_factura->monto_total_factura / 1.19), 1, ',', '.') }}
                        </div>


                    </fieldset>
                    
                </div>
            </div>

            <div class="row">
                <div class="col-md-6" style="display: inline-block; width: 48%; vertical-align: top;">
                    <fieldset style="margin-right: 10px;">
                        <legend>DATOS DEL VEHÍCULO</legend>
                        @if (isset($solicitud->tipo_vehiculo))
                            <div class="form-group">
                                <label>Tipo Vehículo:</label>
                                {{ $solicitud->tipo_vehiculo->name }}
                            </div>
                        @endif

                        <div class="form-group">
                            <label>N° Chasis:</label>
                            {{ $solicitud->datos_factura->nro_chasis }}
                        </div>

                        <div class="form-group">
                            <label>N° Vin:</label>
                            {{ $solicitud->datos_factura->nro_vin }}
                        </div>

                        <div class="form-group">
                            <label>N° Serie:</label>
                            {{ $solicitud->datos_factura->nro_serie }}
                        </div>

                        <div class="form-group">
                            <label>Motor:</label>
                            {{ $solicitud->datos_factura->motor }}
                        </div>

                        <div class="form-group">
                            <label>Marca:</label>
                            {{ $solicitud->datos_factura->marca }}
                        </div>

                        <div class="form-group">
                            <label>Modelo:</label>
                            {{ $solicitud->datos_factura->modelo }}
                        </div>

                        <div class="form-group">
                            <label>Peso Bruto Vehícular:</label>
                            {{ $solicitud->datos_factura->peso_bruto_vehicular }}
                        </div>

                        <div class="form-group">
                            <label>Año:</label>
                            {{ $solicitud->datos_factura->agno_fabricacion }}
                        </div>

                        <div class="form-group">
                            <label>Tipo Combustible:</label>
                            {{ $solicitud->datos_factura->tipo_combustible }}
                        </div>

                        <div class="form-group">
                            <label>Color:</label>
                            {{ $solicitud->datos_factura->color }}
                        </div>

                        <div class="form-group">
                            <label>Tipo Carga:</label>
                            {{ $solicitud->datos_factura->tipo_carga }}
                        </div>

                        <div class="form-group">
                            <label>Tipo Peso Bruto Vehícular:</label>
                            {{ $solicitud->datos_factura->tipo_pbv }}
                        </div>

                        <div class="form-group">
                            <label>Código CIT:</label>
                            {{ $solicitud->datos_factura->codigo_cit }}
                        </div>

                        <div class="form-group">
                            <label>Código CID:</label>
                            {{ $solicitud->datos_factura->codigo_cid }}
                        </div>
                    </fieldset>
                </div>

                <div class="col-md-6" style="display: inline-block; width: 48%; vertical-align: top;">
                    <fieldset>
                        <legend>DATOS DE ADQUIRIENTE</legend>
                        <div class="form-group">
                            <label>RUT:</label>
                            {{ $solicitud->adquiriente->rut }}
                        </div>

                        <div class="form-group">
                            <label>Nombre:</label>
                            {{ $solicitud->adquiriente->nombre }}
                        </div>

                        <div class="form-group">
                            <label>Apellido Paterno:</label>
                            {{ $solicitud->adquiriente->aPaterno }}
                        </div>

                        <div class="form-group">
                            <label>Apellido Materno:</label>
                            {{ $solicitud->adquiriente->aMaterno }}
                        </div>

                        <div class="form-group">
                            <label>Tipo Persona:</label>
                            <?php
                            switch ($solicitud->adquiriente->tipo) {
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
                            {{ $solicitud->adquiriente->calle }}
                        </div>

                        <div class="form-group">
                            <label>N°:</label>
                            {{ $solicitud->adquiriente->numero }}
                        </div>

                        <div class="form-group">
                            <label>Info adicional:</label>
                            {{ $solicitud->adquiriente->rDomicilio }}
                        </div>

                        <div class="form-group">
                            <label>Comuna:</label>
                            {{ $solicitud->adquiriente->comunas->Nombre }}
                        </div>

                        <div class="form-group">
                            <label>Email:</label>
                            {{ $solicitud->adquiriente->email }}
                        </div>

                        <div class="form-group">
                            <label>Teléfono:</label>
                            {{ $solicitud->adquiriente->telefono }}
                        </div>
                    </fieldset>
                </div>
            </div>

            <div class="row">
                @if ($solicitud->paras != null)
                <div class="col-md-6" style="display: inline-block; width: 48%; vertical-align: top;">
                    <fieldset>
                        <legend>DATOS DE COMPRA PARA</legend>
                        <div class="form-group">
                            <label>RUT:</label>
                            {{ $solicitud->paras->rut }}
                        </div>

                        <div class="form-group">
                            <label>Nombre:</label>
                            {{ $solicitud->paras->nombre }}
                        </div>

                        <div class="form-group">
                            <label>Apellido Paterno:</label>
                            {{ $solicitud->paras->aPaterno }}
                        </div>

                        <div class="form-group">
                            <label>Apellido Materno:</label>
                            {{ $solicitud->paras->aMaterno }}
                        </div>

                        <div class="form-group">
                            <label>Tipo Persona:</label>
                            <?php
                            switch ($solicitud->paras->tipo) {
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
                            {{ $solicitud->paras->calle }}
                        </div>

                        <div class="form-group">
                            <label>N°:</label>
                            {{ $solicitud->paras->numero }}
                        </div>

                        <div class="form-group">
                            <label>Info adicional:</label>
                            {{ $solicitud->paras->rDomicilio }}
                        </div>

                        <div class="form-group">
                            <label>Comuna:</label>
                            {{ $solicitud->paras->comunas->Nombre }}
                        </div>

                        <div class="form-group">
                            <label>Email:</label>
                            {{ $solicitud->paras->email }}
                        </div>

                        <div class="form-group">
                            <label>Teléfono:</label>
                            {{ $solicitud->paras->telefono }}
                        </div>
                    </fieldset>
                </div>
                @endif

                <div class="col-md-6" style="display: inline-block; width: 48%; vertical-align: top;">
                    <fieldset>
                        <legend>SERVICIOS ADICIONALES</legend>
                        <div class="form-group">
                            <label>TAG:</label>
                            {{ $solicitud->incluyeTAG == null || $solicitud->incluyeTAG == 0 ? 'NO' : 'SI' }}
                        </div>

                        <div class="form-group">
                            <label>SOAP:</label>
                            {{ $solicitud->incluyeSOAP == null || $solicitud->incluyeSOAP == 0 ? 'NO' : 'SI' }}
                        </div>

                        <div class="form-group">
                            <label>Permiso de circulación:</label>
                            {{ $solicitud->incluyePermiso == null || $solicitud->incluyePermiso == 0 ? 'NO' : 'SI' }}
                        </div>
                    </fieldset>
                </div>
                <br>
                <br>
            </div>

            <div class="row">
                @if($solicitud->solicitud_rc != null)
                    <div class="col-md-6" style="display: inline-block; width: 48%; vertical-align: top;">
                        <fieldset>
                            <legend>DATOS DE LA INSCRIPCIÓN</legend>
                            <div class="form-group">
                                <label>PPU:</label>
                                {{ $solicitud->solicitud_rc->ppu }}
                            </div>

                            <div class="form-group">
                                <label>Oficina:</label>
                                {{ $solicitud->solicitud_rc->oficina }}
                            </div>

                            <div class="form-group">
                                <label>Número Solicitud:</label>
                                {{ $solicitud->solicitud_rc->numeroSol }}
                            </div>

                            <div class="form-group">
                                <label>Fecha:</label>
                                {{ date('d-m-Y', strtotime($solicitud->solicitud_rc->fecha)) }}
                            </div>

                            <div class="form-group">
                                <label>Hora:</label>
                                {{ date('H:i', strtotime($solicitud->solicitud_rc->hora)) }}
                            </div>
                        </fieldset>
                        
                    </div>
                @endif

                @if($solicitud->documentos != null)
                    <div class="col-md-6" style="display: inline-block; width: 48%; vertical-align: top;">
                        <fieldset>
                            <legend>DOCUMENTOS DE LA SOLICITUD</legend>
                            @foreach($solicitud->documentos as $docs)
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


            <div class="row">
                @if ($solicitud->limitacion != null)
                    <div class="col-md-6" style="display: inline-block; width: 48%; vertical-align: top;">
                        <fieldset>
                            <legend>DATOS DE LA LIMITACIÓN/PROHIBICIÓN</legend>
                            <div class="form-group">
                                <label>Acreedor:</label>
                                {{ $solicitud->limitacion->acreedor->nombre }}
                            </div>

                            <div class="form-group">
                                <label>Folio:</label>
                                {{ $solicitud->limitacion->folio }}
                            </div>

                            <div class="form-group">
                                <label>Autorizante:</label>
                                {{ $solicitud->limitacion->autorizante }}
                            </div>

                            <div class="form-group">
                                <label>Tipo Documento:</label>
                                {{ $solicitud->limitacion->tipodocumento->name }}
                            </div>
                        </fieldset>
                    </div>
                @endif

            </div>


        </div>
    </div>
</div>
