
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
                            <input type="text" class="form-control" name="ppu_transf" id="ppu_transf" value="<?php 
                                if($solicitud_data == null){
                                    echo isset($salida['certificadoAnotacionesVigentePatente']['datosVehiculo']['ppu'])? $salida['certificadoAnotacionesVigentePatente']['datosVehiculo']['ppu']: ''; 
                                }else{
                                    echo $solicitud_data->vehiculo->ppu;   
                                }?>">
                        </div>

                        <div class="form-group">
                            <label>Tipo Vehículo</label>
                            <input type="text" class="form-control" name="tipo_transf" id="tipo_transf" value="<?php 
                            if($solicitud_data == null){
                                    echo isset($salida['certificadoAnotacionesVigentePatente']['datosVehiculo']['tipo'])? $salida['certificadoAnotacionesVigentePatente']['datosVehiculo']['tipo']: '';
                            }else{
                                echo $solicitud_data->vehiculo->tipo_vehiculo;
                            }?>">
                        </div>

                        <div class="form-group">
                            <label>Año</label>
                            <input type="text" class="form-control" name="anio_transf" id="anio_transf" value="<?php 
                            if($solicitud_data == null){
                                    echo isset($salida['certificadoAnotacionesVigentePatente']['datosVehiculo']['aFabrica'])? $salida['certificadoAnotacionesVigentePatente']['datosVehiculo']['aFabrica']: '';
                            }else{
                                echo $solicitud_data->vehiculo->anio;
                            }?>">
                        </div>

                        <div class="form-group">
                            <label>Marca</label>
                            <input type="text" class="form-control" name="marca_transf" id="marca_transf" value="<?php 
                            if($solicitud_data == null){
                                    echo isset($salida['certificadoAnotacionesVigentePatente']['datosVehiculo']['marca'])? $salida['certificadoAnotacionesVigentePatente']['datosVehiculo']['marca']: '';
                            }else{
                                echo $solicitud_data->vehiculo->marca;
                            }    
                                ?>">
                        </div>
                        
                        <div class="form-group">
                            <label>Modelo</label>
                            <input type="text" class="form-control" name="modelo_transf" id="modelo_transf" value="<?php 
                            if($solicitud_data == null){
                                echo isset($salida['certificadoAnotacionesVigentePatente']['datosVehiculo']['modelo'])? $salida['certificadoAnotacionesVigentePatente']['datosVehiculo']['modelo']: '';
                            }else{
                                echo $solicitud_data->vehiculo->modelo;   
                            }?>">
                        </div>

                        <div class="form-group">
                            <label>Chasis</label>
                            <input type="text" class="form-control" name="chasis_transf" id="chasis_transf" value="<?php 
                            if($solicitud_data == null){
                                echo isset($salida['certificadoAnotacionesVigentePatente']['datosVehiculo']['chasis'])? $salida['certificadoAnotacionesVigentePatente']['datosVehiculo']['chasis']: '';
                            }
                            else{
                                echo $solicitud_data->vehiculo->chasis;
                            }    
                                ?>">
                        </div>

                        <div class="form-group">
                            <label>Motor</label>
                            <input type="text" class="form-control" name="motor_transf" id="motor_transf" value="<?php 
                            if($solicitud_data == null){
                                echo isset($salida['certificadoAnotacionesVigentePatente']['datosVehiculo']['numeroMotor'])? $salida['certificadoAnotacionesVigentePatente']['datosVehiculo']['numeroMotor']: '';
                            }else{
                                echo $solicitud_data->vehiculo->motor;
                            }    
                                ?>">
                        </div>

                        <div class="form-group">
                            <label>N° de serie</label>
                            <input type="text" class="form-control" name="serie_transf" id="serie_transf" value="<?php 
                            if($solicitud_data == null){    
                                echo isset($salida['certificadoAnotacionesVigentePatente']['datosVehiculo']['numeroSerie'])? $salida['certificadoAnotacionesVigentePatente']['datosVehiculo']['numeroSerie']: '';
                            }else{
                                echo $solicitud_data->vehiculo->serie;
                            }?>">
                        </div>

                        <div class="form-group">
                            <label>N° VIN</label>
                            <input type="text" class="form-control" name="vin_transf" id="vin_transf" value="<?php 
                            if($solicitud_data == null){
                                echo isset($salida['certificadoAnotacionesVigentePatente']['datosVehiculo']['vin'])? $salida['certificadoAnotacionesVigentePatente']['datosVehiculo']['vin']: '';
                            }else{
                                echo $solicitud_data->vehiculo->vin;
                            }?>">
                        </div>

                        <div class="form-group">
                            <label>Color</label>
                            <input type="text" class="form-control" name="color_transf" id="color_transf" value="<?php 
                            if($solicitud_data == null){
                                echo isset($salida['certificadoAnotacionesVigentePatente']['datosVehiculo']['color'])? $salida['certificadoAnotacionesVigentePatente']['datosVehiculo']['color']: '';
                            }else{
                                echo $solicitud_data->vehiculo->color;
                            }?>">
                        </div>
                    </fieldset>
                </div>
                <div class="col-lg-6">
                    <fieldset>
                        <legend>Datos del propietario</legend>
                        <div class="form-group">
                            <label>Nombres</label>
                            <input type="text" class="form-control" name="nombres_transf" id="nombres_transf" value="<?php 
                            if($solicitud_data == null){
                                echo isset($salida['certificadoAnotacionesVigentePatente']['PropietariosActuales']['datosPropietario']['nombrePersonaNatural']['nombres'])? $salida['certificadoAnotacionesVigentePatente']['PropietariosActuales']['datosPropietario']['nombrePersonaNatural']['nombres']: '';
                            }else{
                                if($propietario_data != null){
                                    echo $propietario_data->nombre;
                                }
                            }?>">
                        </div>

                        <div class="form-group">
                            <label>Apellido Paterno</label>
                            <input type="text" class="form-control" name="apaterno_transf" id="apaterno_transf" value="<?php 
                            if($solicitud_data == null){
                                echo isset($salida['certificadoAnotacionesVigentePatente']['PropietariosActuales']['datosPropietario']['nombrePersonaNatural']['apellidoPaterno'])? $salida['certificadoAnotacionesVigentePatente']['PropietariosActuales']['datosPropietario']['nombrePersonaNatural']['apellidoPaterno']: '';
                            }
                            else{
                                if($propietario_data != null){
                                    echo $propietario_data->aPaterno;
                                }
                
                            }?>">
                        </div>

                        <div class="form-group">
                            <label>Apellido Materno</label>
                            <input type="text" class="form-control" name="amaterno_transf" id="amaterno_transf" value="<?php 
                            if($solicitud_data == null){    
                                echo isset($salida['certificadoAnotacionesVigentePatente']['PropietariosActuales']['datosPropietario']['nombrePersonaNatural']['apellidoMaterno'])? $salida['certificadoAnotacionesVigentePatente']['PropietariosActuales']['datosPropietario']['nombrePersonaNatural']['apellidoMaterno']: '';
                            }else{
                                if($propietario_data != null){
                                    echo $propietario_data->aMaterno;
                                }
                            }?>">
                        </div>

                        <div class="form-group">
                            <label>Razón Social</label>
                            <input type="text" class="form-control" name="razon_social_transf" id="razon_social_transf" value="<?php 
                            if($solicitud_data == null){  
                                echo isset($salida['certificadoAnotacionesVigentePatente']['PropietariosActuales']['datosPropietario']['razonSocial'])? $salida['certificadoAnotacionesVigentePatente']['PropietariosActuales']['datosPropietario']['razonSocial']: '';
                            }else{
                                if($propietario_data != null){
                                    echo $propietario_data->razon_social;
                                }
                            }?>">
                        </div>

                        <div class="form-group">
                            <label>Rut</label>
                            <input type="text" class="form-control" name="rut_transf" id="rut_transf" value="<?php 
                            if($solicitud_data == null){  
                                echo isset($salida['certificadoAnotacionesVigentePatente']['PropietariosActuales']['datosPropietario']['run'])? $salida['certificadoAnotacionesVigentePatente']['PropietariosActuales']['datosPropietario']['run']['numero'].'-'.$salida['certificadoAnotacionesVigentePatente']['PropietariosActuales']['datosPropietario']['run']['dv'] : '';
                            }
                            else{
                                if($propietario_data != null){
                                    echo $propietario_data->rut;
                                }
                            }?>">
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
                                                    <th>Enviado a RC</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead><tbody id="tableDocsBody3">';
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