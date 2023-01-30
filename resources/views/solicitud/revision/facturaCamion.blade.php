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



@include('includes.form-error-message')
<form method="post" id="formFacturaCamion" old-action="{{route('solicitud.updateRevisionFacturaCamion', ['id' => $id])}}" role="form" class="form-horizontal form-revision" >
    @csrf
    @method('post')
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
                                <span class="glyphicon glyphicon-cog hidden"></span>Información del Vehículo Pesado
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
                                        <input type="input" name="asientos" id="asientos" value="{{ $header->Asientos}}" class="form-control" placeholder="5" >
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="carga" class="col-lg-3 control-label ">Carga:</label>
                                    <label class="col-lg-2">
                                        <input type="input" name="carga" id="carga" class="form-control" placeholder="1600" required>
                                    </label>
                                    
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
                                        <input type="input" name="color" id="color" value="{{ $header->Color}}" class="form-control" placeholder="BLANCO" required>
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
                                        <input type="input" name="marca" id="marca" value="{{ $header->Marca}}" class="form-control" placeholder="SCANIA" required>
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="modelo" class="col-lg-3 control-label ">Modelo:</label>
                                    <label class="col-lg-9">
                                        <input type="input" name="modelo" id="modelo" value="{{ $header->Modelo}}" class="form-control" placeholder="G-450" required>
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
                                        <input type="input" name="nroSerie" id="nroSerie" class="form-control" placeholder="" required>
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="nroVin" class="col-lg-3 control-label ">N° de VIN:</label>
                                    <label class="col-lg-9">
                                        <input type="input" name="nroVin" id="nroVin" value="{{ $header->NroVin}}" class="form-control" placeholder="" required>
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="nroEjes" class="col-lg-3 control-label ">N° de Ejes:</label>
                                    <label class="col-lg-9">
                                        <input type="input" name="nroEjes" id="nroEjes" class="form-control" placeholder="" required>
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="potenciaMotor" class="col-lg-3 control-label ">Potencia Motor:</label>
                                    <label class="col-lg-9">
                                        <input type="input" name="potenciaMotor" id="potenciaMotor" class="form-control" placeholder="">
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="tipoTraccion" class="col-lg-3 control-label ">Tipo Tracción:</label>
                                    <label class="col-lg-9">
                                        <input type="input" name="tipoTraccion" id="tipoTraccion" class="form-control" placeholder="">
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="tipoCarroceria" class="col-lg-3 control-label ">Tipo Carrocería:</label>
                                    <label class="col-lg-9">
                                        <select name="tipoCarroceria" id="tipoCarroceria" class="form-control">
                                            @foreach($tipo_carroceria as $tipo)
                                                <option value="{{$tipo->name}}">{{$tipo->name}}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                </div>

                                <div class="row">
                                    <label for="tipoPotencia" class="col-lg-3 control-label ">Tipo Potencia:</label>
                                    <label class="col-lg-9">
                                        <input type="input" name="tipoPotencia" id="tipoPotencia" class="form-control" placeholder="">
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
                                        <input type="input" name="puertas" id="puertas" class="form-control" placeholder="0" readonly> 
                                    </label>
                                </div>
                                <div class="row">
                                    <label for="tipoVehiculo" class="col-lg-3 control-label ">Tipo Vehículo:</label>
                                    <label class="col-lg-9">
                                        <input type="input" name="tipoVehiculo" id="tipoVehiculo" value="{{ $header->TipoVehiculo}}" class="form-control" placeholder="AUTOMOVIL" required>
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
    





<script type="text/javascript">
    jQuery(document).ready(function() {


    });

    $(document).on("submit","#formFacturaCamion",function(e){
        e.preventDefault();
        let formData = new FormData(document.getElementById("formFacturaCamion"));

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "/solicitud/{{$id}}/updateRevisionFacturaCamion",
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

