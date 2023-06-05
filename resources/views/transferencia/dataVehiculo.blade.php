
<form enctype="multipart/form-data" id="form-creasolicitud-transf" method="POST">
    @csrf
    @method('POST')
    <input type="hidden" name="transferencia_id" id="transferencia_id" value="{{ isset($id_transferencia)? $id_transferencia :  0}}">
    <div class="panel panel-info panel-border top">

        <div class="panel-heading">
            <span class="panel-title">Datos del vehículo de acuerdo a PPU consultada</span>
        </div>


        <div class="panel-body">
            <div class="row">
                <div class="col-lg-6">
                    <fieldset>
                        <legend>Datos del vehículo</legend>
                        <div class="form-group">
                            <label>PPU</label>
                            <input type="text" class="form-control" name="ppu_transf" id="ppu_transf" value="{{isset($salida['certificadoAnotacionesVigentePatente']['datosVehiculo']['ppu'])? $salida['certificadoAnotacionesVigentePatente']['datosVehiculo']['ppu']: ''}}">
                        </div>

                        <div class="form-group">
                            <label>Tipo Vehículo</label>
                            <input type="text" class="form-control" name="tipo_transf" id="tipo_transf" value="{{isset($salida['certificadoAnotacionesVigentePatente']['datosVehiculo']['tipo'])? $salida['certificadoAnotacionesVigentePatente']['datosVehiculo']['tipo']: ''}}">
                        </div>

                        <div class="form-group">
                            <label>Año</label>
                            <input type="text" class="form-control" name="anio_transf" id="anio_transf" value="{{isset($salida['certificadoAnotacionesVigentePatente']['datosVehiculo']['aFabrica'])? $salida['certificadoAnotacionesVigentePatente']['datosVehiculo']['aFabrica']: ''}}">
                        </div>

                        <div class="form-group">
                            <label>Marca</label>
                            <input type="text" class="form-control" name="marca_transf" id="marca_transf" value="{{isset($salida['certificadoAnotacionesVigentePatente']['datosVehiculo']['marca'])? $salida['certificadoAnotacionesVigentePatente']['datosVehiculo']['marca']: ''}}">
                        </div>
                        
                        <div class="form-group">
                            <label>Modelo</label>
                            <input type="text" class="form-control" name="modelo_transf" id="modelo_transf" value="{{isset($salida['certificadoAnotacionesVigentePatente']['datosVehiculo']['modelo'])? $salida['certificadoAnotacionesVigentePatente']['datosVehiculo']['modelo']: ''}}">
                        </div>

                        <div class="form-group">
                            <label>Chasis</label>
                            <input type="text" class="form-control" name="chasis_transf" id="chasis_transf" value="{{isset($salida['certificadoAnotacionesVigentePatente']['datosVehiculo']['chasis'])? $salida['certificadoAnotacionesVigentePatente']['datosVehiculo']['chasis']: ''}}">
                        </div>

                        <div class="form-group">
                            <label>Motor</label>
                            <input type="text" class="form-control" name="motor_transf" id="motor_transf" value="{{isset($salida['certificadoAnotacionesVigentePatente']['datosVehiculo']['numeroMotor'])? $salida['certificadoAnotacionesVigentePatente']['datosVehiculo']['numeroMotor']: ''}}">
                        </div>

                        <div class="form-group">
                            <label>N° de serie</label>
                            <input type="text" class="form-control" name="serie_transf" id="serie_transf" value="{{isset($salida['certificadoAnotacionesVigentePatente']['datosVehiculo']['numeroSerie'])? $salida['certificadoAnotacionesVigentePatente']['datosVehiculo']['numeroSerie']: ''}}">
                        </div>

                        <div class="form-group">
                            <label>N° VIN</label>
                            <input type="text" class="form-control" name="vin_transf" id="vin_transf" value="{{isset($salida['certificadoAnotacionesVigentePatente']['datosVehiculo']['vin'])? $salida['certificadoAnotacionesVigentePatente']['datosVehiculo']['vin']: ''}}">
                        </div>

                        <div class="form-group">
                            <label>Color</label>
                            <input type="text" class="form-control" name="color_transf" id="color_transf" value="{{isset($salida['certificadoAnotacionesVigentePatente']['datosVehiculo']['color'])? $salida['certificadoAnotacionesVigentePatente']['datosVehiculo']['color']: ''}}">
                        </div>
                    </fieldset>
                </div>
                <div class="col-lg-6">
                    <fieldset>
                        <legend>Datos del propietario</legend>
                        <div class="form-group">
                            <label>Nombres</label>
                            <input type="text" class="form-control" name="nombres_transf" id="nombres_transf" value="{{isset($salida['certificadoAnotacionesVigentePatente']['PropietariosActuales']['datosPropietario']['nombrePersonaNatural']['nombres'])? $salida['certificadoAnotacionesVigentePatente']['PropietariosActuales']['datosPropietario']['nombrePersonaNatural']['nombres']: ''}}">
                        </div>

                        <div class="form-group">
                            <label>Apellido Paterno</label>
                            <input type="text" class="form-control" name="apaterno_transf" id="apaterno_transf" value="{{isset($salida['certificadoAnotacionesVigentePatente']['PropietariosActuales']['datosPropietario']['nombrePersonaNatural']['apellidoPaterno'])? $salida['certificadoAnotacionesVigentePatente']['PropietariosActuales']['datosPropietario']['nombrePersonaNatural']['apellidoPaterno']: ''}}">
                        </div>

                        <div class="form-group">
                            <label>Apellido Materno</label>
                            <input type="text" class="form-control" name="amaterno_transf" id="amaterno_transf" value="{{isset($salida['certificadoAnotacionesVigentePatente']['PropietariosActuales']['datosPropietario']['nombrePersonaNatural']['apellidoMaterno'])? $salida['certificadoAnotacionesVigentePatente']['PropietariosActuales']['datosPropietario']['nombrePersonaNatural']['apellidoMaterno']: ''}}">
                        </div>

                        <div class="form-group">
                            <label>Razón Social</label>
                            <input type="text" class="form-control" name="razon_social_transf" id="razon_social_transf" value="{{isset($salida['certificadoAnotacionesVigentePatente']['PropietariosActuales']['datosPropietario']['razonSocial'])? $salida['certificadoAnotacionesVigentePatente']['PropietariosActuales']['datosPropietario']['razonSocial']: ''}}">
                        </div>

                        <div class="form-group">
                            <label>Rut</label>
                            <input type="text" class="form-control" name="rut_transf" id="rut_transf" value="{{isset($salida['certificadoAnotacionesVigentePatente']['PropietariosActuales']['datosPropietario']['run'])? $salida['certificadoAnotacionesVigentePatente']['PropietariosActuales']['datosPropietario']['run']['numero'].'-'.$salida['certificadoAnotacionesVigentePatente']['PropietariosActuales']['datosPropietario']['run']['dv'] : ''}}">
                        </div>

                    </fieldset>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <div class="col-sm-12 col-lg-12 mb5">
                            @if(!empty($solicitud_data))
                                @php
                                    $documentos_solicitud = $solicitud_data->documentos;
                                    if(!empty($documentos_solicitud)){
                                        echo '<table id="tableDocs3" class="table table-bordered"><thead>
                                                <tr>
                                                    <th>Nombre Archivo</th>
                                                    <th>Tipo</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead><tbody id="tableDocsBody3">';
                                        foreach($documentos_solicitud as $docs){
                                            echo '<tr id="'.$docs->name.'"><td>';
                                            //if($docs->description== "Factura en PDF"){
                                                echo '<a target="_blank" href="'.url(str_replace("public/","storage/",$docs->name)).'">'.url(str_replace("public/","storage/",$docs->name)).'</a>';
                                            //}
                                            echo '</td><td>'.$docs->description.'</td><td><button class="btn btn-danger eliminarArchivoDoc" data-solicitudid="'.$solicitud_data->id.'" data-docname="'.$docs->name.'"><i class="fa fa-trash"></i></button></td></tr>';
                                        }
                                        echo '</tbody></table>';
                                    }
                                @endphp
                            @endif
                        </div>
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


