<?php 

use App\Models\CompraPara;
use App\Models\Solicitud;
$solicitud = Solicitud::find($id);

?>

<form enctype="multipart/form-data" id="form_subeDocs" class="form-documentos form-horizontal form-solicitud" method="POST">
    @csrf
    @method('POST')
    <div class="panel panel-info panel-border top">
        <div class="panel-heading">
            <span class="panel-title">Ingreso de Solicitud N° {{$id}} - Adjuntar Documentación para la inscripción del vehículo</span>
        </div>

        <div class="panel-body">
            <div class="form-group">
                <label for="patente_ppu" class="col-lg-2 control-label">Patente PPU :</label>
                <label class="col-lg-2">
                    <input type="hidden" name="solicitud_rc_id" id="solicitud_rc_id" value="{{ isset($solicitud_rc[0]->id)? $solicitud_rc[0]->id :  0}}">
                    <input type="text" name="patente_ppu" id="patente_ppu" class="form-control" placeholder="XXX.999-2" value="{{ isset($solicitud_rc[0]->ppu)? str_replace(".","",explode("-",$solicitud_rc[0]->ppu)[0]) : ''}}" required disabled>
                    
                </label>
                <label class="col-lg-2"></label>
                
                <label for="solicitud_rc_num" class="col-lg-2 control-label">N° Solicitud RC :</label>
                <label class="col-lg-4">
                    <input type="text" name="nroSolRc" id="solicitud_rc_num" class="form-control" value="{{ isset($solicitud_rc[0]->numeroSol)? $solicitud_rc[0]->numeroSol :  0}}" required disabled>
                </label>
            </div>

            <div class="form-group">
                <div class="col-sm-6 col-lg-6 mb5">
                    <div class="col-lg-6">
                        <span style="white-space:normal;" class="btn btn-warning fileinput-button col-sm-12" name="CedulaPDF" id="CedulaPDF">
                            Seleccionar Cédula PDF</span>
                    </div>
                    <div class="col-lg-6">
                        <input id="Cedula_PDF" name="Cedula_PDF" type="file" style="display:none" accept="application/pdf" />
                        <label id="lbl_Cedula_PDF"></label>
                    </div>
                </div>

                @if(count(CompraPara::getSolicitud($id)) > 0)
                <div class="col-sm-6 col-lg-6 mb5">
                    <div class="col-lg-6">
                        <span style="white-space:normal;" class="btn btn-warning fileinput-button col-sm-12" name="CedulaParaPDF" id="CedulaParaPDF" style="white-space: normal;">
                            Seleccionar Cédula Para PDF</span>
                    </div>
                    <div class="col-lg-6">
                        <input id="Cedula_Para_PDF" name="Cedula_Para_PDF" type="file" style="display:none" accept="application/pdf" />
                        <label id="lbl_Cedula_Para_PDF"></label>
                    </div>
                </div>
                @endif

                @if ($solicitud->empresa==1)
                
                    <div class="col-sm-6 col-lg-6 mb5">
                        <div class="col-lg-6">
                            <span style="white-space:normal;" class="btn btn-warning fileinput-button col-sm-12" name="pic" id="RolPDF">
                                Seleccionar Rol de Cliente PDF</span>
                        </div>
                        <div class="col-lg-6">
                            <input id="Rol_PDF" name="Rol_PDF" type="file" style="display:none" accept="application/pdf"/>
                            <label id="lbl_Rol_PDF"></label>
                        </div>
                    </div>
                
                @endif
                <!--
                <div class="col-sm-4 col-lg-4 mb5">
                    <div class="col-lg-6">
                        <span class="btn btn-warning fileinput-button col-sm-12" name="FacturaPDFRC" id="FacturaPDFRC">
                            Seleccionar Factura PDF</span>
                    </div>
                    <div class="col-lg-6">
                        <input id="Factura_PDF_RC" name="Factura_PDF_RC" type="file" style="display:none" accept="application/pdf" />
                        <label id="lbl_Factura_PDF_RC"></label>
                    </div>
                </div>
                -->

            </div>

            <div class="row">
                <div class="col-sm-12 col-lg-12 mb5">
                    @if(!empty($solicitud_data))
                        @php
                            $documentos_solicitud = $solicitud_data->documentos;
                            if(!empty($documentos_solicitud)){
                                echo '<table id="tableDocs2" class="table table-bordered"><thead>
                                        <tr>
                                            <th>Nombre Archivo</th>
                                            <th>Tipo</th>
                                            <th>Enviado a RC</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead><tbody id="tableDocsBody2">';
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
                                    echo '</td><td>'.$docs->description.'</td><td>'.$status_doc_rc.'</td><td><button class="btn btn-danger eliminarArchivoDoc2" data-solicitudid="'.$solicitud_data->id.'" data-docname="'.$docs->name.'"><i class="fa fa-trash"></i></button></td></tr>';
                                }
                                echo '</tbody></table>';
                            }
                        @endphp
                    @endif

                </div>
            </div>

        </div>    

        <div class="panel-footer">
            <button type="submit" class="btn btn-system"> <li class="fa fa-save"></li> Grabar y Continuar</button>
        </div>
    </div>


