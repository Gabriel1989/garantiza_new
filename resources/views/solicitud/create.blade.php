@extends("themes.$themes.layout")

@section('styles')
@endsection

@section('contenido')
@include('includes.form-error-message')
@include('includes.mensaje')

<div class="panel panel-info panel-border top">
    <div class="panel-body">
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link @if($id_solicitud == 0)  active @endif" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Crear Solicitud</a>
            </li>
            <li class="nav-item @if($id_solicitud != 0 && $id_adquiriente == 0)  active    @endif" role="presentation">
                <a class="nav-link @if($id_solicitud == 0)  disabled    @endif" id="pills-profile-tab" data-toggle="pill" @if($id_solicitud != 0) href="#pills-profile" @else href="#" @endif role="tab" aria-controls="pills-profile" aria-selected="false" @if($id_solicitud == 0) aria-disabled="true" @endif>Datos de Adquiriente</a>
            </li>
            <li class="nav-item @if($id_adquiriente != 0 && $id_comprapara == 0)  active    @endif" role="presentation">
                <a class="nav-link @if($id_adquiriente == 0)  disabled    @endif" id="pills-contact-tab" data-toggle="pill" @if($id_adquiriente != 0) href="#pills-contact" @else href="#" @endif role="tab" aria-controls="pills-contact" aria-selected="false"@if($id_adquiriente == 0) aria-disabled="true" @endif>Datos de Compra Para</a>
            </li>
            <li class="nav-item @if($id_adquiriente != 0 && $id_comprapara != 0)  active    @endif" role="presentation">
                <a class="nav-link @if($id_comprapara == 0)  disabled    @endif" id="pills-invoice-tab" data-toggle="pill" @if($id_comprapara != 0) href="#pills-invoice" @else href="#" @endif role="tab" aria-controls="pills-invoice" aria-selected="false" @if($id_comprapara == 0) aria-disabled="true" @endif>Datos de Factura</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link disabled" id="pills-docs-tab" data-toggle="pill" href="#" role="tab" aria-controls="pills-docs" aria-selected="false" aria-disabled="true">Adjuntar Documentación</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link disabled" id="pills-limitation-tab" data-toggle="pill" href="#" role="tab" aria-controls="pills-limitation" aria-selected="false" aria-disabled="true">Configurar Limitación</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link disabled" id="pills-pay-tab" data-toggle="pill" href="#" role="tab" aria-controls="pills-pay" aria-selected="false" aria-disabled="true">Pago</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link disabled" id="pills-voucher-tab" data-toggle="pill" href="#" role="tab" aria-controls="pills-voucher" aria-selected="false" aria-disabled="true">Comprobantes</a>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade @if($id_solicitud == 0)  active show in @endif" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                <form enctype="multipart/form-data" id="form-solicitud-create" class="form-documentos form-horizontal form-solicitud" action="{{route('solicitud.store')}}" method="POST">
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
                                        <option value="{{$item->id}}" @if(!is_null($solicitud_data->sucursal_id)) @if($solicitud_data->sucursal_id==$item->id) selected  @endif @endif>{{$item->name}}</option>
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
                                        <option value="{{$item->id}}"  @if(!is_null($solicitud_data->tipoVehiculos_id))  @if($solicitud_data->tipoVehiculos_id==$item->id) selected   @endif   @endif>{{$item->name}}</option>
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
                                        @if(is_null($solicitud_data->termino_1)) 
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

                            <div class="panel panel-info panel-border top">
                                <div class="panel-heading" role="button">
                                    <span class="panel-title" style="cursor:pointer;">Ingresar datos factura</span>
                                </div>
                                <div class="panel-body">

                                    <br>
                                    <div class="row">

                                        <div class="col-lg-3">
                                            <label>Nombre o Razón Social Emisor</label>
                                            <input class="form-control" name="razon_soc_emisor" id="razon_soc_emisor" maxlength="35" value="{{ !is_null($header->RznSoc)? $header->RznSoc : ''}}">
                                        </div>

                                        <div class="col-lg-3">
                                            <label>Rut emisor</label>
                                            <input type="number" class="form-control" name="rut_emisor" id="rut_emisor" value="{{!is_null($header->RUTEmisor)? $header->RUTEmisor : ''}}">
                                        </div>

                                        <div class="col-lg-3">
                                            <label>Fecha Emisión</label>
                                            <input class="form-control" name="fecha_emision_fac" id="fecha_emision_fac" value="{{!is_null($header->FchEmis)? $header->FchEmis : ''}}">
                                        </div>
                                        <div class="col-lg-3">
                                            <label>Monto Total Factura</label>
                                            <input type="number" class="form-control" name="monto_factura" id="monto_factura" value="{{!is_null($header->MntTotal)? $header->MntTotal : ''}}">
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
            </div>
            <div class="tab-pane fade @if($id_solicitud != 0 && $id_adquiriente == 0) show  active in @endif" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                @if($id_solicitud != 0)
                    @include('solicitud.adquirientes')
                @endif
            </div>
            <div class="tab-pane fade @if($id_adquiriente != 0 && $id_comprapara == 0) show  active in @endif" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                @if ($id_adquiriente != 0)
                    @include('solicitud.compraPara')
                @endif

            </div>
            <div class="tab-pane fade @if($id_comprapara != 0)  show active in @endif" id="pills-invoice" role="tabpanel" aria-labelledby="pills-invoice-tab">
                @if ($id_comprapara != 0)
                    @if($id_tipo_vehiculo == 1)
                        @include('solicitud.revision.facturaAuto')
                    @elseif ($id_tipo_vehiculo == 2)
                        @include('solicitud.revision.facturaMoto')
                    @else
                        @include('solicitud.revision.facturaCamion')
                    @endif
                @endif
            </div>
            <div class="tab-pane fade" id="pills-docs" role="tabpanel" aria-labelledby="pills-docs-tab">

            </div>

        </div>
    </div>
    <div class="panel-footer">
        <button id="btnCancelProcess" class="btn btn-danger">
            <li class="fa fa-times"></li> Cancelar solicitud
        </button>
    </div>
