@extends("themes.$themes.layout")

@section('contenido')

@include('includes.form-error-message')
@php
    use App\Models\Transferencia;
    if($id_transferencia != 0){
        $estadoSolicitud = Transferencia::select('estado_id')->where('id',$id_transferencia)->first();
        $estadoSolicitud = $estadoSolicitud->estado_id;
    }
    else{
        $estadoSolicitud = '';
    }
@endphp
    <div class="panel panel-info panel-border top">
        <div class="panel-heading">
            <span class="panel-title">Revisión de Transferencia N° {{$id}} - Datos Solicitud del Cliente</span>
        </div>
        <div class="panel-body">
            <ul class="nav nav-pills mb-3" id="transferencia-tab" role="tablist">
                <li class="nav-item @if($solicita_data == false)  active @endif" role="presentation">
                    <a class="nav-link @if($solicita_data == false)  active @endif" id="pills-consultappu-tab" data-toggle="pill" href="#pills-consultappu" role="tab" aria-controls="pills-consultappu" aria-selected="true">Consultar PPU</a>
                </li>
    
                <li class="nav item @if($solicita_data == true && $id_transferencia == 0)  active @endif" role="presentation">
                    <a class="nav-link @if($solicita_data == false)  disabled @endif" id="pills-datavehiculo-tab" data-toggle="pill" @if($solicita_data == false) href="#" @else  href="#pills-datavehiculo" @endif role="tab" aria-controls="pills-datavehiculo" aria-selected="true">Crear solicitud</a>
                </li>
    
                <li class="nav-item @if($id_transferencia != 0 && $id_comprador == 0 && $id_transferencia_rc == 0)  active    @endif" role="presentation">
                    <a class="nav-link @if($id_transferencia == 0)  disabled    @endif" id="pills-comprador-tab" data-toggle="pill" @if($id_transferencia != 0) href="#pills-comprador" @else href="#" @endif role="tab" aria-controls="pills-comprador" aria-selected="false" @if($id_transferencia == 0) aria-disabled="true" @endif>Comprador</a>
                </li>
    
                <li class="nav-item @if($id_transferencia != 0 && $id_comprador != 0 && $id_transferencia_rc == 0 && $id_vendedor == 0)  active    @endif" role="presentation">
                    <a class="nav-link @if($id_transferencia == 0 && $id_comprador == 0)  disabled    @endif" id="pills-vendedor-tab" data-toggle="pill" @if($id_transferencia != 0 && $id_comprador != 0) href="#pills-vendedor" @else href="#" @endif role="tab" aria-controls="pills-vendedor" aria-selected="false" @if($id_transferencia == 0 && $id_comprador == 0) aria-disabled="true" @endif>Vendedor</a>
                </li>
    
                <li class="nav-item @if($id_transferencia != 0 && $id_comprador != 0 && $id_vendedor != 0 && $id_transferencia_rc == 0 && $id_estipulante == 0)  active    @endif" role="presentation">
                    <a class="nav-link @if($id_transferencia == 0 && $id_comprador == 0 && $id_vendedor == 0)  disabled    @endif" id="pills-estipulante-tab" data-toggle="pill" @if($id_transferencia != 0 && $id_comprador != 0 && $id_vendedor != 0) href="#pills-estipulante" @else href="#" @endif role="tab" aria-controls="pills-estipulante" aria-selected="false" @if($id_transferencia == 0 && $id_comprador == 0 && $id_vendedor == 0) aria-disabled="true" @endif>Estipulante</a>
                </li>
    
                <li class="nav-item @if($id_transferencia != 0 && $id_comprador != 0 && $id_vendedor != 0 && $id_transferencia_rc == 0 && $id_estipulante != 0 && $estadoSolicitud == 4)  active    @endif" role="presentation">
                    <a class="nav-link @if($id_transferencia == 0 && $id_comprador == 0 && $id_vendedor == 0 && $id_transferencia_rc == 0 && $id_estipulante == 0)  disabled    @endif" id="pills-invoice-tab" data-toggle="pill" @if($id_transferencia != 0 && $id_comprador != 0 && $id_vendedor != 0 && $id_estipulante != 0) href="#pills-invoice" @else href="#" @endif role="tab" aria-controls="pills-invoice" aria-selected="false" @if($id_transferencia == 0 && $id_comprador == 0 && $id_vendedor == 0 && $id_estipulante == 0) aria-disabled="true" @endif>Datos adicionales</a>
                </li>
    
                <li class="nav-item @if($acceso == "revision") 
                                        @if($id_transferencia_rc != 0 && $documento_rc == null)  
                                            active 
                                        @elseif($estadoSolicitud == 5 || $estadoSolicitud == 12)
                                            active
                                        @endif 
                                    @elseif($acceso == "ingreso") 
                                        @if($estadoSolicitud != '' && ($estadoSolicitud == 5 || $estadoSolicitud == 12))   
                                            active
                                        @endif   
                                    @endif" role="presentation">
                    <a class="nav-link @if($acceso == "revision") 
                    @if($id_transferencia_rc != 0 && $documento_rc == null)  
                        disabled 
                    @endif 
                @elseif($acceso == "ingreso") 
                    @if($estadoSolicitud != '' && $estadoSolicitud != 5)   
                        disabled
                    @endif   
                @endif" id="pills-docs-tab" data-toggle="pill"  
                                                            @if($acceso == "revision")
                                                                @if($id_transferencia_rc != 0) 
                                                                    href="#pills-docs"
                                                                @elseif($estadoSolicitud == 5 || $estadoSolicitud == 12)
                                                                    href="#pills-docs"
                                                                @else 
                                                                    href="#"
                                                                @endif 
                                                            @elseif($acceso == "ingreso")
                                                                @if($estadoSolicitud != '' && $estadoSolicitud == 5)
                                                                    href="#pills-docs"
                                                                @elseif($id_transferencia_rc != 0) 
                                                                    href="#pills-docs"
                                                                @else
                                                                    href="#"
                                                                @endif
                                                            @endif
                                                                role="tab" aria-controls="pills-docs" aria-selected="false" @if($id_transferencia_rc == 0) aria-disabled="true" @endif>Documentación</a>
                </li>
    
                <li class="nav-item" role="presentation">
                    <a class="nav-link" 
                    id="pills-limitation-tab" data-toggle="pill" href="#pills-limitation" role="tab" aria-controls="pills-limitation" aria-selected="false" @if($id_transferencia_rc == 0) aria-disabled="true" @endif>Limitación</a>
                </li>
    
                @if($acceso == "revision")
                <li class="nav-item @if($id_transferencia_rc != 0 && $documento_rc != null)  active @endif" role="presentation">
                    <a class="nav-link @if($id_transferencia_rc == 0)  disabled  @elseif($id_transferencia_rc != 0 && $documento_rc == null) disabled @elseif($documento_rc != null) active @endif" id="pills-pay-tab" data-toggle="pill" @if($documento_rc != null) href="#pills-pay" @else href="#" @endif role="tab" aria-controls="pills-pay" aria-selected="false" @if($id_transferencia_rc == 0) aria-disabled="true" @endif>Pago</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link @if($id_transferencia_rc == 0)  disabled  @elseif($id_transferencia_rc != 0 && $documento_rc == null) disabled @endif" id="pills-voucher-tab" data-toggle="pill" @if($documento_rc != null) href="#pills-voucher" @else href="#" @endif role="tab" aria-controls="pills-voucher" aria-selected="false" @if($id_transferencia_rc == 0) aria-disabled="true" @endif>Comprobantes</a>
                </li>
                @elseif($acceso == "ingreso")
                <li class="nav-item" role="presentation">
                    <a class="nav-link " id="pills-voucher-tab" data-toggle="pill" href="#pills-voucher" role="tab" aria-controls="pills-voucher" aria-selected="false">Comprobante Solicitud</a>
                </li>
                @endif
    
            </ul>
            <div class="tab-pane fade @if($solicita_data == false)  active show in @endif" id="pills-consultappu" role="tabpanel" aria-labelledby="pills-consultappu-tab">
                @include('transferencia.consultarPPU')
            </div>
            <div class="tab-pane fade @if($solicita_data == true && $id_transferencia == 0)  active show in @endif" id="pills-datavehiculo" role="tabpanel" aria-labelledby="pills-datavehiculo-tab">
                @if($solicita_data == true)
                    @include('transferencia.dataVehiculo')
                @endif
            </div>
            <div class="tab-pane fade @if($id_transferencia != 0 && $id_comprador == 0 && $id_transferencia_rc == 0) show  active in @endif" id="pills-comprador" role="tabpanel" aria-labelledby="pills-comprador-tab">
                @if($id_transferencia != 0)
                    @include('transferencia.comprador')
                @endif
            </div>
    
            <div class="tab-pane fade @if($id_transferencia != 0 && $id_comprador != 0 && $id_transferencia_rc == 0 && $id_vendedor == 0) show  active in @endif" id="pills-vendedor" role="tabpanel" aria-labelledby="pills-vendedor-tab">
                @if($id_transferencia != 0 && $id_comprador != 0)
                    @include('transferencia.vendedor')
                @endif
            </div>
    
            <div class="tab-pane fade @if($id_transferencia != 0 && $id_comprador != 0 && $id_vendedor != 0 && $id_transferencia_rc == 0 && $id_estipulante == 0) show  active in @endif" id="pills-estipulante" role="tabpanel" aria-labelledby="pills-estipulante-tab">
                @if($id_transferencia != 0 && $id_comprador != 0 && $id_vendedor != 0)
                    @include('transferencia.estipulante')
                @endif
            </div>
    
            <div class="tab-pane fade @if($id_transferencia != 0 && $id_comprador != 0 && $id_vendedor != 0 && $id_transferencia_rc == 0 && $id_estipulante != 0 && $estadoSolicitud == 4) show  active in @endif" id="pills-invoice" role="tabpanel" aria-labelledby="pills-invoice-tab">
                @if($id_transferencia != 0 && $id_comprador != 0 && $id_vendedor != 0 && $id_transferencia_rc == 0 && $id_estipulante != 0)
                    @include('transferencia.dataResumen')
    
                @elseif($id_transferencia_rc != 0)    
                    @include('transferencia.dataResumen')
                @endif
            </div>
    
            <div class="tab-pane fade
                    @if($acceso == "revision") 
                        @if($id_transferencia_rc != 0 && $documento_rc == null)  
                            active show in 
                        @elseif($estadoSolicitud == 5 || $estadoSolicitud == 12)
                            active show in
                        @endif
                    @elseif($acceso == "ingreso")
                        @if($estadoSolicitud == 5 || $estadoSolicitud == 12)
                            show active in
                        @endif
                    @endif
                    " id="pills-docs" role="tabpanel" aria-labelledby="pills-docs-tab">
                    @if($acceso == "revision")
                        @if($id_transferencia_rc != 0)
                            @include('transferencia.menuDocs')
                        @elseif($estadoSolicitud == 5 || $estadoSolicitud == 12)
                            @include('transferencia.menuDocs')
                        @endif
                    @elseif($acceso == "ingreso")
                        @if($estadoSolicitud == 5)
                            @include('transferencia.menuDocs')
                        @elseif($id_transferencia_rc != 0)
                            @include('transferencia.menuDocs')
                        @endif
                    @endif
            </div>
    
            <div class="tab-pane fade" id="pills-limitation" role="tabpanel" aria-labelledby="pills-limitation-tab">
                @if($acceso == "revision")
                    @include('transferencia.limitacion')
                @elseif($acceso == "ingreso")
                    @if($id_transferencia != 0)
                        @include('transferencia.limitacion')
                    @endif
                @endif
            </div>
    
            @if($acceso == "revision")
            <div class="tab-pane fade @if($id_transferencia_rc != 0 && $documento_rc != null)  active show in @endif" id="pills-pay" role="tabpanel" aria-labelledby="pills-pay-tab">
                @if($documento_rc != null)
                    @include('transferencia.pagos')
                @endif
            </div>
            <div class="tab-pane fade" id="pills-voucher" role="tabpanel" aria-labelledby="pills-voucher-tab">
                @if($documento_rc != null)
                    @include('transferencia.comprobante')
                @endif
            </div>
            @elseif($acceso == "ingreso")
            <div class="tab-pane fade" id="pills-voucher" role="tabpanel" aria-labelledby="pills-voucher-tab">
                    @include('transferencia.comprobante')
            </div>
            @endif
            <form method="post" action="{{route('transferencia.updateRevisionCedula', ['id' => $id])}}" role="form" class="form-horizontal form-revision">
                @csrf
                @method('PUT')
                <div class="form-group" style="padding: 5px;">
                    <div class="row">
                        <div class="col-lg-4">
                            @if($cedula_comprador != null)
                            <label>Cédula Comprador</label>
                                <div class="flex-container">
                                    <div class="responsive-iframe">
                                        <iframe width="400" height="400" src="/{{str_replace('public/', 'storage/', $cedula_comprador->name)}}" frameborder="0"></iframe>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-lg-4">
                            @if($cedula_estipulante != null)
                            <label>Cédula Estipulante</label>
                            <div class="flex-container">
                                <div class="responsive-iframe">
                                        <iframe width="400" height="400" src="/{{str_replace('public/', 'storage/', $cedula_estipulante->name)}}" frameborder="0"></iframe>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="col-lg-3">
                            @if($cedula_vendedor != null)
                            <label>Cédula Vendedor</label>
                            <div class="flex-container">
                                <div class="responsive-iframe">
                                    <iframe width="400" height="400" src="/{{str_replace('public/', 'storage/', $cedula_vendedor->name)}}" frameborder="0"></iframe>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            @if($doc_transferencia != null)
                            <label>Documento Transferencia</label>
                            <div class="flex-container">
                                <div class="responsive-iframe">
                                    <iframe width="400" height="400" src="/{{str_replace('public/', 'storage/', $doc_transferencia->name)}}" frameborder="0"></iframe>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="col-lg-4">
                            @if($rol_empresa != null)
                            <label>Rol de empresa</label>
                            <div class="flex-container">
                                <div class="responsive-iframe">
                                    <iframe width="400" height="400" src="/{{str_replace('public/', 'storage/', $rol_empresa->name)}}" frameborder="0"></iframe>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="col-lg-3">
                            @if($doc_limitacion != null)
                            <label>Documento limitación/prohibición</label>
                            <div class="flex-container">
                                <div class="responsive-iframe">
                                    <iframe width="400" height="400" src="/{{str_replace('public/', 'storage/', $doc_limitacion->name)}}" frameborder="0"></iframe>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-4"></div>
                    <div class="col-lg-8">
                        <div class="row">
                            <label for="name" class="col-lg-2 control-label ">RUT:</label>
                            <label class="col-lg-10">
                                <p class="form-control-static text-muted">{{$header->RUTRecep}}</p>
                            </label>
                        </div>
                        <div class="row">
                            <label for="name" class="col-lg-2 control-label ">Nombre:</label>
                            <label class="col-lg-10">
                                <p class="form-control-static text-muted">{{$header->RznSocRecep}}</p>
                            </label>
                        </div>
                        <div class="row">
                            <label class="col-lg-2 control-label">Aprobado:</label>
                            <div class="col-lg-10">
                                <div class="switch switch-success switch-inline">
                                    <input type="checkbox" id="aprobado" name="aprobado" value="0">
                                    <label for="aprobado"></label>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="motivo">
                            <label for="motivo_rechazo" class="col-lg-2 control-label">Motivo de Rechazo:</label>
                            <div class="col-lg-10">
                                <select name="motivo_rechazo" id="motivo_rechazo">
                                    <option value="0" selected>Seleccione un Motivo de Rechazo ...</option>
                                    @foreach($rechazos as $r)

                                        <option value="{{$r->id}}">{{$r->motivo}}</option>

                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <button type="submit" class="btn btn-system"><li class="fa fa-save"></li>  Grabar y Continuar Revisión </button>
                </div>
            </form>
        </div>
        
    </div>

