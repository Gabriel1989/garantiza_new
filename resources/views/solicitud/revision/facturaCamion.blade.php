@section('styles')
    <!-- FlowChart CSS -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset("assets/$themes/vendor/plugins/flowchart/jquery.flowchart.min.css") }}">
    <style>
        .flowchart-example-container {
            width: 800px;
            height: 500px;
            background: white;
            border: 1px solid #BBB;
            margin-bottom: 10px;
        }
    </style>
@endsection

<?php

use App\Models\NumDispoEjes;
use App\Models\Reingreso;
$reingreso = Reingreso::where('solicitud_id',$id)->first();
?>

@include('includes.form-error-message')
<form method="post" id="formFacturaCamion"
    old-action="{{ route('solicitud.updateRevisionFacturaCamion', ['id' => $id]) }}" role="form"
    class="form-horizontal form-revision">
    @csrf
    @method('post')
    <div class="panel panel-info panel-border top">
        <div class="panel-heading">
            <span class="panel-title">Ingreso de Solicitud N° {{ $id }} - Datos de Factura</span>
        </div>
        <div class="panel-body">
            <div class="form-group">

                <div class="col-lg-6">
                    <div class="panel">
                        <div class="panel-heading">
                            <span class="panel-title">
                                <span class="glyphicon glyphicon-cog hidden"></span>Información del Vehículo Pesado
                            </span>
                            <br>
                            <span class="panel-title" style="color:#f00">(*) Datos obligatorios</span>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <div class="row">
                                    <label for="agnoFabricacion" class="col-lg-3 control-label "><span class="panel-title" style="color:#f00">(*) </span>Año
                                        Fabricación:</label>
                                    <label class="col-lg-2">
                                        <input type="input" name="agnoFabricacion" id="agnoFabricacion"
                                            value="{{ $header->AnnioFabricacion }}" class="form-control"
                                            placeholder="{{ now()->year }}" required>
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="asientos" class="col-lg-3 control-label ">Asientos:</label>
                                    <label class="col-lg-2">
                                        <input type="input" name="asientos" id="asientos"
                                            value="{{ $header->Asientos }}" class="form-control" placeholder="5">
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="carga" class="col-lg-3 control-label "><span class="panel-title" style="color:#f00">(*) </span>Carga:</label>
                                    <label class="col-lg-2">
                                        <input type="input" name="carga" id="carga" class="form-control"
                                            placeholder="1600" required>
                                    </label>

                                </div>

                                <div class="row">
                                    <label for="tipo_carga" class="col-lg-3 control-label "><span class="panel-title" style="color:#f00">(*) </span>Tipo Carga:</label>
                                    <label class="col-lg-2">
                                        <input type="input" name="tCarga" id="tipo_carga"
                                            value="{{ $header->TipoCarga }}" class="form-control" placeholder="K"
                                            required>
                                    </label>
                                </div>

                                <div class="row">
                                    <label for="color" class="col-lg-3 control-label "><span class="panel-title" style="color:#f00">(*) </span>Color:</label>
                                    <label class="col-lg-9">
                                        <input type="input" name="color" id="color" value="{{ $header->Color }}"
                                            class="form-control" placeholder="BLANCO" required>
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="combustible" class="col-lg-3 control-label "><span class="panel-title" style="color:#f00">(*) </span>Combustible:</label>
                                    <label class="col-lg-9">
                                        <input type="input" name="combustible" id="combustible"
                                            value="{{ $header->TipoCombustible }}" class="form-control"
                                            placeholder="DIESEL" required>
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="marca" class="col-lg-3 control-label "><span class="panel-title" style="color:#f00">(*) </span>Marca:</label>
                                    <label class="col-lg-9">
                                        <input type="input" name="marca" id="marca" value="{{ $header->Marca }}"
                                            class="form-control" placeholder="SCANIA" required>
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="modelo" class="col-lg-3 control-label "><span class="panel-title" style="color:#f00">(*) </span>Modelo:</label>
                                    <label class="col-lg-9">
                                        <input type="input" name="modelo" id="modelo"
                                            value="{{ $header->Modelo }}" class="form-control" placeholder="G-450"
                                            required>
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="nroChasis" class="col-lg-3 control-label "><span class="panel-title" style="color:#f00">(*) </span>N° de Chasis:</label>
                                    <label class="col-lg-9">
                                        <input type="input" name="nroChasis" id="nroChasis"
                                            value="{{ $header->NroChasis }}" class="form-control"
                                            placeholder="LZSNJDZC2G8024057" required>
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="nroMotor" class="col-lg-3 control-label "><span class="panel-title" style="color:#f00">(*) </span>N° de Motor:</label>
                                    <label class="col-lg-9">
                                        <input type="input" name="nroMotor" value="{{ $header->NroMotor }}"
                                            id="nroMotor" class="form-control" placeholder="60V1200WL1612084866"
                                            required>
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="nroSerie" class="col-lg-3 control-label ">N° de Serie:</label>
                                    <label class="col-lg-9">
                                        <input type="input" name="nroSerie" id="nroSerie" class="form-control"
                                            placeholder="">
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="nroVin" class="col-lg-3 control-label ">N° de VIN:</label>
                                    <label class="col-lg-9">
                                        <input type="input" name="nroVin" id="nroVin"
                                            value="{{ $header->NroVin }}" class="form-control" placeholder=""
                                            >
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="nroEjes" class="col-lg-3 control-label "><span class="panel-title" style="color:#f00">(*) </span>N° y disposición de
                                        Ejes:</label>
                                    <label class="col-lg-9">
                                        <select name="nroEjes" id="nroEjes" class="form-control" required>
                                            <?php
                                            $dispo_ejes = NumDispoEjes::all();
                                            $dispo_ejes_remolque = NumDispoEjes::select('num_dispo')
                                                ->where('id_tipo_vehiculo', 17)
                                                ->where('num_dispo', 'NOT LIKE', '%E%')
                                                ->get();
                                            ?>
                                            @foreach ($dispo_ejes as $dispo)
                                                @if ($dispo->id_tipo_vehiculo == 16)
                                                    @foreach ($dispo_ejes_remolque as $aux)
                                                        <option
                                                            value="{{ $aux->num_dispo }}-{{ $dispo->num_dispo }}">
                                                            {{ $aux->num_dispo }}-{{ $dispo->num_dispo }}</option>
                                                    @endforeach
                                                @else
                                                    <option value="{{ $dispo->num_dispo }}">{{ $dispo->num_dispo }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <!--<input type="input" name="nroEjes" id="nroEjes" class="form-control" placeholder="" required>-->

                                    </label>
                                </div>
                                <div class="row">
                                    <label for="potenciaMotor" class="col-lg-3 control-label ">Potencia Motor:</label>
                                    <label class="col-lg-9">
                                        <input type="input" name="potenciaMotor" id="potenciaMotor"
                                            class="form-control" placeholder="">
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="tipoTraccion" class="col-lg-3 control-label "><span class="panel-title" style="color:#f00">(*) </span>Tipo Tracción:</label>
                                    <label class="col-lg-9">
                                        <!--<input type="input" name="tipoTraccion" id="tipoTraccion"
                                            class="form-control" placeholder="">-->
                                        <select id="tipoTraccion" name="tipoTraccion"class="form-control"></select>

                                    </label>
                                </div>
                                <div class="row">
                                    <label for="tipoCarroceria" class="col-lg-3 control-label "><span class="panel-title" style="color:#f00">(*) </span>Tipo
                                        Carrocería:</label>
                                    <label class="col-lg-9">
                                        <select name="tipoCarroceria" id="tipoCarroceria" class="form-control">
                                            @foreach ($tipo_carroceria as $tipo)
                                                <option value="{{ $tipo->name }}">{{ $tipo->name }}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                </div>

                                <div class="row">
                                    <label for="tipoPotencia" class="col-lg-3 control-label ">Tipo Potencia:</label>
                                    <label class="col-lg-9">
                                        <select name="tipoPotencia" id="tipoPotencia" class="form-control">
                                            @foreach ($tipo_potencia as $tipo)
                                                <option value="{{ $tipo->unidad_medida }}">{{ $tipo->unidad_medida }}
                                                </option>
                                            @endforeach

                                        </select>
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="pbv" class="col-lg-3 control-label "><span class="panel-title" style="color:#f00">(*) </span>Peso Bruto
                                        Vehicular:</label>
                                    <label class="col-lg-3">
                                        <input type="input" name="pbv" id="pbv"
                                            value="{{ $header->PesoBrutoVehi }}" class="form-control"
                                            placeholder="2200" required>
                                    </label>
                                </div>

                                <div class="row">
                                    <label for="tpbv" class="col-lg-3 control-label "><span class="panel-title" style="color:#f00">(*) </span>Tipo Peso Bruto
                                        Vehicular:</label>
                                    <label class="col-lg-3">
                                        <input type="input" name="tpbv" id="tpbv"
                                            value="{{ $header->TipoPBV }}" class="form-control" placeholder="K"
                                            required>
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="puertas" class="col-lg-3 control-label ">Puertas:</label>
                                    <label class="col-lg-3">
                                        <input type="input" name="puertas" id="puertas" class="form-control"
                                            placeholder="0" readonly>
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="tipoVehiculo" class="col-lg-3 control-label "><span class="panel-title" style="color:#f00">(*) </span>Tipo Vehículo:</label>
                                    <label class="col-lg-9">
                                        <input type="input" name="tipoVehiculo" id="tipoVehiculo"
                                            value="{{ $header->TipoVehiculo }}" class="form-control"
                                            placeholder="AUTOMOVIL" required>
                                    </label>
                                </div>
                                @if($reingreso != null)
                                <div class="row">
                                    <label for="nroResExenta" class="col-lg-3 control-label "><span class="panel-title" style="color:#f00">(*) </span>Número resolución exenta:</label>
                                    <label class="col-lg-9">
                                        <input type="input" value="" name="nroResExenta" id="nroResExenta" class="form-control" min="1" max="99999999">
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="fechaResExenta" class="col-lg-3 control-label "><span class="panel-title" style="color:#f00">(*) </span>Fecha resolución exenta:</label>
                                    <label class="col-lg-9">
                                        <input type="input" value="" name="fechaResExenta" id="fechaResExenta" class="form-control fechaRechazos">
                                    </label>
                                </div>

                                <div class="row">
                                    <label for="fechaSolRech" class="col-lg-3 control-label "><span class="panel-title" style="color:#f00">(*) </span>Fecha solicitud rechazada:</label>
                                    <label class="col-lg-9">
                                        <input type="input" value="" name="fechaSolRech" id="fechaSolRech" class="form-control fechaRechazos">
                                    </label>
                                </div>

                                <script>
                                    $(".fechaRechazos").datepicker({
                                        language: 'es',
                                        dateFormat: 'yymmdd',
                                        changeMonth: true, 
                                        changeYear: true,       
                                    });
                                </script>



                                @endif


                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="panel">
                        <div class="panel-heading">
                            <span class="panel-title">
                                <span class="glyphicon glyphicon-cog hidden"></span>Servicios adicionales
                            </span>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="col-lg-2 control-label">SOAP:</label>
                                <label class="col-lg-4">
                                    <div class="switch switch-success switch-inline">
                                        <input type="checkbox" id="incluyeSOAP" name="incluyeSOAP" value="{{ isset($solicitud_data)? ((is_null($solicitud_data->incluyeSOAP))? "0" : $solicitud_data->incluyeSOAP) : "0" }}" 
                                        @if(isset($solicitud_data))   @if(!is_null($solicitud_data->incluyeSOAP))  @if($solicitud_data->incluyeSOAP == 1) checked   @endif @endif @endif >
                                        <label for="incluyeSOAP"></label>
                                    </div>
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">TAG:</label>
                                <label class="col-lg-4">
                                    <div class="switch switch-success switch-inline">
                                        <input type="checkbox" id="incluyeTAG" name="incluyeTAG" value="{{ isset($solicitud_data)? ((is_null($solicitud_data->incluyeTAG))? "0" : $solicitud_data->incluyeTAG) : "0" }}"
                                        @if(isset($solicitud_data))   @if(!is_null($solicitud_data->incluyeTAG))  @if($solicitud_data->incluyeTAG == 1) checked   @endif @endif @endif>
                                        <label for="incluyeTAG"></label>
                                    </div>
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">Permiso de circulación:</label>
                                <label class="col-lg-4">
                                    <div class="switch switch-success switch-inline">
                                        <input type="checkbox" id="incluyePermiso" name="incluyePermiso"
                                            value="{{ isset($solicitud_data)? ((is_null($solicitud_data->incluyePermiso))? "0" : $solicitud_data->incluyePermiso) : "0" }}" 
                                            @if(isset($solicitud_data))   @if(!is_null($solicitud_data->incluyePermiso))  @if($solicitud_data->incluyePermiso == 1) checked   @endif @endif @endif>
                                        <label for="incluyePermiso"></label>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>




        </div>
        <div class="panel-footer">
            <button type="submit" class="btn btn-system">
                <li class="fa fa-save"></li> Grabar y Continuar Revisión
            </button>
        </div>
    </div>
</form>






<script type="text/javascript">
    $(document).ready(function() {
        $('#nroEjes').on('change', function(e) {
            var numDispoEjes = $(this).val();
            console.log(numDispoEjes);
            var traccionValidas = getValidTractionFormats(numDispoEjes.trim());
            console.log(traccionValidas);
            var options = "";
            if (traccionValidas.length > 0) {
                for (var i = 0; i < traccionValidas.length; i++) {
                    options += "<option value='" + traccionValidas[i] + "'>" + traccionValidas[i] +
                        "</option>";
                }
                $("#tipoTraccion").html(options);
            } else {
                $("#tipoTraccion").html(options);
            }
        });

        $('#incluyeSOAP').on('change', function(e) {
            if ($('#incluyeSOAP').prop('checked')==true){
                $(this).val(1);
            }else{
                $(this).val(0);
            }
        });

        $('#incluyeTAG').on('change', function(e) {
            if ($('#incluyeTAG').prop('checked')==true){
                $(this).val(1);
            }else{
                $(this).val(0);
            }
        });

        $('#incluyePermiso').on('change', function(e) {
            if ($('#incluyePermiso').prop('checked')==true){
                $(this).val(1);
            }else{
                $(this).val(0);
            }
        });
    });

    $.datepicker.regional['es'] = {
        closeText: 'Cerrar',
        prevText: '< Ant',
        nextText: 'Sig >',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
        weekHeader: 'Sm',
        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };

    $.datepicker.setDefaults($.datepicker.regional['es']);

    $(document).on("submit", "#formFacturaCamion", function(e) {
        e.preventDefault();
        showOverlay();
        let formData = new FormData(document.getElementById("formFacturaCamion"));

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "@php  if(Auth::user()->rol_id >= 4){ 
                            echo '/solicitud/'.$id.'/updateRevisionFacturaCamion';
                        }elseif(Auth::user()->rol_id <= 3){ 
                            echo  '/solicitud/'.$id.'/updateRevisionFacturaCamion/revision';
                        }
                  @endphp",
            type: "post",
            data: formData,
            processData: false,
            contentType: false,
            error: function(jqXHR, textStatus, errorThrown) {
                hideOverlay();
                // Acción cuando hay un error.
                new PNotify({
                        title: 'Error',
                        text: "Error: " + textStatus + ' : ' + errorThrown,
                        shadow: true,
                        opacity: '0.75',
                        addclass: 'stack_top_right',
                        type: 'danger',
                        stack: {
                            "dir1": "down",
                            "dir2": "left",
                            "push": "top",
                            "spacing1": 10,
                            "spacing2": 10
                        },
                        width: '290px',
                        delay: 5000
                });
            },
            success: function(data) {
                hideOverlay();
                $("#pills-docs").html(data);
                $("#pills-docs").toggleClass('show');
                $("#pills-home").removeClass('show');
                $("#pills-contact").removeClass('show');
                $("#pills-profile").removeClass('show');
                $("#pills-invoice").removeClass('show');
                $("#pills-voucher").removeClass('show');
                $("#pills-pay").removeClass('show');
                $("#pills-docs-tab").attr("href", "#pills-docs");
                $("#pills-docs-tab").toggleClass('disabled');
                $("#pills-docs-tab").attr("aria-disabled", false);
                $("#pills-docs-tab").click();
            }
        });


    });

    function getValidTractionFormats(allowedConfigs) {
        const allowedDisposition = {
            S: 2,
            D: 4,
            T: 6,
            C: 8,
            Q: 10,
            E: 12,
        };

        let minAllowed, maxAllowed;

        if (allowedConfigs.includes("-")) {
            const [minConfig, maxConfig] = allowedConfigs.split("-");
            minAllowed = allowedDisposition[minConfig[0]] + parseInt(minConfig.slice(1)) - 2;
            maxAllowed = allowedDisposition[maxConfig[0]] + parseInt(maxConfig.slice(1)) - 2;
        } else {
            const num = parseInt(allowedConfigs.slice(1));
            const letter = allowedConfigs[0];
            minAllowed = maxAllowed = allowedDisposition[letter] + num - 2;
        }

        const validTractionFormats = [];

        for (let numAxes = minAllowed; numAxes <= maxAllowed; numAxes += 2) {
            for (let numTractionAxes = 2; numTractionAxes <= numAxes; numTractionAxes += 2) {
                validTractionFormats.push(`${numAxes}X${numTractionAxes}`);
            }
        }

        return validTractionFormats;
    }
</script>
