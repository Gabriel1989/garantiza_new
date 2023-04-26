@section('styles')
<!-- FlowChart CSS -->
<link rel="stylesheet" type="text/css" href="{{asset("assets/$themes/vendor/plugins/flowchart/jquery.flowchart.min.css")}}">
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
use App\Models\Reingreso;
$reingreso = Reingreso::where('solicitud_id',$id)->first();
?>

@include('includes.form-error-message')
<form method="post" id="formFacturaAuto" old-action="{{route('solicitud.updateRevisionFacturaAuto', ['id' => $id])}}" role="form" class="form-horizontal form-revision" >
    @csrf
    @method('post')
    <div class="panel panel-info panel-border top">
        <div class="panel-heading">
            <span class="panel-title">Ingreso de Solicitud N° {{$id}} - Datos de Factura</span>
        </div>
        <div class="panel-body">
            <div class="form-group">
                
                <div class="col-lg-6">
                    <div class="panel">
                        <div class="panel-heading">
                            <span class="panel-title">
                                <span class="glyphicon glyphicon-cog hidden"></span>Información del Auto
                            </span>
                        </div>
                        <div class="panel-body" >
                            <div class="form-group">
                                <div class="row">
                                    <label for="agnoFabricacion" class="col-lg-3 control-label ">Año Fabricación:</label>
                                    <label class="col-lg-2">
                                        <input type="input" name="agnoFabricacion" id="agnoFabricacion" value="{{ $header->AnnioFabricacion}}" class="form-control" placeholder="{{ now()->year }}" required>
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="asientos" class="col-lg-3 control-label ">Asientos:</label>
                                    <label class="col-lg-2">
                                        <input type="input" name="asientos" id="asientos" value="{{ $header->Asientos}}" class="form-control" placeholder="5" required>
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="carga" class="col-lg-3 control-label ">Carga:</label>
                                    <label class="col-lg-2">
                                        <input type="input" name="carga" id="carga" class="form-control" placeholder="1600" value="0" required>
                                    </label>
                                    <div class="col-lg-5">
                                        <p style="color:#F00;"><i style="color:#F00;" class="fa fa-exclamation-triangle"></i> Atención: en caso de inscribir una CAMIONETA, debe obligatoriamente ingresar un valor mayor a cero</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <label for="tipo_carga" class="col-lg-3 control-label ">Tipo Carga:</label>
                                    <label class="col-lg-2">
                                        <input type="input" name="tCarga" id="tipo_carga" value="{{ $header->TipoCarga}}" class="form-control" placeholder="K" required>
                                    </label>
                                </div>

                                <div class="row">
                                    <label for="color" class="col-lg-3 control-label ">Color:</label>
                                    <label class="col-lg-9">
                                        <input type="input" name="color" id="color" value="{{ $header->Color}}" class="form-control" placeholder="ROJO" required>
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="combustible" class="col-lg-3 control-label ">Combustible:</label>
                                    <label class="col-lg-9">
                                        <input type="input" name="combustible" id="combustible" value="{{ $header->TipoCombustible}}" class="form-control" placeholder="DIESEL" required>
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="marca" class="col-lg-3 control-label ">Marca:</label>
                                    <label class="col-lg-9">
                                        <input type="input" name="marca" id="marca" value="{{ $header->Marca}}" class="form-control" placeholder="CITROEN" required>
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="modelo" class="col-lg-3 control-label ">Modelo:</label>
                                    <label class="col-lg-9">
                                        <input type="input" name="modelo" id="modelo" value="{{ $header->Modelo}}" class="form-control" placeholder="C-ELYSSE" required>
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="nroChasis" class="col-lg-3 control-label ">N° de Chasis:</label>
                                    <label class="col-lg-9">
                                        <input type="input" name="nroChasis" id="nroChasis" value="{{ $header->NroChasis}}" class="form-control" placeholder="LZSNJDZC2G8024057" required>
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="nroMotor" class="col-lg-3 control-label ">N° de Motor:</label>
                                    <label class="col-lg-9">
                                        <input type="input" name="nroMotor" value="{{ $header->NroMotor}}" id="nroMotor" class="form-control" placeholder="60V1200WL1612084866" required>
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="nroSerie" class="col-lg-3 control-label ">N° de Serie:</label>
                                    <label class="col-lg-9">
                                        <input type="input" name="nroSerie" id="nroSerie" class="form-control" placeholder="">
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="nroVin" class="col-lg-3 control-label ">N° de VIN:</label>
                                    <label class="col-lg-9">
                                        <input type="input" name="nroVin" id="nroVin" value="{{ $header->NroVin}}" class="form-control" placeholder="" >
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="pbv" class="col-lg-3 control-label ">Peso Bruto Vehicular:</label>
                                    <label class="col-lg-3">
                                        <input type="input" name="pbv" id="pbv" value="{{ $header->PesoBrutoVehi}}" class="form-control" placeholder="2200" required>
                                    </label>
                                </div>

                                <div class="row">
                                    <label for="tpbv" class="col-lg-3 control-label ">Tipo Peso Bruto Vehicular:</label>
                                    <label class="col-lg-3">
                                        <input type="input" name="tpbv" id="tpbv" value="{{ $header->TipoPBV}}" class="form-control" placeholder="K" required>
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="puertas" class="col-lg-3 control-label ">Puertas:</label>
                                    <label class="col-lg-3">
                                        <input type="input" name="puertas" id="puertas" value="{{ $header->Puertas}}" class="form-control" placeholder="4" required> 
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="tipoVehiculo" class="col-lg-3 control-label ">Tipo Vehículo:</label>
                                    <label class="col-lg-9">
                                        <input type="input" name="tipoVehiculo" id="tipoVehiculo" value="{{ $header->TipoVehiculo}}" class="form-control" placeholder="AUTOMOVIL" required>
                                    </label>
                                </div>

                                <div class="row">
                                    <label for="codigoCit" class="col-lg-3 control-label ">Código CIT:</label>
                                    <label class="col-lg-9">
                                        <input type="input" name="codigoCit" id="codigoCit" value="{{ $header->CitModelo}}" class="form-control" placeholder="XXXXXX">
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="codigoCid" class="col-lg-3 control-label">Código CID:</label>
                                    <label class="col-lg-9">
                                        <input type="input" name="codigoCid" id="codigoCid" value="" class="form-control" placeholder="XXXXXX">
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="mImpuesto" class="col-lg-3 control-label">Monto Pagado Impuesto Verde:</label>
                                    <label class="col-lg-9">
                                        <input type="input" name="mImpuesto" id="mImpuesto" value="0" class="form-control" placeholder="XXXXXX">
                                    </label>
                                </div>
                                @if($reingreso != null)
                                <div class="row">
                                    <label for="nroResExenta" class="col-lg-3 control-label ">Número resolución exenta:</label>
                                    <label class="col-lg-9">
                                        <input type="input" value="" name="nroResExenta" id="nroResExenta" class="form-control" min="1" max="99999999">
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="fechaResExenta" class="col-lg-3 control-label ">Fecha resolución exenta:</label>
                                    <label class="col-lg-9">
                                        <input type="input" value="" name="fechaResExenta" id="fechaResExenta" class="form-control fechaRechazos">
                                    </label>
                                </div>

                                <div class="row">
                                    <label for="fechaSolRech" class="col-lg-3 control-label ">Fecha solicitud rechazada:</label>
                                    <label class="col-lg-9">
                                        <input type="input" value="" name="fechaSolRech" id="fechaSolRech" class="form-control fechaRechazos">
                                    </label>
                                </div>

                                <script>
                                    $(".fechaRechazos").datepicker({
                                        language: 'es',
                                        dateFormat: 'yymmdd',
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
                                <span class="glyphicon glyphicon-cog hidden"></span>Información Detalle de la Factura
                            </span>
                        </div>
                        <div class="panel-body" >
                            <div class="form-group">
                                @for ($i = 0; $i < count($detalle) -1; $i++)
                                <div class="row">
                                    <label for="linea{{$i}}" class="col-lg-2 control-label ">Línea {{$i + 1}}:</label>
                                    <label class="col-lg-10">
                                        <input type="input" name="linea{{$i}}" id="linea{{$i}}" class="form-control" value="{{$detalle[$i]}}" readonly>
                                    </label>
                                </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </div>



           
        </div>
        <div class="panel-footer">
            <button type="submit" class="btn btn-system"><li class="fa fa-save"></li>  Grabar y Continuar Revisión </button>
        </div>
    </div>
</form>
    





<script type="text/javascript">
    jQuery(document).ready(function() {


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

    $(document).on("submit","#formFacturaAuto",function(e){
        e.preventDefault();
        let formData = new FormData(document.getElementById("formFacturaAuto"));

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "/solicitud/{{$id}}/updateRevisionFacturaAuto",
            type: "post",
            data: formData,
            processData: false,
            contentType: false,
            success: function(data){
                $("#pills-docs").html(data);
                $("#pills-docs").toggleClass('show');
                $("#pills-home").removeClass('show');
                $("#pills-contact").removeClass('show');
                $("#pills-profile").removeClass('show');
                $("#pills-invoice").removeClass('show');
                $("#pills-docs-tab").attr("href","#pills-docs");
                $("#pills-docs-tab").toggleClass('disabled');
                $("#pills-docs-tab").attr("aria-disabled",false);
                $("#pills-docs-tab").click();
            }
        });


    });

    
</script>