</form>

<script>

$(document).ready(function(){

    $('#CedulaPDF').on('click', function() {
            $('#Cedula_PDF').trigger('click');
    });

    $('#Cedula_PDF').on('change', function() {
        $('#lbl_Cedula_PDF').text($('#Cedula_PDF').val());
    });


    $('#CedulaParaPDF').on('click', function() {
            $('#Cedula_Para_PDF').trigger('click');
    });

    $('#Cedula_Para_PDF').on('change', function() {
        $('#lbl_Cedula_Para_PDF').text($('#Cedula_Para_PDF').val());
    });

    /*
    $('#FacturaPDFRC').on('click', function() {
            $('#Factura_PDF_RC').trigger('click');
    });

    $('#Factura_PDF_RC').on('change', function() {
        $('#lbl_Factura_PDF_RC').text($('#Factura_PDF_RC').val());
    });*/

    $('#RolPDF').on('click', function() {
        $('#Rol_PDF').trigger('click');
    });

    $('#Rol_PDF').on('change', function(){
        $('#lbl_Rol_PDF').text($('#Rol_PDF').val());
    });

});

$(document).on("click",".eliminarArchivoDoc2",function(e){
    e.preventDefault();
    var doc_name = $(this).data('docname');
    var solicitud_id = $(this).data('solicitudid');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });      
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
                        $("#tableDocsBody2").html(html);
                    }

            });
        }
    });
});

$(document).on("submit","#form_subeDocs",function(e){
    showOverlay();
    e.preventDefault();

    let formData = new FormData(document.getElementById("form_subeDocs"));

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: "/documento/{{$id}}/cargadocs",
        type: "post",
        processData: false,
        contentType: false,
        data: formData,
        success: function(data){
            hideOverlay();
            let json = JSON.parse(data);
            if(json.status == "ERROR"){
                new PNotify({
                    title: 'Subir documentos',
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
                    width: '290px',
                    delay: 2000
                });
                return false;
            }
            else{
                if(typeof json.esRevision !== 'undefined'){
                    if(json.esRevision == true){
                        new PNotify({
                            title: 'Subir documentos',
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
                            width: '290px',
                            delay: 2000
                        });
                        $("#pills-pay").html(json.html);
                        $("#pills-voucher").html(json.html2);
                        $("#pills-pay").toggleClass('show');
                        $("#pills-docs").removeClass('show');
                        $("#pills-home").removeClass('show');
                        $("#pills-contact").removeClass('show');
                        $("#pills-profile").removeClass('show');
                        $("#pills-invoice").removeClass('show');
                        $("#pills-voucher").removeClass('show');

                        $("#pills-voucher-tab").attr("href","#pills-voucher");
                        $("#pills-voucher-tab").toggleClass('disabled');
                        $("#pills-voucher-tab").attr("aria-disabled",false);

                        $("#pills-pay-tab").attr("href","#pills-pay");
                        $("#pills-pay-tab").toggleClass('disabled');
                        $("#pills-pay-tab").attr("aria-disabled",false);
                        $("#pills-pay-tab").click();
                        return true;
                    }
                    else{
                        new PNotify({
                            title: 'Subir documentos',
                            text: "Archivos subidos exitosamente. Ahora espere la revisión de un ejecutivo o administrador de Garantiza para aprobar/rechazar su solicitud para posterior inscripción a Registro Civil.",
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
                            delay: 20000
                        });

                        $.ajax({
                            url: "/documento/{{$id}}/get",
                            type: "get",
                            success: function(data2) {
                                let jsondata = JSON.parse(data2);
                                let html = jsondata.data;
                                $("#tableDocsBody").html(html);
                                $("#tableDocsBody2").html(html);
                            }
                        });
                    }
                }
            }

        }
    })



});


</script>