<script>

    $(document).on("submit","#form-creasolicitud-transf",function(e){
        e.preventDefault();
        showOverlay();
        let formData = new FormData(document.getElementById('form-creasolicitud-transf'));
        $.ajax({
            url: "{{ route('transferencia.store')}}",
            type: "post",
            processData: false,
            contentType: false,
            data: formData,
            error: function(jqXHR, textStatus, errorThrown) {
                hideOverlay();
                // Acción cuando hay un error.
                new PNotify({
                        title: 'Error',
                        text: "AJAX error: " + textStatus + ' : ' + errorThrown,
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
                hideOverlay();
                let jsondata = JSON.parse(data);
                let solicitud_id = jsondata.solicitud_id;
                let solicitud_id2 = jsondata.solicitud_id2;
                if(parseInt(solicitud_id) != 0){                
                    $("#pills-comprador").html(jsondata.html);
                    $("#pills-comprador").toggleClass('show');
                    $("#pills-datavehiculo").toggleClass('show');
                    $("#pills-comprador-tab").attr("href","#pills-comprador");
                    $("#pills-comprador-tab").toggleClass('disabled');
                    $("#pills-comprador-tab").attr("aria-disabled",false);
                    $("#pills-comprador-tab").click();
                }
                else{
                    $.ajax({
                        url: "/documentoTransferencia/"+solicitud_id2+"/get",
                        type: "get",
                        success: function(data2) {
                            let jsondata = JSON.parse(data2);
                            let html = jsondata.data;
                            $("#tableDocsBody3").html(html);
                        }

                    });
                }

                if(parseInt(solicitud_id) != 0){
                    window.location.href= "https://"+"{{$_SERVER['HTTP_HOST']}}"+ "/transferencia/continuarSolicitud/"+ solicitud_id;
                }
                else{
                    new PNotify({
                        title: 'Crear Transferencia',
                        text: 'Solicitud de transferencia actualizada exitosamente',
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
                        width: '290px',
                        delay: 2000
                    });
                    return true;
                }
            }
        })


    });






</script>