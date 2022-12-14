@extends("themes.$themes.layout")

@section('contenido')
@include('includes.mensaje')
<div class="panel panel-info panel-border top">
    <div class="panel-heading">
        <span class="panel-title">Revisión de Solicitud</span>
    </div>
    <div class="panel">
        <div class="panel-body">
            <ul class="nav nav-pills mb20">
                <li class="active">
                    <a href="#tab18_1" data-toggle="tab">Datos Cédula</a>
                </li>
                <li>
                    <a href="#tab18_2" data-toggle="tab">Datos Factura</a>
                </li>
                <li>
                    <a href="#tab18_3" data-toggle="tab">Información del Vehículo</a>
                </li>
                <li>
                    <a href="#tab18_4" data-toggle="tab">Finaliza Revisión</a>
                </li>
            </ul>
            <div class="tab-content br-n pn">
                <div id="tab18_1" class="tab-pane active">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="name" class="col-lg-1 control-label mt10">RUT:</label>
                                <label class="col-lg-3">
                                    <p class="form-control-static text-muted">{{$header->RUTRecep}}</p>
                                </label>

                                <label for="name" class="col-lg-1 control-label mt10">Nombre:</label>
                                <label class="col-lg-5">
                                    <p class="form-control-static text-muted">{{$header->RznSocRecep}}</p>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <iframe width="400" height="400" src="/{{str_replace('public/', 'storage/', $cedula_cliente->name)}}" frameborder="0"></iframe>
                        </div>
                    </div>
                </div>
                <div id="tab18_2" class="tab-pane">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="name" class="col-lg-1 control-label mt10">RUT:</label>
                                <label class="col-lg-3">
                                    <p class="form-control-static text-muted">{{$header->RUTRecep}}</p>
                                </label>

                                <label for="name" class="col-lg-1 control-label mt10">Nombre:</label>
                                <label class="col-lg-5">
                                    <p class="form-control-static text-muted">{{$header->RznSocRecep}}</p>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <iframe width="400" height="400" src="/{{str_replace('public/', 'storage/', $factura->name)}}" frameborder="0"></iframe>
                        </div>
                    </div>
                </div>
                <div id="tab18_3" class="tab-pane">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="name" class="col-lg-1 control-label mt10">RUT:</label>
                                <label class="col-lg-3">
                                    <p class="form-control-static text-muted">{{$header->RUTRecep}}</p>
                                </label>

                                <label for="name" class="col-lg-1 control-label mt10">Nombre:</label>
                                <label class="col-lg-5">
                                    <p class="form-control-static text-muted">{{$header->RznSocRecep}}</p>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <img src="assets/img/stock/3.jpg" class="img-responsive thumbnail mr25">
                        </div>
                    </div>
                </div>
                <div id="tab18_4" class="tab-pane">
                    <div class="row">
                        <div class="col-md-8">
                            <p>4. Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident.</p>
                        </div>
                        <div class="col-md-4">
                            <img src="assets/img/stock/3.jpg" class="img-responsive thumbnail mr25">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <button id="btn_cancelar" type="button" class="btn btn-sm btn-default" onclick="location.href='{{route('solicitud.revision')}}'"> Cancelar </button>
            <button type="submit" class="btn btn-sm btn-system"> Siguiente </button>
        </div>
    </div>
</div>
@endsection