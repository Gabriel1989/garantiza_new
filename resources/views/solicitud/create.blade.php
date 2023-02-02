@extends("themes.$themes.layout")

@section('styles')
@endsection

@section('contenido')
@include('includes.form-error-message')
@include('includes.mensaje')

<div class="panel panel-info panel-border top">
    <div class="panel-body">
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item @if($solicita_ppu == false)  active @endif" role="presentation">
                <a class="nav-link @if($solicita_ppu == false)  active @endif" id="pills-ppu-tab" data-toggle="pill" href="#pills-ppu" role="tab" aria-controls="pills-ppu" aria-selected="true">Solicitar Nueva PPU</a>
            </li>
            <li class="nav-item @if($id_solicitud == 0 && $solicita_ppu)  active @endif" role="presentation">
                <a class="nav-link @if($id_solicitud == 0 && $solicita_ppu == false)  disabled @endif" id="pills-home-tab" data-toggle="pill" @if($solicita_ppu) href="#pills-home" @else href="#" @endif role="tab" aria-controls="pills-home" aria-selected="true" @if($solicita_ppu == false) aria-disabled="true" @endif>Crear Solicitud</a>
            </li>
            <li class="nav-item @if($id_solicitud != 0 && $id_adquiriente == 0)  active    @endif" role="presentation">
                <a class="nav-link @if($id_solicitud == 0)  disabled    @endif" id="pills-profile-tab" data-toggle="pill" @if($id_solicitud != 0) href="#pills-profile" @else href="#" @endif role="tab" aria-controls="pills-profile" aria-selected="false" @if($id_solicitud == 0) aria-disabled="true" @endif>Adquirientes</a>
            </li>
            <li class="nav-item @if($id_adquiriente != 0 && $id_comprapara == 0)  active    @endif" role="presentation">
                <a class="nav-link @if($id_adquiriente == 0)  disabled    @endif" id="pills-contact-tab" data-toggle="pill" @if($id_adquiriente != 0) href="#pills-contact" @else href="#" @endif role="tab" aria-controls="pills-contact" aria-selected="false"@if($id_adquiriente == 0) aria-disabled="true" @endif>Compra Para</a>
            </li>
            <li class="nav-item @if($id_adquiriente != 0 && $id_comprapara != 0 && $id_solicitud_rc == 0)  active    @endif" role="presentation">
                <a class="nav-link @if($id_comprapara == 0)  disabled    @endif" id="pills-invoice-tab" data-toggle="pill" @if($id_comprapara != 0) href="#pills-invoice" @else href="#" @endif role="tab" aria-controls="pills-invoice" aria-selected="false" @if($id_comprapara == 0) aria-disabled="true" @endif>Factura</a>
            </li>
            <li class="nav-item @if($id_solicitud_rc != 0)  active @endif" role="presentation">
                <a class="nav-link @if($id_solicitud_rc == 0)  disabled    @endif" id="pills-docs-tab" data-toggle="pill" @if($id_solicitud_rc != 0) href="#pills-docs" @else href="#" @endif role="tab" aria-controls="pills-docs" aria-selected="false" @if($id_solicitud_rc == 0) aria-disabled="true" @endif>Documentación</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link @if($id_solicitud_rc == 0)  disabled  @endif" id="pills-limitation-tab" data-toggle="pill" @if($id_solicitud_rc != 0) href="#pills-limitation" @else href="#" @endif role="tab" aria-controls="pills-limitation" aria-selected="false" @if($id_solicitud_rc == 0) aria-disabled="true" @endif>Limitación</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link disabled" id="pills-pay-tab" data-toggle="pill" href="#" role="tab" aria-controls="pills-pay" aria-selected="false" aria-disabled="true">Pago</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link disabled" id="pills-voucher-tab" data-toggle="pill" href="#" role="tab" aria-controls="pills-voucher" aria-selected="false" aria-disabled="true">Comprobantes</a>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade @if($solicita_ppu == false)  active show in @endif" id="pills-ppu" role="tabpanel" aria-labelledby="pills-ppu-tab">
                @include('solicitud.solicitarPPU')
            </div>
            <div class="tab-pane fade @if($id_solicitud == 0 && $solicita_ppu == true)  active show in @endif" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                @if($solicita_ppu)
                    @include('solicitud.createsolicitudnew')
                @endif
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
            <div class="tab-pane fade @if($id_comprapara != 0 && $id_solicitud_rc == 0)  show active in @endif" id="pills-invoice" role="tabpanel" aria-labelledby="pills-invoice-tab">
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
            <div class="tab-pane fade @if($id_solicitud_rc != 0)  active show in @endif" id="pills-docs" role="tabpanel" aria-labelledby="pills-docs-tab">
                @if($id_solicitud_rc != 0)
                    @if($id_tipo_vehiculo == 1)
                        @include('solicitud.revision.docsIdentidadAuto')
                    @elseif ($id_tipo_vehiculo == 2)
                        @include('solicitud.revision.docsIdentidadMoto')
                    @else
                        @include('solicitud.revision.docsIdentidadCamion')
                    @endif
                @endif
            </div>
            <div class="tab-pane fade" id="pills-limitation" role="tabpanel" aria-labelledby="pills-limitation-tab">
                @if($id_solicitud_rc != 0)
                    @include('solicitud.limitacion')
                @endif
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
                let jsondata = JSON.parse(data);
                let solicitud_id = jsondata.solicitud_id;
                if(parseInt(solicitud_id) != 0){                
                    $("#pills-profile").html(jsondata.html);
                    $("#pills-profile").toggleClass('show');
                    $("#pills-home").toggleClass('show');
                    $("#pills-profile-tab").attr("href","#pills-profile");
                    $("#pills-profile-tab").toggleClass('disabled');
                    $("#pills-profile-tab").attr("aria-disabled",false);
                    $("#pills-profile-tab").click();
                }

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

                if(parseInt(solicitud_id) != 0){
                    window.location.href= "http://"+"{{$_SERVER['HTTP_HOST']}}"+ "/solicitud/continuar/"+ solicitud_id;
                }
                else{
                    new PNotify({
                        title: 'Crear Solicitud',
                        text: 'Solicitud actualizada exitosamente',
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
        $("#pills-docs").removeClass('show');
        $("#pills-limitation").removeClass('show');
        $("#pills-ppu").removeClass('show');

    });

    $(document).on("click","#pills-profile-tab",function(e){
        $("#pills-home").removeClass('show');
        $("#pills-contact").removeClass('show');
        $("#pills-invoice").removeClass('show');
        $("#pills-docs").removeClass('show');
        $("#pills-limitation").removeClass('show');
        $("#pills-ppu").removeClass('show');

    });

    $(document).on("click","#pills-contact-tab",function(e){
        $("#pills-home").removeClass('show');
        $("#pills-profile").removeClass('show');
        $("#pills-invoice").removeClass('show');
        $("#pills-docs").removeClass('show');
        $("#pills-limitation").removeClass('show');
        $("#pills-ppu").removeClass('show');

    });

    $(document).on("click","#pills-invoice-tab",function(e){
        $("#pills-home").removeClass('show');
        $("#pills-profile").removeClass('show');
        $("#pills-contact").removeClass('show');
        $("#pills-docs").removeClass('show');
        $("#pills-limitation").removeClass('show');
        $("#pills-ppu").removeClass('show');

    });

    $(document).on("click","#pills-limitation-tab",function(e){
        $("#pills-home").removeClass('show');
        $("#pills-profile").removeClass('show');
        $("#pills-contact").removeClass('show');
        $("#pills-docs").removeClass('show');
        $("#pills-invoice").removeClass('show');
        $("#pills-ppu").removeClass('show');

    });

    $(document).on("click","#pills-ppu-tab",function(e){
        $("#pills-home").removeClass('show');
        $("#pills-profile").removeClass('show');
        $("#pills-contact").removeClass('show');
        $("#pills-docs").removeClass('show');
        $("#pills-invoice").removeClass('show');
        $("#pills-limitation").removeClass('show');

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
