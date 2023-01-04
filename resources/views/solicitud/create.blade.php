@extends("themes.$themes.layout")

@section('styles')
@endsection

@section('contenido')
@include('includes.form-error-message')
@include('includes.mensaje')
<form enctype="multipart/form-data" class="form-documentos form-horizontal form-solicitud" action="{{route('solicitud.store')}}" method="POST">
    @csrf
    @method('POST')
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

                
                <label for="sucursal_id" class="col-lg-1 control-label">Sucursal: </label>
                <div class="col-lg-5">
                    <select name="sucursal_id" id="sucursal_id">
                        <option value="0" selected>Seleccione Sucursal ...</option>
                        @foreach ($sucursales as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>    
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
                            <option value="{{$item->id}}">{{$item->name}}</option>    
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
                        <input id="Factura_XML" name="Factura_XML" type="file" style="display:none" accept="text/xml,application/pdf"/>
                        <label id="lbl_Factura_XML"></label>
                    </div>
                </div>
            </div>
            <div class="form-group">
            <label for="ppu_terminacion" class="col-lg-1 control-label">PPU Disponibles:</label>
                <label class="col-lg-5">
                    <select name="ppu_terminacion" id="ppu_terminacion">
                        <option value="" selected>Seleccione PPU ...</option>
                        @foreach ($ppu as $item)
                            <option value="{{$item['Terminacion']}}">{{$item['Terminacion']}}</option>    
                        @endforeach
                    </select>
                </label>


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
                                <input class="form-control" name="razon_soc_emisor" id="razon_soc_emisor" maxlength="35">
                            </div>

                            <div class="col-lg-3">
                                <label>Rut emisor</label>
                                <input type="number" class="form-control" name="rut_emisor" id="rut_emisor">
                            </div>

                            <div class="col-lg-3">
                                <label>Fecha Emisión</label>
                                <input class="form-control" name="fecha_emision_fac" id="fecha_emision_fac">
                            </div>
                            <div class="col-lg-3">
                                <label>Monto Total Factura</label>
                                <input type="number" class="form-control" name="monto_factura" id="monto_factura">
                            </div>
                        </div>
                        <div class="row">
                            
                            
                        </div>
            </div>
            </div>
        </div>

    <div class="panel-footer">
        <button type="submit" class="btn btn-system"> <li class="fa fa-upload"></li> Crear Solicitud y Continuar</button>
    </div>
</div>
</form>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {

        $('#sucursal_id').multiselect();
        $('#tipoVehiculos_id').multiselect();

        $.datepicker.regional['es'] = {
        closeText: 'Cerrar',
        prevText: '< Ant',
        nextText: 'Sig >',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
        dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
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
        });

        $('#FacturaXML').on('click', function() {
            $('#Factura_XML').trigger('click');
        });

        $('#Factura_XML').on('change', function(){
            $('#lbl_Factura_XML').text($('#Factura_XML').val());
        });

    });

    $(".form-documentos").on('submit', function () {
        if($('#sucursal_id').val()=='0'){
            new PNotify({
                title: 'Crear Solicitud',
                text: 'Debe Seleccionar la Sucursal',
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

        if($('#tipoVehiculos_id').val()=='0'){
            new PNotify({
                title: 'Crear Solicitud',
                text: 'Debe Seleccionar el Tipo de Vehículo',
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

        if ($('#Factura_XML').val().length==0){
            new PNotify({
                title: 'Crear Solicitud',
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

        if ($('#razon_soc_emisor').val().length==0){
            new PNotify({
                title: 'Crear Solicitud',
                text: 'Debe ingresar razón social del Emisor',
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

        if ($('#rut_emisor').val().length==0){
            new PNotify({
                title: 'Crear Solicitud',
                text: 'Debe ingresar rut del Emisor',
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

        if ($('#fecha_emision_fac').val().length==0){
            new PNotify({
                title: 'Crear Solicitud',
                text: 'Debe ingresar fecha de emisión de Factura',
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

        if ($('#monto_factura').val().length==0){
            new PNotify({
                title: 'Crear Solicitud',
                text: 'Debe ingresar monto total de factura',
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



        
    });

</script>
@endsection