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

use App\Models\Factura;
use App\Models\Tipo_Documento;
use App\Models\Acreedor;

?>

@include('includes.form-error-message')

<form method="post" id="formLimitacion" role="form" class="form-horizontal form-revision" >
    @csrf
    @method('post')

    <div class="panel panel-info panel-border top">
        <div class="panel-heading">
            <span class="panel-title">Ingreso de Solicitud N° {{$id}} - Datos de Prohibición y/o Limitación de Vehículo</span>
        </div>
        <?php
            $factura = Factura::where('id_solicitud',$id)->first();
            $tipo_documento = Tipo_Documento::whereIn('id',[6,7])->get();
            $acreedores = Acreedor::all();

        ?>
        <div class="panel-body">
            <div class="form-group">
                <div class="row"><div class="col-lg-4"></div><div class="col-lg-4"><h4>Datos Acreedor</h4></div></div>
                <div class="row">
                    <label for="runAcreedor" class="col-lg-3 control-label ">Seleccione Acreedor:</label>
                    <label class="col-lg-3">
                        <select name="runAcreedor" id="runAcreedor" class="form-control" required>
                            <option value="0">Seleccione Acreedor</option>
                            @foreach($acreedores as $acre)
                                <option value="{{$acre->rut}}">{{$acre->nombre}}</option>
                            @endforeach
                        </select>


                        <input type="hidden" name="nombreRazon" id="nombreRazon" class="form-control" required>
                    </label>
                </div>
                <div class="row"><div class="col-lg-4"></div><div class="col-lg-4"><h4>Datos Vehiculo</h4></div></div>
                <div class="row">
                    <label for="nro_chasis" class="col-lg-3 control-label ">Nro Chasis:</label>
                    <label class="col-lg-3">
                        <input type="text" name="nro_chasis" id="nro_chasis" value="{{$factura->nro_chasis}}" class="form-control" required>
                    </label>
                </div>
                <div class="row">
                    <label for="nro_serie" class="col-lg-3 control-label ">Nro Serie:</label>
                    <label class="col-lg-3">
                        <input type="text" name="nro_serie" id="nro_serie" value="{{$factura->nro_serie}}" class="form-control" required>
                    </label>
                </div>
                <div class="row">
                    <label for="nro_vin" class="col-lg-3 control-label ">Nro Vin:</label>
                    <label class="col-lg-3">
                        <input type="text" name="nro_vin" id="nro_vin" value="{{$factura->nro_vin}}" class="form-control" required>
                    </label>
                </div>
                <div class="row">
                    <label for="nro_motor" class="col-lg-3 control-label ">Nro Motor:</label>
                    <label class="col-lg-3">
                        <input type="text" name="nro_motor" id="nro_motor" value="{{$factura->motor}}" class="form-control" required>
                    </label>
                </div>
                <div class="row"><div class="col-lg-4"></div><div class="col-lg-4"><h4>Datos Documento</h4></div></div>
                <div class="row">
                    <label for="folio" class="col-lg-3 control-label ">Folio:</label>
                    <label class="col-lg-3">
                        <input type="number" min="1" max="99999999" name="folio" id="folio" value="" class="form-control" required>
                    </label>
                </div>
                <div class="row">
                    <label for="folio" class="col-lg-3 control-label ">Tipo Documento:</label>
                    <label class="col-lg-3">
                        <select name="tipoDoc" id="tipoDoc2" class="form-control" required>
                            @foreach($tipo_documento as $tipo)
                                <option value="{{trim($tipo->name)}}">{{$tipo->name}}</option>
                            @endforeach

                        </select>
                    </label>
                </div>
                <div class="row">
                    <label for="autorizante" class="col-lg-3 control-label ">Autorizante:</label>
                    <label class="col-lg-3">
                        <input type="input" name="autorizante" id="autorizante" value="" class="form-control">
                    </label>
                </div>
                

                <div class="row">
                    <div class="col-lg-3"></div>
                    <div class="col-lg-3">
                        <span class="btn btn-warning fileinput-button col-sm-12" name="DocLim" id="DocLim">
                            Seleccionar Documento</span>
                    </div>
                    <div class="col-lg-3">
                        <input id="Doc_Lim" name="Doc_Lim" type="file" style="display:none" accept="application/pdf" />
                        <label id="lbl_Doc_Lim"></label>
                    </div>
                </div>    
            </div>


        </div>


        <div class="panel-footer">
            <button type="submit" class="btn btn-system"><li class="fa fa-save"></li>  Grabar y Continuar Revisión </button>
        </div>
    </div>



</form>

<script>


    $(document).ready(function(){

        $('#DocLim').on('click', function() {
            $('#Doc_Lim').trigger('click');
        });

        $('#Doc_Lim').on('change', function() {
            $('#lbl_Doc_Lim').text($('#Doc_Lim').val());
        });
    });

    $(document).on("change","#runAcreedor",function(){
        if(parseFloat($(this).val()) != 0){
            $("#nombreRazon").val($("#runAcreedor option:selected").text());
        }
        else{
            $("#nombreRazon").val('');
        }


    });


    $(document).on("submit","#formLimitacion",function(e){
        e.preventDefault();
        let formData = new FormData(document.getElementById("formLimitacion"));

        if(parseFloat($("#runAcreedor").val()) == 0){
            new PNotify({
                title: 'Inscribir limitación',
                text: 'Debe seleccionar un acreedor',
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
                delay: 2000
            });
            return false;

        }




        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "/solicitud/{{$id}}/limitacion/new",
            type: "post",
            data: formData,
            processData: false,
            contentType: false,
            success: function(data){

            }

        });
    });




</script>