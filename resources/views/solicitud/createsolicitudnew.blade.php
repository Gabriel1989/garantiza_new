<form enctype="multipart/form-data" id="form-solicitud-create" class="form-documentos form-horizontal form-solicitud" old-action="{{route('solicitud.store')}}" method="POST">
    @csrf
    @method('POST')
    <input type="hidden" name="region_selected" id="region_selected" value="{{ isset($region_selected)? $region_selected : $solicitud_data->region_id}}">
    <input type="hidden" name="id_tipo_placa_patente" id="id_tipo_placa_patente" value="{{ isset($id_tipo_placa)? $id_tipo_placa : $solicitud_data->id_tipo_placa_patente}}">
    <input type="hidden" name="solicitud_id" id="solicitud_id" value="{{ isset($id_solicitud)? $id_solicitud :  0}}">
    <div class="panel panel-info panel-border top">
        <div class="panel-heading">
            <span class="panel-title">Crear Nueva Solicitud</span>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label for="name" class="col-lg-1 control-label">Ejecutivo: </label>
                <label class="col-lg-5">
                    <p class="form-control-static text-muted">{{Auth::user()->name}}</p>
                </label>
                @php 
                    use App\User;
                    $user = User::with('concesionaria')->find(Auth::id());
                @endphp

                <label for="sucursal_id" class="col-lg-1 control-label">Sucursal: </label>
                <div class="col-lg-5">
                    <select name="sucursal_id" id="sucursal_id">
                        <option value="0" selected>Seleccione Sucursal ...</option>
                        @foreach ($sucursales as $item)
                        <option value="{{$item->id}}" @if(!@is_null($solicitud_data->sucursal_id)) @if($solicitud_data->sucursal_id==$item->id) selected  @endif @endif>{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="tipoVehiculos_id" class="col-lg-1 control-label">Tipo de Vehículo: </label>
                <div class="col-lg-5">
                    <select name="tipoVehiculos_id" id="tipoVehiculos_id">
                        <option value="0" selected>Seleccione Tipo de Vehículo ...</option>
                        @foreach ($tipo_vehiculos as $item)
                        <option value="{{$item->id}}"  @if(!@is_null($solicitud_data->tipoVehiculos_id))  @if($solicitud_data->tipoVehiculos_id==$item->id) selected   @endif   @endif>{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>

                {{-- </div>
                <div class="form-group"> --}}
                <div class="col-sm-6 col-lg-6 mb5">
                    <div class="col-lg-5">
                        <span class="btn btn-warning fileinput-button col-sm-12" name="pic" id="FacturaXML">
                            Seleccionar Factura PDF</span>
                    </div>
                    <div class="col-lg-5">
                        <input id="Factura_XML" name="Factura_XML" type="file" style="display:none" accept="text/xml,application/pdf" />
                        <label id="lbl_Factura_XML"></label>
                    </div>
                </div>
                
            </div>
            <div class="form-group">
                <label for="ppu_terminacion" class="col-lg-1 control-label">PPU Disponibles:</label>
                <label class="col-lg-5">
                    <select name="ppu_terminacion" id="ppu_terminacion">
                        @if(@is_null($solicitud_data->termino_1)) 
                            <option value="" selected>Seleccione PPU ...</option>
                            @foreach ($ppu as $item)
                            <option value="{{$item['Terminacion']}}">{{$item['Terminacion']}}</option>
                            @endforeach
                        @else
                        <option value="{{$solicitud_data->termino_1}}">{{$solicitud_data->termino_1}}</option>
                        @endif
                    </select>
                </label>
            </div>

            <div class="form-group">
                <div class="col-sm-12 col-lg-12 mb5">
                    @if(!empty($solicitud_data))
                        @php
                            $documentos_solicitud = $solicitud_data->documentos;
                            if(!empty($documentos_solicitud)){
                                echo '<table id="tableDocs" class="table table-bordered"><thead>
                                        <tr>
                                            <th>Nombre Archivo</th>
                                            <th>Tipo</th>
                                            <th>Enviado a RC</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead><tbody id="tableDocsBody">';
                                foreach($documentos_solicitud as $docs){
                                    echo '<tr id="'.$docs->name.'"><td>';
                                    $status_doc_rc = '';
                                    if($docs->documento_rc_coteja != null){
                                        $status_doc_rc = '<div style="border-radius:30px;color:green;"><i class="fa fa-check"></i></div>';
                                    }   
                                    else{
                                        $status_doc_rc = '<div style="border-radius:30px;color:#f00;"><i class="fa fa-times"></i></div>';
                                    }    
                                    //if($docs->description== "Factura en PDF"){
                                        echo '<a target="_blank" href="'.url(str_replace("public/","storage/",$docs->name)).'">'.url(str_replace("public/","storage/",$docs->name)).'</a>';
                                    //}
                                    echo '</td><td>'.$docs->description.'</td><td>'.$status_doc_rc.'</td><td><button class="btn btn-danger eliminarArchivoDoc" data-solicitudid="'.$solicitud_data->id.'" data-docname="'.$docs->name.'"><i class="fa fa-trash"></i></button></td></tr>';
                                }
                                echo '</tbody></table>';
                            }
                        @endphp
                    @endif
                </div>
            </div>
            <div class="panel panel-info panel-border top">
                <div class="panel-heading" role="button">
                    <span class="panel-title" style="cursor:pointer;">Ingresar datos factura</span>
                </div>
                <div class="panel-body">

                    <br>
                    <div class="row">

                        <div class="col-lg-3">
                            <label>Nombre o Razón Social Emisor</label>
                            <input class="form-control" name="razon_soc_emisor" id="razon_soc_emisor" maxlength="35" value="{{ !@is_null($header->RznSoc)? $header->RznSoc : $user->concesionaria->razon_social}}">
                        </div>

                        <div class="col-lg-3">
                            <label>Rut emisor</label>
                            <input type="number" min="1" max="99999999" class="form-control" name="rut_emisor" id="rut_emisor" value="{{!@is_null($header->RUTEmisor)? $header->RUTEmisor : $user->concesionaria->rut}}">
                        </div>

                        <div class="col-lg-3">
                            <label>Fecha Emisión</label>
                            <input class="form-control" maxlength="8" name="fecha_emision_fac" id="fecha_emision_fac" value="{{!@is_null($header->FchEmis)? $header->FchEmis : ''}}">
                        </div>
                        <div class="col-lg-3">
                            <label>Monto Total Factura</label>
                            <input type="number" max="999999999" class="form-control" name="monto_factura" id="monto_factura" value="{{!@is_null($header->MntTotal)? $header->MntTotal : ''}}">
                        </div>
                    </div>
                    <div class="row">


                    </div>
                </div>
            </div>
        </div>

        <div class="panel-footer">
            <button type="submit" class="btn btn-system">
                <li class="fa fa-upload"></li> Crear Solicitud y Continuar
            </button>
        </div>
    </div>
</form>
<script type="text/javascript">
$(document).ready(function() {

    $('#sucursal_id').multiselect();
    $('#tipoVehiculos_id').multiselect();

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

    $("#fecha_emision_fac").datepicker({
        language: 'es',
        dateFormat: 'yymmdd',
        changeMonth: true, 
        changeYear: true
    });

    $('#FacturaXML').on('click', function() {
        $('#Factura_XML').trigger('click');
    });

    $('#Factura_XML').on('change', function() {
        $('#lbl_Factura_XML').text($('#Factura_XML').val());
    });

    $(document).on("click",".eliminarArchivoDoc",function(e){
        e.preventDefault();
        var doc_name = $(this).data('docname');
        var solicitud_id = $(this).data('solicitudid');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });      
        console.log("#".doc_name);  
        $.ajax({
            url: "@php  if(Auth::user()->rol_id >= 4){ 
                            echo route('documento.destroy');
                        }elseif(Auth::user()->rol_id <= 3){ 
                            echo  route('documento.destroy.revision');
                        }
                  @endphp",
            data: {
                solicitud_id : $(this).data('solicitudid'),
                doc_name : doc_name,
                _token: "{{ csrf_token() }}"
            },
            type: "POST",
            success: function(data){
                $.ajax({
                        url: "/documento/"+solicitud_id+"/get",
                        type: "get",
                        success: function(data2) {
                            let jsondata = JSON.parse(data2);
                            let html = jsondata.data;
                            $("#tableDocsBody").html(html);
                        }

                    });
            }
        });

    });

});

</script>