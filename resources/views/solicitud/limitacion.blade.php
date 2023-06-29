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
use App\Models\LimitacionRC;
use App\Models\Limitacion;
use App\Models\ErrorEnvioDoc;

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
            $limitacion_rc = LimitacionRC::getSolicitud($id);
            $limitacion = Limitacion::where('solicitud_id',$id)->first();
            $error_envio_doc = ErrorEnvioDoc::where('solicitud_id',$id)->first();
        ?>
        @if(count($limitacion_rc) == 0)
        <div class="panel-body">
            <div class="form-group">
                <div class="row"><div class="col-lg-4"></div><div class="col-lg-4"><h4>Datos Acreedor</h4></div></div>
                <div class="row">
                    <label for="runAcreedor" class="col-lg-3 control-label ">Seleccione Acreedor:</label>
                    <label class="col-lg-3">
                        <select name="runAcreedor" id="runAcreedor" class="form-control" required>
                            <option value="0">Seleccione Acreedor</option>
                            @foreach($acreedores as $acre)
                                <option value="{{$acre->rut}}" @if($limitacion != null) @if($limitacion->acreedor->rut == $acre->rut) selected  @endif @endif>{{$acre->nombre}}</option>
                            @endforeach
                        </select>


                        <input type="hidden" name="nombreRazon" id="nombreRazon" class="form-control" required value="{{ ($limitacion != null)? $limitacion->acreedor->nombre: '' }}">
                    </label>
                </div>
                <div class="row"><div class="col-lg-4"></div><div class="col-lg-4"><h4>Datos Vehiculo</h4></div></div>
                <div class="row">
                    <label for="nro_chasis" class="col-lg-3 control-label ">Nro Chasis:</label>
                    <label class="col-lg-3">
                        <input type="text" name="nro_chasis" id="nro_chasis" value="{{ ($limitacion == null)?  ((isset($factura->nro_chasis))? $factura->nro_chasis : ''): $limitacion->nro_chasis}}" class="form-control" required>
                    </label>
                </div>
                <div class="row">
                    <label for="nro_serie" class="col-lg-3 control-label ">Nro Serie:</label>
                    <label class="col-lg-3">
                        <input type="text" name="nro_serie" id="nro_serie" value="{{ ($limitacion == null)?  ((isset($factura->nro_serie))? $factura->nro_serie : ''): $limitacion->nro_serie}}" class="form-control">
                    </label>
                </div>
                <div class="row">
                    <label for="nro_vin" class="col-lg-3 control-label ">Nro Vin:</label>
                    <label class="col-lg-3">
                        <input type="text" name="nro_vin" id="nro_vin" value="{{ ($limitacion == null)? ((isset($factura->nro_vin))? $factura->nro_vin : ''): $limitacion->nro_vin}}" class="form-control" >
                    </label>
                </div>
                <div class="row">
                    <label for="nro_motor" class="col-lg-3 control-label ">Nro Motor:</label>
                    <label class="col-lg-3">
                        <input type="text" name="nro_motor" id="nro_motor" value="{{ ($limitacion == null)? ((isset($factura->motor))? $factura->motor : ''): $limitacion->nro_motor}}" class="form-control" required>
                    </label>
                </div>
                <div class="row"><div class="col-lg-4"></div><div class="col-lg-4"><h4>Datos Documento</h4></div></div>
                <div class="row">
                    <label for="folio" class="col-lg-3 control-label ">Folio:</label>
                    <label class="col-lg-3">
                        <input type="number" min="1" max="99999999" name="folio" id="folio" value="{{ ($limitacion != null)? $limitacion->folio : '' }}" class="form-control" required>
                    </label>
                </div>
                <div class="row">
                    <label for="folio" class="col-lg-3 control-label ">Tipo Documento:</label>
                    <label class="col-lg-3">
                        <select name="tipoDoc" id="tipoDoc2" class="form-control" required>
                            @foreach($tipo_documento as $tipo)
                                <option value="{{trim($tipo->name)}}" @if($limitacion != null)  @if($limitacion->tipodocumento->name == $tipo->name) selected  @endif  @endif>{{$tipo->name}}</option>
                            @endforeach

                        </select>
                    </label>
                </div>
                <div class="row">
                    <label for="autorizante" class="col-lg-3 control-label ">Autorizante:</label>
                    <label class="col-lg-3">
                        <input type="input" name="autorizante" id="autorizante" value="{{ ($limitacion != null)? $limitacion->autorizante : '' }}" class="form-control">
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
        @else
            @if($error_envio_doc != null)
                <div class="row">
                    <div class="col-lg-3">Adjunte documento fundante nuevamente:</div>
                    <div class="col-lg-3">
                        <span class="btn btn-warning fileinput-button col-sm-12" name="DocLim2" id="DocLim2">
                            Seleccionar Documento</span>
                    </div>
                    <div class="col-lg-3">
                        <input id="Doc_Lim2" name="Doc_Lim2" type="file" style="display:none" accept="application/pdf" />
                        <label id="lbl_Doc_Lim2"></label>
                    </div>
                </div>    

                <button type="button" class="btn btn-system btnReenviaArchivoLimitacion"><li class="fa fa-send"></li>  Enviar archivo </button>
            @endif

            <ul>
            @foreach($limitacion_rc as $lim)
                <li>N° Solicitud Limitación: {{$lim->numSol}}</li>
                <li>PPU: {{$lim->ppu}}</li>
                <li>Fecha: {{$lim->fecha}}</li>
                <li>Hora: {{$lim->hora}}</li>
                <li>Tipo Solicitud: {{$lim->tipoSol}}</li>
            @endforeach
            </ul>
            <button type="button" data-toggle="modal" data-target="#modal_limitacion" data-garantizaSol="{{$id}}" data-numsol="{{ $limitacion_rc[0]->numSol }}" class="btn btn-success btnRevisaLimitacion">Consultar Estado Online</button>
            <button type="button" class="btn btn-primary btnReingresaLimitacion" onclick="$('.formReingresaLimitacion').slideToggle();">Reingresar limitación</button>

            <div class="formReingresaLimitacion" style="display:none;">
                <div class="panel-body">
                    <div class="form-group">
                        <div class="row"><div class="col-lg-4"></div><div class="col-lg-4"><h4>Datos Acreedor</h4></div></div>
                        <div class="row">
                            <label for="runAcreedor" class="col-lg-3 control-label ">Seleccione Acreedor:</label>
                            <label class="col-lg-3">
                                <select name="runAcreedor" id="runAcreedor" class="form-control" required>
                                    <option value="0">Seleccione Acreedor</option>
                                    @foreach($acreedores as $acre)
                                        <option value="{{$acre->rut}}" @if($limitacion != null) @if($limitacion->acreedor->rut == $acre->rut) selected  @endif @endif>{{$acre->nombre}}</option>
                                    @endforeach
                                </select>
        
        
                                <input type="hidden" name="nombreRazon" id="nombreRazon" class="form-control" required>
                            </label>
                        </div>
                        <div class="row"><div class="col-lg-4"></div><div class="col-lg-4"><h4>Datos Vehiculo</h4></div></div>
                        <div class="row">
                            <label for="nro_chasis" class="col-lg-3 control-label ">Nro Chasis:</label>
                            <label class="col-lg-3">
                                <input type="text" name="nro_chasis" id="nro_chasis" value="{{ ($limitacion == null)?  $factura->nro_chasis : $limitacion->nro_chasis}}" class="form-control" required>
                            </label>
                        </div>
                        <div class="row">
                            <label for="nro_serie" class="col-lg-3 control-label ">Nro Serie:</label>
                            <label class="col-lg-3">
                                <input type="text" name="nro_serie" id="nro_serie" value="{{ ($limitacion == null)?  $factura->nro_serie : $limitacion->nro_serie}}" class="form-control">
                            </label>
                        </div>
                        <div class="row">
                            <label for="nro_vin" class="col-lg-3 control-label ">Nro Vin:</label>
                            <label class="col-lg-3">
                                <input type="text" name="nro_vin" id="nro_vin" value="{{ ($limitacion == null)? $factura->nro_vin : $limitacion->nro_vin}}" class="form-control" required>
                            </label>
                        </div>
                        <div class="row">
                            <label for="nro_motor" class="col-lg-3 control-label ">Nro Motor:</label>
                            <label class="col-lg-3">
                                <input type="text" name="nro_motor" id="nro_motor" value="{{ ($limitacion == null)? $factura->motor : $limitacion->nro_motor}}" class="form-control" required>
                            </label>
                        </div>
                        <div class="row"><div class="col-lg-4"></div><div class="col-lg-4"><h4>Datos Documento</h4></div></div>
                        <div class="row">
                            <label for="folio" class="col-lg-3 control-label ">Folio:</label>
                            <label class="col-lg-3">
                                <input type="number" min="1" max="99999999" name="folio" id="folio" value="{{ ($limitacion != null)? $limitacion->folio : '' }}" class="form-control" required>
                            </label>
                        </div>
                        <div class="row">
                            <label for="folio" class="col-lg-3 control-label ">Tipo Documento:</label>
                            <label class="col-lg-3">
                                <select name="tipoDoc" id="tipoDoc2" class="form-control" required>
                                    @foreach($tipo_documento as $tipo)
                                        <option value="{{trim($tipo->name)}}"  @if($limitacion != null)  @if($limitacion->tipodocumento->name == $tipo->name) selected  @endif  @endif>{{$tipo->name}}</option>
                                    @endforeach
        
                                </select>
                            </label>
                        </div>
                        <div class="row">
                            <label for="autorizante" class="col-lg-3 control-label ">Autorizante:</label>
                            <label class="col-lg-3">
                                <input type="input" name="autorizante" id="autorizante" value="{{ ($limitacion != null)? $limitacion->autorizante : '' }}" class="form-control">
                            </label>
                        </div>
                        
                        <div class="row">
                            <label for="nroResExenta" class="col-lg-3 control-label ">Número resolución exenta:</label>
                            <label class="col-lg-3">
                                <input type="input" name="nroResExenta" id="nroResExenta" value="" class="form-control">
                            </label>
                        </div>

                        <div class="row">
                            <label for="fechaResExenta" class="col-lg-3 control-label ">Fecha resolución exenta:</label>
                            <label class="col-lg-3">
                                <input type="input" name="fechaResExenta" id="fechaResExenta" value="" class="form-control fechaRechazos">
                            </label>
                        </div>

                        <div class="row">
                            <label for="fechaSolRech" class="col-lg-3 control-label ">Fecha solicitud rechazada:</label>
                            <label class="col-lg-3">
                                <input type="input" name="fechaSolRech" id="fechaSolRech" value="" class="form-control fechaRechazos">
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




        @endif
    </div>

    <div class="modal fade" id="modal_limitacion" tabindex="-1" role="dialog" aria-labelledby="modal_limitacionLabel" aria-hidden="true">
        <div class="modal-dialog" role="document" style="min-width:450px;">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Estado Solicitud</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body" id="modal_limitacion_body">
              
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
          </div>
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

        $('#DocLim2').on('click', function() {
            $('#Doc_Lim2').trigger('click');
        });

        $('#Doc_Lim2').on('change', function() {
            $('#lbl_Doc_Lim2').text($('#Doc_Lim2').val());
        });

        $("#runAcreedor").change();
    });

    $(document).on("change","#runAcreedor",function(){
        if(parseFloat($(this).val()) != 0){
            $("#nombreRazon").val($("#runAcreedor option:selected").text());
        }
        else{
            $("#nombreRazon").val('');
        }


    });

    $(document).on("click",".btnRevisaLimitacion",function(e){
        e.preventDefault();
        showOverlay();
        let numSolRC = $(this).data('numsol');
        let numSolGarantiza = $(this).data('garantizasol');

        $.ajax({
            url: "/solicitud/"+numSolGarantiza+"/limitacion/verEstadoSolicitud",
            type: "post",
            data: {
                id_solicitud_rc: numSolRC,
                _token: "{{ csrf_token() }}"
            },
            success: function(data){
                hideOverlay();
                $("#modal_limitacion_body").html(data);
            }
        })

    });

    $(document).on("click", ".btnDescargaComprobanteLimi", function(e) {
        showOverlay();
        e.preventDefault();
        let numSolRC = $(this).data('numsol');
        let numSolGarantiza = $(this).data('garantizasol');

        fetch("/solicitud/" + numSolGarantiza + "/descargaComprobanteLimi", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                id_solicitud_rc: numSolRC
            })
        })
        .then(function(response) {
            hideOverlay();
            if (response.ok) {
                if (response.headers.get('Content-Type') === 'application/pdf') {
                    return response.blob();
                } else {
                    return response.json();
                }
            } else {
                throw new Error('Error en la petición');
            }
        })
        .then(function(data) {
            if (data instanceof Blob) {
                var blob = new Blob([data], {
                    type: 'application/pdf'
                });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'voucher.pdf';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            } else {
                showErrorNotification(data.error);
            }
        })
        .catch(function(error) {
            hideOverlay();
            showErrorNotification(error.message);
        });
    });

    $(document).on("click",".btnReenviaArchivoLimitacion",function(e){
        showOverlay();
        e.preventDefault();
        let formData = new FormData();
        let inputFile = document.querySelector('#Doc_Lim2');

        if (inputFile.files.length > 0) {
            let file = inputFile.files[0]; // Obtener el primer archivo seleccionado
            formData.append('Doc_Lim2', file, file.name);
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "{{route('solicitud.limitacion.reenviarArchivo',['id' => $id])}}",
            type: "post",
            data: formData,
            processData: false,
            contentType: false,
            success: function(data){
                hideOverlay();
                let json = JSON.parse(data);
                if(json.status == "ERROR"){
                    new PNotify({
                        title: 'Inscribir limitación',
                        text: json.msj,
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
                        width: '290px'
                    });
                    return false;
                }
                else{
                    new PNotify({
                        title: 'Inscribir limitación',
                        text: json.msj,
                        shadow: true,
                        opacity: '0.75',
                        addclass: 'stack_top_right',
                        type: 'success',
                        stack: {
                            "dir1": "down",
                            "dir2": "left",
                            "push": "top",
                            "spacing1": 10,
                            "spacing2": 10
                        },
                        width: '290px'
                    });
                    return true;
                }
            }
        });

    });


    $(document).on("submit","#formLimitacion",function(e){
        showOverlay();
        e.preventDefault();
        let formData = new FormData(document.getElementById("formLimitacion"));
             
        // Obtener el elemento de entrada de archivos y verificar si se seleccionó un archivo
        /*
        var inputFile = $("#Doc_Lim")[0];
        if (inputFile.files.length === 0) {
            new PNotify({
                title: 'Inscribir limitación',
                text: 'Debe adjuntar el archivo con el tipo de documento seleccionado',
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
            hideOverlay();
            return false;
        }*/

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
            hideOverlay();
            return false;

        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "@php  if($acceso == 'ingreso'){ 
                            echo '/solicitud/'.$id.'/limitacion/new';
                        }elseif($acceso == 'revision'){ 
                            echo '/solicitud/'.$id.'/limitacion/new/revision';
                        }
                  @endphp",
            type: "post",
            data: formData,
            processData: false,
            contentType: false,
            success: function(data){
                hideOverlay();
                let json = JSON.parse(data);
                if(json.status == "ERROR"){
                    new PNotify({
                        title: 'Inscribir limitación',
                        text: json.msj,
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
                        width: '290px'
                    });
                    return false;
                }
                else{
                    new PNotify({
                        title: 'Inscribir limitación',
                        text: json.msj,
                        shadow: true,
                        opacity: '0.75',
                        addclass: 'stack_top_right',
                        type: 'success',
                        stack: {
                            "dir1": "down",
                            "dir2": "left",
                            "push": "top",
                            "spacing1": 10,
                            "spacing2": 10
                        },
                        width: '290px'
                    });
                    return true;
                }
            }
        });
    });


</script>

<script>
    $(".fechaRechazos").datepicker({
        language: 'es',
        dateFormat: 'yymmdd',
        changeMonth: true, 
        changeYear: true
    });
</script>