</div>




@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

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
        });

        $('#FacturaXML').on('click', function() {
            $('#Factura_XML').trigger('click');
        });

        $('#Factura_XML').on('change', function() {
            $('#lbl_Factura_XML').text($('#Factura_XML').val());
        });

    });

    $(document).on('submit',"#form-solicitud-create", function(e) {
        e.preventDefault();
        if ($('#sucursal_id').val() == '0') {
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

        if ($('#tipoVehiculos_id').val() == '0') {
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

        if ($('#Factura_XML').val().length == 0) {
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

        if ($('#razon_soc_emisor').val().length == 0) {
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

        if ($('#rut_emisor').val().length == 0) {
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

        if ($('#fecha_emision_fac').val().length == 0) {
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

        if ($('#monto_factura').val().length == 0) {
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

        let formData = new FormData(document.getElementById('form-solicitud-create'));

        $.ajax({
            url: "{{route('solicitud.store')}}",
            type: "post",
            processData: false,
            contentType: false,
            data: formData,
            success: function(data) {
                $("#pills-profile").html(data);
                $("#pills-profile").toggleClass('show');
                $("#pills-home").toggleClass('show');
                $("#pills-profile-tab").attr("href","#pills-profile");
                $("#pills-profile-tab").toggleClass('disabled');
                $("#pills-profile-tab").attr("aria-disabled",false);
                $("#pills-profile-tab").click();
                

                $('.comuna').multiselect({
                    enableFiltering: true,
                });
                $('#tipoPersona').multiselect();

                $(".rut").rut({
                    formatOn: 'keyup',
                    minimumLength: 8,
                    validateOn: 'change'
                });

                $(".rut").rut().on('rutInvalido', function(e) {
                    new PNotify({
                        title: 'Rut de Concesionaria',
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

                $('#agregar').on('click', function() {
                    if (!$('#adquiriente2').is(":visible")) {
                        $('#calle2').val($('#calle').val());
                        $('#numero2').val($('#numero').val());
                        $('#rDireccion2').val($('#rDireccion').val());
                        var lastSelected = $('#comuna option:selected').val();
                        //OJO - problema
                        $("#comuna2").multiselect('select', lastSelected);
                        $('#adquiriente2').show();
                    } else {
                        $('#calle3').val($('#calle').val());
                        $('#numero3').val($('#numero').val());
                        $('#rDireccion3').val($('#rDireccion').val());
                        var lastSelected = $('#comuna option:selected').val();
                        //OJO - problema
                        $("#comuna3").multiselect('select', lastSelected);
                        $('#adquiriente3').show();
                        $('#agregar').hide();
                    }
                });

                $("#tipoPersona").on('change', function() {
                    if ($(this).val() == 'O') {
                        $('#agregar').show();
                    } else {
                        $('#agregar').hide();
                        $('#adquiriente2').hide();
                        $('#adquiriente3').hide();
                        $('#rut2').val('');
                        $('#rut3').val('');
                    };
                });


                /*let json = JSON.parse(data);
                $(".num_solicitud_interno").text(json.solicitud_id);
                $("#form_adquiriente").fadeToggle();
                $("#rut").val(json.data_factura.rut_receptor);
                var rut_format = $("#rut").val();
                rut_format = rut_format + $.computeDv(rut_format);
                $("#rut").val($.formatRut(rut_format));
                $("#nombre").val(json.data_factura.razon_social_recep)
                $("#calle").val(json.data_factura.direccion);*/
                //window.open("http://"+"{{$_SERVER['HTTP_HOST']}}"+"/solicitud/"+data+"/adquirientes");
            }
        });


    });

    $(document).on("click","#pills-home-tab",function(e){
        $("#pills-profile").removeClass('show');
        $("#pills-contact").removeClass('show');
        $("#pills-invoice").removeClass('show');
    });

    $(document).on("click","#pills-profile-tab",function(e){
        $("#pills-home").removeClass('show');
        $("#pills-contact").removeClass('show');
        $("#pills-invoice").removeClass('show');
    });

    $(document).on("click","#pills-contact-tab",function(e){
        $("#pills-home").removeClass('show');
        $("#pills-profile").removeClass('show');
        $("#pills-invoice").removeClass('show');
    });

    $(document).on("click","#pills-invoice-tab",function(e){
        $("#pills-home").removeClass('show');
        $("#pills-profile").removeClass('show');
        $("#pills-contact").removeClass('show');
    });

    

    function elimina(adquiriente) {
        if (adquiriente == 2) {
            $('#adquiriente2').hide();
            $('#rut2').val('');
        } else {
            $('#adquiriente3').hide();
            $('#rut3').val('');
        }
    }


</script>
