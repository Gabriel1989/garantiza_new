@extends("themes.$themes.layout")



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

@section('contenido')

@include('includes.form-error-message')
<form method="post" action="{{route('solicitud.updateRevisionFacturaAuto', ['id' => $id])}}" role="form" class="form-horizontal form-revision" >
    @csrf
    @method('PUT')
    <div class="panel panel-info panel-border top">
        <div class="panel-heading">
            <span class="panel-title">Revisión de Solicitud N° {{$id}} - Datos de Factura</span>
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
                                        <input type="input" name="agnoFabricacion" id="agnoFabricacion" class="form-control" placeholder="{{ now()->year }}" required>
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="asientos" class="col-lg-3 control-label ">Asientos:</label>
                                    <label class="col-lg-2">
                                        <input type="input" name="asientos" id="asientos" class="form-control" placeholder="5" required>
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="carga" class="col-lg-3 control-label ">Carga:</label>
                                    <label class="col-lg-2">
                                        <input type="input" name="carga" id="carga" class="form-control" placeholder="1600" required>
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="color" class="col-lg-3 control-label ">Color:</label>
                                    <label class="col-lg-9">
                                        <input type="input" name="color" id="color" class="form-control" placeholder="ROJO" required>
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="combustible" class="col-lg-3 control-label ">Combustible:</label>
                                    <label class="col-lg-9">
                                        <input type="input" name="combustible" id="combustible" class="form-control" placeholder="DIESEL" required>
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="marca" class="col-lg-3 control-label ">Marca:</label>
                                    <label class="col-lg-9">
                                        <input type="input" name="marca" id="marca" class="form-control" placeholder="CITROEN" required>
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="modelo" class="col-lg-3 control-label ">Modelo:</label>
                                    <label class="col-lg-9">
                                        <input type="input" name="modelo" id="modelo" class="form-control" placeholder="C-ELYSSE" required>
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="nroChasis" class="col-lg-3 control-label ">N° de Chasis:</label>
                                    <label class="col-lg-9">
                                        <input type="input" name="nroChasis" id="nroChasis" class="form-control" placeholder="LZSNJDZC2G8024057" required>
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="nroMotor" class="col-lg-3 control-label ">N° de Motor:</label>
                                    <label class="col-lg-9">
                                        <input type="input" name="nroMotor" id="nroMotor" class="form-control" placeholder="60V1200WL1612084866" required>
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
                                        <input type="input" name="nroVin" id="nroVin" class="form-control" placeholder="">
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="pbv" class="col-lg-3 control-label ">Peso Bruto Vehicular:</label>
                                    <label class="col-lg-3">
                                        <input type="input" name="pbv" id="pbv" class="form-control" placeholder="2200">
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="puertas" class="col-lg-3 control-label ">Puertas:</label>
                                    <label class="col-lg-3">
                                        <input type="input" name="puertas" id="puertas" class="form-control" placeholder="4">
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="tipoVehiculo" class="col-lg-3 control-label ">Tipo Vehículo:</label>
                                    <label class="col-lg-9">
                                        <input type="input" name="tipoVehiculo" id="tipoVehiculo" class="form-control" placeholder="AUTOMOVIL">
                                    </label>
                                </div>
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
    

@endsection

@section('scripts')

<script type="text/javascript">
    jQuery(document).ready(function() {


    });

    
</script>

@endsection