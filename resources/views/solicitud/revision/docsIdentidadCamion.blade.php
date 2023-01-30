<?php 

use App\Models\CompraPara;


?>

<form enctype="multipart/form-data" id="form_subeDocs" class="form-documentos form-horizontal form-solicitud" method="POST">
    @csrf
    @method('POST')
    <div class="panel panel-info panel-border top">
        <div class="panel-heading">
            <span class="panel-title">Ingreso de Solicitud N° {{$id}} - Adjuntar Documentación para la inscripción/limitación del vehículo</span>
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
                <div class="col-sm-4 col-lg-4 mb5">
                    <div class="col-lg-6">
                        <span class="btn btn-warning fileinput-button col-sm-12" name="CedulaPDF" id="CedulaPDF">
                            Seleccionar Cédula PDF</span>
                    </div>
                    <div class="col-lg-6">
                        <input id="Cedula_PDF" name="Cedula_PDF" type="file" style="display:none" accept="application/pdf" />
                        <label id="lbl_Cedula_PDF"></label>
                    </div>
                </div>
                @if(!is_null(CompraPara::getSolicitud($id)))
                <div class="col-sm-4 col-lg-4 mb5">
                    <div class="col-lg-6">
                        <span class="btn btn-warning fileinput-button col-sm-12" name="CedulaParaPDF" id="CedulaParaPDF">
                            Seleccionar Cédula Para PDF</span>
                    </div>
                    <div class="col-lg-6">
                        <input id="Cedula_Para_PDF" name="Cedula_Para_PDF" type="file" style="display:none" accept="application/pdf" />
                        <label id="lbl_Cedula_Para_PDF"></label>
                    </div>
                </div>
                @endif

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

    $('#FacturaPDFRC').on('click', function() {
            $('#Factura_PDF_RC').trigger('click');
    });

    $('#Factura_PDF_RC').on('change', function() {
        $('#lbl_Factura_PDF_RC').text($('#Factura_PDF_RC').val());
    });

});


$(document).on("submit","#form_subeDocs",function(e){
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

        }
    })



});


</script>