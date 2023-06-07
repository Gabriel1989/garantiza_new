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
$reingreso = Reingreso::where('transferencia_id',$id)->first();
$tipoDocs = Tipo_Documento::whereIn('id',[9,10,11,12,13,14])->get();
?>

@include('includes.form-error-message')
<form method="post" id="formDataResumen" role="form" class="form-horizontal form-revision" >
    @csrf
    @method('post')
    <div class="panel panel-info panel-border top">
        <div class="panel-heading">
            <span class="panel-title">Ingreso de Solicitud N° {{$id}} - Datos adicionales para transferencia</span>
        </div>
        <div class="panel-body">
            <div class="col-lg-6 col-md-6">
                <div class="form-group">
                    <label for="digitoVerificadorPPU" class="col-lg-3 control-label ">Digito verificador PPU:</label>
                    <label class="col-lg-4">
                        <input type="text" name="digitoVerificadorPPU" id="digitoVerificadorPPU" class="form-control" maxlength="1">
                    </label>
                </div>
                <div class="form-group">
                    <label for="tipoDocTransf" class="col-lg-3 control-label ">Tipo Documento:</label>
                    <label class="col-lg-4">
                        <select name="tipoDocTransf" id="tipoDocTransf" class="form-control"  required>
                            @foreach ($tipoDocs as $doc)
                                <option value="{{ $doc->name }}">{{ $doc->name }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>
                <div class="form-group">
                    <label for="naturalezaTransf" class="col-lg-3 control-label ">Naturaleza del acto:</label>
                    <label class="col-lg-4">
                        <select name="naturalezaTransf" id="naturalezaTransf" class="form-control"  required>
                        </select>
                    </label>
                </div>
                <div class="form-group">
                    <label for=" numDocTransf" class="col-lg-3 control-label ">Número de documento:</label>
                    <label class="col-lg-4">
                        <input type="number" name="numDocTransf" id="numDocTransf" class="form-control"  required>
                        
                    </label>
                </div>
                <div class="form-group">
                    <label for="fechaEmisionTransf" class="col-lg-3 control-label ">Fecha emisión documento o factura:</label>
                    <label class="col-lg-4">
                        <input type="text" name="fechaEmisionTransf" id="fechaEmisionTransf" class="form-control datepicker"  required>
                    </label>
                </div>

                <div class="form-group ml50">
                    <label for="comuna3" class="col-lg-2 control-label">Lugar :</label>
                        <label class="col-lg-4">
                            <select class="col-sm-12 form-select comuna" name="lugarTransf" id="comuna3">
                                <option value="0">Seleccione Comuna...</option>
                                @foreach ($comunas as $item)
                                    <option value="{{ $item->Codigo }}">{{ $item->Nombre }}</option>
                                @endforeach
                            </select>
                        </label>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="form-group">
                    <label for="totalTransf" class="col-lg-3 control-label ">Total Venta:</label>
                    <label class="col-lg-4">
                        <input type="number" name="totalTransf" id="totalTransf" class="form-control"  required>
                        
                    </label>
                </div>

                <div class="form-group ml50">
                    <label for="monedaTransf" class="col-lg-2 control-label">Moneda :</label>
                        <label class="col-lg-5">
                            <select class="col-sm-12 form-select" name="monedaTransf" id="monedaTransf">
                                <option value="0">Seleccione Moneda...</option>
                                <option value="$">Peso</option>
                                <option value="US">Dólar</option>
                                <option value="UF">Unidad de Fomento</option>
                            </select>
                        </label>
                </div>

                <div class="form-group">
                    <label for="kilometraje" class="col-lg-3 control-label ">Kilometraje vehículo:</label>
                    <label class="col-lg-4">
                        <input type="number" name="kilometraje" id="kilometraje" class="form-control">
                    </label>
                </div>

                <div class="form-group">
                    <label for="rutEmisorTransf" class="col-lg-3 control-label ">Rut emisor:</label>
                    <label class="col-lg-4">
                        <input type="text" name="rutEmisorTransf" id="rutEmisorTransf" class="form-control rut4">
                    </label>
                </div>

                <fieldset>
                    <legend>Datos del impuesto a la transferencia</legend>

                    <div class="form-group">
                        <label for="codigoCID" class="col-lg-3 control-label ">Código CID del pago:</label>
                        <label class="col-lg-4">
                            <input type="text" name="codigoCID" id="codigoCID" class="form-control">
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="montoPagadoImpuesto" class="col-lg-3 control-label ">Monto pagado:</label>
                        <label class="col-lg-4">
                            <input type="number" name="montoPagadoImpuesto" id="montoPagadoImpuesto" class="form-control">
                        </label>
                    </div>
                </fieldset>
            </div>
        </div>
        <div class="panel-footer">
            <button id="enviaSolicitud1" type="submit" class="btn btn-system"><li class="fa fa-save"></li>  Grabar y Continuar Revisión </button>
        </div>
    </div>

</form>

<script>
    $(document).ready(function () { 

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
                $("#pills-docs").html(data);
                $("#pills-docs").toggleClass('show');
                $("#pills-home").removeClass('show');
                $("#pills-contact").removeClass('show');
                $("#pills-profile").removeClass('show');
                $("#pills-invoice").removeClass('show');
                $("#pills-voucher").removeClass('show');
                $("#pills-pay").removeClass('show');
                $("#pills-docs-tab").attr("href","#pills-docs");
                $("#pills-docs-tab").toggleClass('disabled');
                $("#pills-docs-tab").attr("aria-disabled",false);
                $("#pills-docs-tab").click();
            }
        });
    });
</script>