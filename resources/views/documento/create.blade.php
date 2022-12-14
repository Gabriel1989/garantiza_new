@extends("themes.$themes.layout")

@section('styles')
@endsection

@section('contenido')
@include('includes.form-error-message')
@include('includes.mensaje')
<form enctype="multipart/form-data" class="form-documentos" action="{{route('documento.store')}}" method="POST">
    @csrf
    <input type="hidden" name="id" value="{{$solicitud->id}}"/>
    <div class="panel panel-info panel-border top">
        <div class="panel-heading">
            <span class="panel-title">Adjuntar Documentos a la Solicitud</span>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <div class="col-sm-12 col-lg-12 mb5">
                    <div class="col-lg-2">
                        <span class="btn btn-warning fileinput-button col-sm-12" name="pic" id="FacturaXML">
                            Seleccionar Factura XML</span>
                    </div>
                    <div class="col-lg-3">
                        <input id="Factura_XML" name="Factura_XML" type="file" style="display:none" accept="text/xml"/>
                        <label id="lbl_Factura_XML"></label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12 col-lg-12 mb5">
                    <div class="col-lg-2">
                        <span class="btn btn-warning fileinput-button col-sm-12" name="pic" id="FacturaPDF">
                            Seleccionar Factura PDF</span>
                    </div>
                    <div class="col-lg-3">
                        <input id="Factura_PDF" name="Factura_PDF" type="file" style="display:none" accept="application/pdf"/>
                        <label id="lbl_Factura_PDF"></label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12 col-lg-12 mb5">
                    <div class="col-lg-2">
                        <span class="btn btn-warning fileinput-button col-sm-12" name="pic" id="CedulaPDF">
                             Seleccionar Cédula PDF</span>
                    </div>
                    <div class="col-lg-3">
                        <input id="Cedula_PDF" name="Cedula_PDF" type="file" style="display:none" accept="application/pdf"/>
                        <label id="lbl_Cedula_PDF"></label>
                    </div>
                </div>
            </div>

            @if ($solicitud->empresa==1)
            <div class="form-group">
                <div class="col-sm-12 col-lg-12 mb5">
                    <div class="col-lg-2">
                        <span class="btn btn-warning fileinput-button col-sm-12" name="pic" id="RolPDF">
                            Seleccionar Rol de Cliente PDF</span>
                    </div>
                    <div class="col-lg-3">
                        <input id="Rol_PDF" name="Rol_PDF" type="file" style="display:none" accept="application/pdf"/>
                        <label id="lbl_Rol_PDF"></label>
                    </div>
                </div>
            </div>
            @endif
            
            @foreach ($para as $item)
            <div class="form-group">
                <div class="col-sm-12 col-lg-12 mb5">
                    <div class="col-lg-2">
                        <span class="btn btn-warning fileinput-button col-sm-12" name="pic" id="CedulaParaPDF">
                            Seleccionar Cédula Para PDF</span>
                    </div>
                    <div class="col-lg-3">
                        <input id="CedulaPara_PDF" name="CedulaPara_PDF" type="file" style="display:none" accept="application/pdf"/>
                        <label id="lbl_CedulaPara_PDF"></label>
                    </div>
                </div>
            </div>
            @endforeach
            
        </div>
    <div class="panel-footer">
        <button type="submit" class="btn btn-system"> <li class="fa fa-upload"></li> Subir archivos y Continuar</button>
    </div>
</div>
</form>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {

        $('#FacturaXML').on('click', function() {
            $('#Factura_XML').trigger('click');
        });

        $('#Factura_XML').on('change', function(){
            $('#lbl_Factura_XML').text($('#Factura_XML').val());
        });

        $('#FacturaPDF').on('click', function() {
            $('#Factura_PDF').trigger('click');
        });

        $('#Factura_PDF').on('change', function(){
            $('#lbl_Factura_PDF').text($('#Factura_PDF').val());
        });

        $('#CedulaPDF').on('click', function() {
            $('#Cedula_PDF').trigger('click');
        });

        $('#Cedula_PDF').on('change', function(){
            $('#lbl_Cedula_PDF').text($('#Cedula_PDF').val());
        });

        $('#RolPDF').on('click', function() {
            $('#Rol_PDF').trigger('click');
        });

        $('#Rol_PDF').on('change', function(){
            $('#lbl_Rol_PDF').text($('#Rol_PDF').val());
        });

        $('#CedulaParaPDF').on('click', function() {
            $('#CedulaPara_PDF').trigger('click');
        });

        $('#CedulaPara_PDF').on('change', function(){
            $('#lbl_CedulaPara_PDF').text($('#CedulaPara_PDF').val());
        });

    });


    $(".form-documentos").on('submit', function () {
        if ($('#Factura_XML').val().length==0){
            new PNotify({
                title: 'Adjuntar Archivo',
                text: 'Debe adjuntar la Factura en formato XML',
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
        };

        if ($('#Factura_PDF').val().length==0){
            new PNotify({
                title: 'Adjuntar Archivo',
                text: 'Debe adjuntar la Factura en formato PDF',
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
        };

        if ($('#Cedula_PDF').val().length==0){
            new PNotify({
                title: 'Adjuntar Archivo',
                text: 'Debe adjuntar la Cédula en formato PDF',
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
        };

        if ($('#Rol_PDF').length){
            if ($('#Rol_PDF').val().length==0){
                new PNotify({
                    title: 'Adjuntar Archivo',
                    text: 'Debe adjuntar el Rol en formato PDF',
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
            };
        };

        if ($('#CedulaPara_PDF').length){
            if ($('#CedulaPara_PDF').val().length==0){
                new PNotify({
                    title: 'Adjuntar Archivo',
                    text: 'Debe adjuntar la Cédula de Compra/Para en formato PDF',
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
            };
        };
    });

</script>
@endsection