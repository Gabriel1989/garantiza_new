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
use App\Models\Tipo_Documento;
use App\Models\TransferenciaData;
$reingreso = Reingreso::where('transferencia_id',$id)->first();
$tipoDocs = Tipo_Documento::whereIn('id',[9,10,11,12,13,14])->get();
$transferencia_data = TransferenciaData::where('transferencia_id',$id)->first();
?>

@include('includes.form-error-message')
<form method="post" id="formDataResumen" role="form" class="form-horizontal form-revision" >
    @csrf
    @method('post')
    <div class="panel panel-info panel-border top">
        <div class="panel-heading">
            <span class="panel-title">Ingreso de Solicitud N° {{$id}} - Datos adicionales para transferencia
                <br><span style="color:#f00">(*)</span> Datos obligatorios</span>
            </span>
        </div>
        <div class="panel-body">
            <div class="col-lg-6 col-md-6">
                <div class="form-group">
                    <label for="digitoVerificadorPPU" class="col-lg-3 control-label "><span style="color:#f00">(*)</span>Digito verificador PPU:</label>
                    <label class="col-lg-4">
                        <input value="{{ ($transferencia_data != null)? $transferencia_data->dv_ppu : '' }}" type="text" name="digitoVerificadorPPU" id="digitoVerificadorPPU" class="form-control" maxlength="1">
                    </label>
                </div>
                <div class="form-group">
                    <label for="tipoDocTransf" class="col-lg-3 control-label "><span style="color:#f00">(*)</span>Tipo Documento:</label>
                    <label class="col-lg-4">
                        <select name="tipoDocTransf" id="tipoDocTransf" class="form-control"  required>
                            @foreach ($tipoDocs as $doc)
                                <option value="{{ $doc->name }}" @if($transferencia_data != null) @if($transferencia_data->tipoDocumento->name == $doc->name)  selected @endif @endif>{{ $doc->name }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>
                <div class="form-group">
                    <label for="naturalezaTransf" class="col-lg-3 control-label "><span style="color:#f00">(*)</span>Naturaleza del acto:</label>
                    <label class="col-lg-4">
                        <select name="naturalezaTransf" id="naturalezaTransf" class="form-control"  required>
                        </select>
                    </label>
                </div>
                <div class="form-group">
                    <label for=" numDocTransf" class="col-lg-3 control-label "><span style="color:#f00">(*)</span>Número de documento:</label>
                    <label class="col-lg-4">
                        <input value="{{ ($transferencia_data != null)? $transferencia_data->num_doc : 0 }}" type="number" name="numDocTransf" id="numDocTransf" class="form-control"  required>
                        
                    </label>
                </div>
                <div class="form-group">
                    <div class="col-sm-12 col-lg-12 mb5">
                        <div class="col-lg-7">
                            <span class="btn btn-warning fileinput-button col-sm-12" name="pic" id="DocumentoTransferencia">
                                <span style="color:#f00">(*)</span>Seleccionar Documento PDF</span>
                        </div>
                        <div class="col-lg-5">
                            <input id="Documento_Transferencia" name="Documento_Transferencia" type="file" style="display:none" accept="text/xml,application/pdf" />
                            <label id="lbl_Documento_Transferencia"></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="fechaEmisionTransf" class="col-lg-3 control-label "><span style="color:#f00">(*)</span>Fecha emisión documento o factura:</label>
                    <label class="col-lg-4">
                        <input value="{{ ($transferencia_data != null)? $transferencia_data->fecha_emision : 0 }}" type="text" name="fechaEmisionTransf" id="fechaEmisionTransf" class="form-control datepicker"  required>
                    </label>
                </div>

                <div class="form-group ml50">
                    <label for="comuna3" class="col-lg-2 control-label"><span style="color:#f00">(*)</span>Lugar :</label>
                        <label class="col-lg-4">
                            <select class="col-sm-12 form-select comuna" name="lugarTransf" id="comuna3">
                                <option value="0">Seleccione Comuna...</option>
                                @foreach ($comunas as $item)
                                    <option value="{{ $item->Codigo }}" @if($transferencia_data != null) @if($transferencia_data->lugar_id == $item->Codigo)  selected @endif @endif>{{ $item->Nombre }}</option>
                                @endforeach
                            </select>
                        </label>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="form-group">
                    <label for="totalTransf" class="col-lg-3 control-label "><span style="color:#f00">(*)</span>Total Venta:</label>
                    <label class="col-lg-4">
                        <input value="{{ ($transferencia_data != null)? $transferencia_data->total_venta : 0 }}" type="number" name="totalTransf" id="totalTransf" class="form-control"  required>
                        
                    </label>
                </div>

                <div class="form-group ml50">
                    <label for="monedaTransf" class="col-lg-2 control-label"><span style="color:#f00">(*)</span>Moneda :</label>
                        <label class="col-lg-5">
                            <select class="col-sm-12 form-select" name="monedaTransf" id="monedaTransf">
                                <option value="0" >Seleccione Moneda...</option>
                                <option value="$" @if($transferencia_data != null) @if($transferencia_data->moneda == "$")  selected @endif @endif>Peso</option>
                                <option value="US" @if($transferencia_data != null) @if($transferencia_data->moneda == "US")  selected @endif @endif>Dólar</option>
                                <option value="UF" @if($transferencia_data != null) @if($transferencia_data->moneda == "UF")  selected @endif @endif>Unidad de Fomento</option>
                            </select>
                        </label>
                </div>

                <div class="form-group">
                    <label for="kilometraje" class="col-lg-3 control-label ">Kilometraje vehículo:</label>
                    <label class="col-lg-4">
                        <input value="{{ ($transferencia_data != null)? $transferencia_data->kilometraje : 0 }}" type="number" name="kilometraje" id="kilometraje" class="form-control">
                    </label>
                </div>

                <div class="form-group">
                    <label for="rutEmisorTransf" class="col-lg-3 control-label ">Rut emisor:</label>
                    <label class="col-lg-4">
                        <input value="{{ ($transferencia_data != null)? $transferencia_data->rut_emisor : '' }}" type="text" name="rutEmisorTransf" id="rutEmisorTransf" class="form-control rut4">
                    </label>
                </div>

                <fieldset>
                    <legend>Datos del impuesto a la transferencia</legend>

                    <div class="form-group">
                        <label for="codigoCID" class="col-lg-3 control-label "><span style="color:#f00">(*)</span>Código CID del pago:</label>
                        <label class="col-lg-4">
                            <input value="{{ ($transferencia_data != null)? $transferencia_data->codigo_cid : 0 }}" type="text" name="codigoCID" id="codigoCID" class="form-control">
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="montoPagadoImpuesto" class="col-lg-3 control-label "><span style="color:#f00">(*)</span>Monto pagado:</label>
                        <label class="col-lg-4">
                            <input value="{{ ($transferencia_data != null)? $transferencia_data->monto_impuesto : 0 }}" type="number" name="montoPagadoImpuesto" id="montoPagadoImpuesto" class="form-control">
                        </label>
                    </div>
                </fieldset>
            </div>
            @if($reingreso != null)
                <div class="col-md-12 col-lg-12">
                    <fieldset>
                        <legend>Datos de reingreso</legend>
                        <div class="row">
                            <label for="nroResExenta" class="col-lg-3 control-label ">Número resolución exenta:</label>
                            <label class="col-lg-4">
                                <input type="input" value="" name="nroResExenta" id="nroResExenta" class="form-control" min="1" max="99999999">
                            </label>
                        </div>
                        <div class="row">
                            <label for="fechaResExenta" class="col-lg-3 control-label ">Fecha resolución exenta:</label>
                            <label class="col-lg-4">
                                <input type="input" value="" name="fechaResExenta" id="fechaResExenta" class="form-control fechaRechazos">
                            </label>
                        </div>

                        <div class="row">
                            <label for="fechaSolRech" class="col-lg-3 control-label ">Fecha solicitud rechazada:</label>
                            <label class="col-lg-4">
                                <input type="input" value="" name="fechaSolRech" id="fechaSolRech" class="form-control fechaRechazos">
                            </label>
                        </div>

                        <script>
                            $(".fechaRechazos").datepicker({
                                language: 'es',
                                dateFormat: 'yymmdd',
                                changeMonth: true, 
                                changeYear: true
                            });
                        </script>
                    </fieldset>
                </div>
            @endif
        </div>
        <div class="panel-footer">
            @if(Auth::user()->rol_id == 1 || Auth::user()->rol_id == 3)
                <button id="enviaSolicitud1" type="submit" class="btn btn-system"><li class="fa fa-save"></li>  Enviar Solicitud a RC y Continuar</button>
            @else
                <button id="enviaSolicitud1" type="submit" class="btn btn-system"><li class="fa fa-save"></li>  Grabar datos y continuar </button>
            @endif
        </div>
    </div>

</form>

<script>
    $(document).ready(function () { 

        var rut_format = $("#rutEmisorTransf").val();
        console.log(rut_format);
        if(rut_format.trim() != ''){
            rut_format = rut_format + $.computeDv(rut_format);
            $("#rutEmisorTransf").val($.formatRut(rut_format)); 
        }

        $(".rut4").rut({
            formatOn: 'keyup',
            minimumLength: 8,
            validateOn: 'change'
        });

        $(".rut4").rut().on('rutInvalido', function(e) {
            new PNotify({
                title: 'Rut de Emisor',
                text: 'El Rut ingresado no es válido.',
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
        });

        $('.comuna').multiselect({
            enableCaseInsensitiveFiltering: true,
            enableFiltering: true
        });

        $('#tipoDocTransf').change(function() {
            var tipoDocTransf = $(this).val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('traeNaturalezasporTipoDoc') }}",
                data:{
                    tipoDocTransf:tipoDocTransf,
                },
                type:'POST',
                success: function(data){
                    $("#naturalezaTransf").html(data);
                }
            });
        });

        $('#tipoDocTransf').change();

        $('#DocumentoTransferencia').on('click', function() {
            $('#Documento_Transferencia').trigger('click');
        });

        $('#Documento_Transferencia').on('change', function() {
            $('#lbl_Documento_Transferencia').text($('#Documento_Transferencia').val());
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


    $(".datepicker").datepicker({
        language: 'es',
        dateFormat: 'yymmdd',
        changeMonth: true, 
        changeYear: true
    });

    $(document).on("submit","#formDataResumen",function(e){
        $("#enviaSolicitud1").prop("disabled",true);
        showOverlay();
        e.preventDefault();
        let formData = new FormData(document.getElementById("formDataResumen"));

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "/transferencia/{{ $id }}/updateDataTransferencia",
            type: "post",
            data: formData,
            processData: false,
            contentType: false,
            error: function(jqXHR, textStatus, errorThrown) {
                hideOverlay();
                console.log('error');
                $("#enviaSolicitud1").prop("disabled",false);
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
            success: function(data){
                $("#enviaSolicitud1").prop("disabled",false);
                hideOverlay();
                try {
                    let datos = JSON.stringify(data);
                    datos = JSON.parse(datos);
                    var isJson = true;
                } catch (e) {
                    var isJson = false;
                }

                if(isJson) {
                    data = JSON.stringify(data);
                    var jsonData = JSON.parse(data);
                    if(jsonData.status == "OK"){
                        console.log('1');
                        $("#pills-docs").html(jsonData.html);
                        $("#pills-docs").toggleClass('show');
                        $("#pills-docs").removeClass('hide');
                        $("#pills-docs").addClass('show');
                        $("#pills-invoice").toggleClass('show');
                        $("#pills-invoice").removeClass('show');
                        $("#pills-invoice").addClass('hide');
                        $("#pills-docs-tab").attr("href","#pills-docs");
                        $("#pills-docs-tab").toggleClass('disabled');
                        $("#pills-docs-tab").attr("aria-disabled",false);
                        $("#pills-docs-tab").click();
                    }
                    else{
                        console.log('2');
                        var errors = jsonData.errors;
                        for (var errorKey in errors) {
                            if (errors.hasOwnProperty(errorKey)) {
                                var messages = errors[errorKey];
                                for (var i = 0; i < messages.length; i++) {
                                    new PNotify({
                                        title: 'Error',
                                        text: messages[i],
                                    });
                                }
                            }
                        }
                    }
                    
                } else {
                    console.log('3');
                    $("#pills-docs").html(data);
                    $("#pills-docs").toggleClass('show');
                    $("#pills-docs").removeClass('hide');
                    $("#pills-docs").addClass('show');
                    $("#pills-invoice").toggleClass('show');
                    $("#pills-invoice").removeClass('show');
                    $("#pills-invoice").addClass('hide');
                    $("#pills-docs-tab").attr("href","#pills-docs");
                    $("#pills-docs-tab").toggleClass('disabled');
                    $("#pills-docs-tab").attr("aria-disabled",false);
                    $("#pills-docs-tab").click();

                }
            }
        });
    });
</script>