@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function() {
        $('#motivo_rechazo').multiselect();

        $('#aprobado').on('change', function(e) {
            if ($('#aprobado').prop('checked')==true){
                $('#motivo').hide();
            }else{
                $('#motivo').show();
            }
        });

        $(".form-revision").on('submit', function () {
            var aprobado = $('#aprobado').prop('checked');
            var motivo = $('#motivo_rechazo').val(); 
            if(!aprobado&&motivo==0){
                new PNotify({
                    title: 'Selección de Motivo',
                    text: 'Debe seleccionar un motivo de Rechazo.',
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
        });

    });
</script>


<script>

$(document).ready(function(){
    
    
    if($("#pills-comprador").hasClass('show')){
        $("#pills-consultappu").addClass('hide');
        $("#pills-datavehiculo").addClass('hide');
        $("#pills-vendedor").addClass('hide');
        $("#pills-estipulante").addClass('hide');
        $("#pills-invoice").addClass('hide');
        $("#pills-docs").addClass('hide');
        $("#pills-limitation").addClass('hide');
        $("#pills-voucher").addClass('hide');
        $("#pills-pay").addClass('hide');
    }

    if($("#pills-vendedor").hasClass('show')){
        $("#pills-consultappu").addClass('hide');
        $("#pills-datavehiculo").addClass('hide');
        $("#pills-comprador").addClass('hide');
        $("#pills-estipulante").addClass('hide');
        $("#pills-invoice").addClass('hide');
        $("#pills-docs").addClass('hide');
        $("#pills-limitation").addClass('hide');
        $("#pills-voucher").addClass('hide');
        $("#pills-pay").addClass('hide');
    }

    if($("#pills-estipulante").hasClass('show')){
        $("#pills-consultappu").addClass('hide');
        $("#pills-datavehiculo").addClass('hide');
        $("#pills-comprador").addClass('hide');
        $("#pills-vendedor").addClass('hide');
        $("#pills-invoice").addClass('hide');
        $("#pills-docs").addClass('hide');
        $("#pills-limitation").addClass('hide');
        $("#pills-voucher").addClass('hide');
        $("#pills-pay").addClass('hide');
    }

    if($("#pills-invoice").hasClass('show')){
        $("#pills-consultappu").addClass('hide');
        $("#pills-datavehiculo").addClass('hide');
        $("#pills-comprador").addClass('hide');
        $("#pills-vendedor").addClass('hide');
        $("#pills-estipulante").addClass('hide');
        $("#pills-docs").addClass('hide');
        $("#pills-limitation").addClass('hide');
        $("#pills-voucher").addClass('hide');
        $("#pills-pay").addClass('hide');
    }

    if($("#pills-docs").hasClass('show')){
        $("#pills-consultappu").addClass('hide');
        $("#pills-datavehiculo").addClass('hide');
        $("#pills-comprador").addClass('hide');
        $("#pills-vendedor").addClass('hide');
        $("#pills-estipulante").addClass('hide');
        $("#pills-invoice").addClass('hide');
        $("#pills-limitation").addClass('hide');
        $("#pills-voucher").addClass('hide');
        $("#pills-pay").addClass('hide');
    }

    if($("#pills-limitation").hasClass('show')){
        $("#pills-consultappu").addClass('hide');
        $("#pills-datavehiculo").addClass('hide');
        $("#pills-comprador").addClass('hide');
        $("#pills-vendedor").addClass('hide');
        $("#pills-estipulante").addClass('hide');
        $("#pills-invoice").addClass('hide');
        $("#pills-docs").addClass('hide');
        $("#pills-voucher").addClass('hide');
        $("#pills-pay").addClass('hide');
    }

    if($("#pills-voucher").hasClass('show')){
        $("#pills-consultappu").addClass('hide');
        $("#pills-datavehiculo").addClass('hide');
        $("#pills-comprador").addClass('hide');
        $("#pills-vendedor").addClass('hide');
        $("#pills-estipulante").addClass('hide');
        $("#pills-invoice").addClass('hide');
        $("#pills-docs").addClass('hide');
        $("#pills-limitation").addClass('hide');
        $("#pills-pay").addClass('hide');
    }

    if($("#pills-pay").hasClass('show')){
        $("#pills-consultappu").addClass('hide');
        $("#pills-datavehiculo").addClass('hide');
        $("#pills-comprador").addClass('hide');
        $("#pills-vendedor").addClass('hide');
        $("#pills-estipulante").addClass('hide');
        $("#pills-invoice").addClass('hide');
        $("#pills-docs").addClass('hide');
        $("#pills-limitation").addClass('hide');
        $("#pills-voucher").addClass('hide');
    }
});




$(document).on("click","#pills-datavehiculo-tab",function(e){
    //Ocultamos las demás pestañas
    $("#pills-consultappu").removeClass('show');
    $("#pills-consultappu").addClass('hide');

    $("#pills-comprador").removeClass('show');
    $("#pills-comprador").addClass('hide');

    $("#pills-vendedor").removeClass('show');
    $("#pills-vendedor").addClass('hide');

    $("#pills-estipulante").removeClass('show');
    $("#pills-estipulante").addClass('hide');

    $("#pills-invoice").removeClass('show');
    $("#pills-invoice").addClass('hide');

    $("#pills-docs").removeClass('show');
    $("#pills-docs").addClass('hide');

    $("#pills-limitation").removeClass('show');
    $("#pills-limitation").addClass('hide');

    $("#pills-pay").removeClass('show');
    $("#pills-pay").addClass('hide');

    $("#pills-voucher").removeClass('show');
    $("#pills-voucher").addClass('hide');

    //Quitamos hide a nav consultada
    $("#pills-datavehiculo").removeClass('hide');
});

$(document).on("click","#pills-consultappu-tab",function(e){
    //Ocultar las demás pestañas
    $("#pills-datavehiculo").removeClass('show');
    $("#pills-datavehiculo").addClass('hide');

    $("#pills-comprador").removeClass('show');
    $("#pills-comprador").addClass('hide');

    $("#pills-vendedor").removeClass('show');
    $("#pills-vendedor").addClass('hide');

    $("#pills-estipulante").removeClass('show');
    $("#pills-estipulante").addClass('hide');

    $("#pills-invoice").removeClass('show');
    $("#pills-invoice").addClass('hide');

    $("#pills-docs").removeClass('show');
    $("#pills-docs").addClass('hide');

    $("#pills-limitation").removeClass('show');
    $("#pills-limitation").addClass('hide');

    $("#pills-pay").removeClass('show');
    $("#pills-pay").addClass('hide');

    $("#pills-voucher").removeClass('show');
    $("#pills-voucher").addClass('hide');

    //Quitamos hide a nav consultada
    $("#pills-consultappu").removeClass('hide');
});

$(document).on("click","#pills-comprador-tab",function(e){
    //Ocultar las demás pestañas
    $("#pills-datavehiculo").removeClass('show');
    $("#pills-datavehiculo").addClass('hide');

    $("#pills-consultappu").removeClass('show');
    $("#pills-consultappu").addClass('hide');

    $("#pills-vendedor").removeClass('show');
    $("#pills-vendedor").addClass('hide');

    $("#pills-estipulante").removeClass('show');
    $("#pills-estipulante").addClass('hide');

    $("#pills-invoice").removeClass('show');
    $("#pills-invoice").addClass('hide');

    $("#pills-docs").removeClass('show');
    $("#pills-docs").addClass('hide');

    $("#pills-limitation").removeClass('show');
    $("#pills-limitation").addClass('hide');

    $("#pills-pay").removeClass('show');
    $("#pills-pay").addClass('hide');

    $("#pills-voucher").removeClass('show');
    $("#pills-voucher").addClass('hide');
    //Quitamos hide a nav consultada
    $("#pills-comprador").removeClass('hide');
});

$(document).on("click","#pills-vendedor-tab",function(e){
    //Ocultar las demás pestañas
    $("#pills-datavehiculo").removeClass('show');
    $("#pills-datavehiculo").addClass('hide');

    $("#pills-consultappu").removeClass('show');
    $("#pills-consultappu").addClass('hide');

    $("#pills-comprador").removeClass('show');
    $("#pills-comprador").addClass('hide');

    $("#pills-estipulante").removeClass('show');
    $("#pills-estipulante").addClass('hide');

    $("#pills-invoice").removeClass('show');
    $("#pills-invoice").addClass('hide');

    $("#pills-docs").removeClass('show');
    $("#pills-docs").addClass('hide');

    $("#pills-limitation").removeClass('show');
    $("#pills-limitation").addClass('hide');

    $("#pills-pay").removeClass('show');
    $("#pills-pay").addClass('hide');

    $("#pills-voucher").removeClass('show');
    $("#pills-voucher").addClass('hide');
    //Quitamos hide a nav consultada
    $("#pills-vendedor").removeClass('hide');
});

$(document).on("click","#pills-estipulante-tab",function(e){
    //Ocultar las demás pestañas
    $("#pills-datavehiculo").removeClass('show');
    $("#pills-datavehiculo").addClass('hide');

    $("#pills-consultappu").removeClass('show');
    $("#pills-consultappu").addClass('hide');

    $("#pills-comprador").removeClass('show');
    $("#pills-comprador").addClass('hide');

    $("#pills-vendedor").removeClass('show');
    $("#pills-vendedor").addClass('hide');

    $("#pills-invoice").removeClass('show');
    $("#pills-invoice").addClass('hide');

    $("#pills-docs").removeClass('show');
    $("#pills-docs").addClass('hide');

    $("#pills-limitation").removeClass('show');
    $("#pills-limitation").addClass('hide');

    $("#pills-pay").removeClass('show');
    $("#pills-pay").addClass('hide');

    $("#pills-voucher").removeClass('show');
    $("#pills-voucher").addClass('hide');

    //Quitamos hide a nav consultada
    $("#pills-estipulante").removeClass('hide');
});

$(document).on("click","#pills-invoice-tab",function(e){
    //Ocultar las demás pestañas
    $("#pills-datavehiculo").removeClass('show');
    $("#pills-datavehiculo").addClass('hide');

    $("#pills-consultappu").removeClass('show');
    $("#pills-consultappu").addClass('hide');

    $("#pills-comprador").removeClass('show');
    $("#pills-comprador").addClass('hide');

    $("#pills-vendedor").removeClass('show');
    $("#pills-vendedor").addClass('hide');

    $("#pills-estipulante").removeClass('show');
    $("#pills-estipulante").addClass('hide');

    $("#pills-docs").removeClass('show');
    $("#pills-docs").addClass('hide');

    $("#pills-limitation").removeClass('show');
    $("#pills-limitation").addClass('hide');

    $("#pills-pay").removeClass('show');
    $("#pills-pay").addClass('hide');

    $("#pills-voucher").removeClass('show');
    $("#pills-voucher").addClass('hide');
    //Quitamos hide a nav consultada
    $("#pills-invoice").removeClass('hide');
});

$(document).on("click","#pills-limitation-tab",function(e){
    //Ocultar las demás pestañas
    $("#pills-datavehiculo").removeClass('show');
    $("#pills-datavehiculo").addClass('hide');

    $("#pills-consultappu").removeClass('show');
    $("#pills-consultappu").addClass('hide');

    $("#pills-comprador").removeClass('show');
    $("#pills-comprador").addClass('hide');

    $("#pills-vendedor").removeClass('show');
    $("#pills-vendedor").addClass('hide');

    $("#pills-estipulante").removeClass('show');
    $("#pills-estipulante").addClass('hide');

    $("#pills-invoice").removeClass('show');
    $("#pills-invoice").addClass('hide');

    $("#pills-docs").removeClass('show');
    $("#pills-docs").addClass('hide');

    $("#pills-pay").removeClass('show');
    $("#pills-pay").addClass('hide');

    $("#pills-voucher").removeClass('show');
    $("#pills-voucher").addClass('hide');

    //Quitamos hide a nav consultada
    $("#pills-limitation").removeClass('hide');
});


$(document).on("click","#pills-docs-tab", function (e){
    //Ocultar las demás pestañas
    $("#pills-datavehiculo").removeClass('show');
    $("#pills-datavehiculo").addClass('hide');

    $("#pills-consultappu").removeClass('show');
    $("#pills-consultappu").addClass('hide');

    $("#pills-comprador").removeClass('show');
    $("#pills-comprador").addClass('hide');

    $("#pills-vendedor").removeClass('show');
    $("#pills-vendedor").addClass('hide');

    $("#pills-estipulante").removeClass('show');
    $("#pills-estipulante").addClass('hide');

    $("#pills-invoice").removeClass('show');
    $("#pills-invoice").addClass('hide');

    $("#pills-pay").removeClass('show');
    $("#pills-pay").addClass('hide');

    $("#pills-voucher").removeClass('show');
    $("#pills-voucher").addClass('hide');

    $("#pills-limitation").removeClass('show');
    $("#pills-limitation").addClass('hide');

    //Quitamos hide a nav consultada
    $("#pills-docs").removeClass('hide');
});


$(document).on("click","#pills-pay-tab",function(e){
    //Ocultar las demás pestañas
    $("#pills-datavehiculo").removeClass('show');
    $("#pills-datavehiculo").addClass('hide');

    $("#pills-consultappu").removeClass('show');
    $("#pills-consultappu").addClass('hide');

    $("#pills-comprador").removeClass('show');
    $("#pills-comprador").addClass('hide');

    $("#pills-vendedor").removeClass('show');
    $("#pills-vendedor").addClass('hide');

    $("#pills-estipulante").removeClass('show');
    $("#pills-estipulante").addClass('hide');

    $("#pills-invoice").removeClass('show');
    $("#pills-invoice").addClass('hide');

    $("#pills-voucher").removeClass('show');
    $("#pills-voucher").addClass('hide');

    $("#pills-limitation").removeClass('show');
    $("#pills-limitation").addClass('hide');

    $("#pills-docs").removeClass('show');
    $("#pills-docs").addClass('hide');

    //Quitamos hide a nav consultada
    $("#pills-pay").removeClass('hide');
});

$(document).on("click","#pills-voucher-tab",function(e){
    //Ocultar las demás pestañas
    $("#pills-datavehiculo").removeClass('show');
    $("#pills-datavehiculo").addClass('hide');

    $("#pills-consultappu").removeClass('show');
    $("#pills-consultappu").addClass('hide');

    $("#pills-comprador").removeClass('show');
    $("#pills-comprador").addClass('hide');

    $("#pills-vendedor").removeClass('show');
    $("#pills-vendedor").addClass('hide');

    $("#pills-estipulante").removeClass('show');
    $("#pills-estipulante").addClass('hide');

    $("#pills-invoice").removeClass('show');
    $("#pills-invoice").addClass('hide');

    $("#pills-limitation").removeClass('show');
    $("#pills-limitation").addClass('hide');

    $("#pills-docs").removeClass('show');
    $("#pills-docs").addClass('hide');

    $("#pills-pay").removeClass('show');
    $("#pills-pay").addClass('hide');

    //Quitamos hide a nav consultada
    $("#pills-voucher").removeClass('hide');

});
</script>