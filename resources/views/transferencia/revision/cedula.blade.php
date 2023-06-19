@extends("themes.$themes.layout")

@section('contenido')

@include('includes.form-error-message')
<form method="post" action="{{route('transferencia.updateRevisionCedula', ['id' => $id])}}" role="form" class="form-horizontal form-revision">
    @csrf
    @method('PUT')
    <div class="panel panel-info panel-border top">
        <div class="panel-heading">
            <span class="panel-title">Revisión de Transferencia N° {{$id}} - Datos Solicitud del Cliente</span>
        </div>
        <div class="panel-body" style="overflow:scroll;">
            <div class="flex-container">
                <div class="responsive-iframe">
                    <iframe width="1400" height="800" src="{{route('transferencia.continuar', ['id' => $id,'reingresa'=> 0,'acceso' => 'revision'])}}"></iframe>
                </div>
            </div>
            <div class="form-group">
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
           
        </div>
        <div class="panel-footer">
            <button type="submit" class="btn btn-system"><li class="fa fa-save"></li>  Grabar y Continuar Revisión </button>
        </div>
    </div>
</form>
@endsection

@section('scripts')
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
@endsection