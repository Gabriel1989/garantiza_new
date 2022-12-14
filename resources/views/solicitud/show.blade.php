@extends("themes.$themes.layout")

@section('contenido')

@include('includes.form-error-message')
<form method="get" action="" role="form" class="form-horizontal">
    <div class="panel panel-info panel-border top">
        <div class="panel-heading">
            <span class="panel-title">Solicitud</span>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label for="name" class="col-lg-1 control-label">Solicitud N° :</label>
                <label class="col-lg-3">
                    <p class="form-control-static text-muted">{{$solicitud[0]->id}}</p>
                </label>
                <label for="name" class="col-lg-11 control-label"></label>
            </div>
            <div class="form-group">
                <label for="name" class="col-lg-1 control-label">Ejecutivo :</label>
                <label class="col-lg-3">
                    <p class="form-control-static text-muted">{{Auth::user()->name}}</p>
                </label>

                <label for="name" class="col-lg-1 control-label">Sucursal :</label>
                <label class="col-lg-5">
                    <p class="form-control-static text-muted"></p>
                </label>
            </div>
            <div class="form-group">
                <label for="name" class="col-lg-1 control-label">Rut Cliente :</label>
                <label class="col-lg-2">
                    <p class="form-control-static text-muted">{{$solicitud[0]->rutCliente}}</p>
                </label>

                <label for="name" class="col-lg-2 control-label">Nombre del Cliente :</label>
                <label class="col-lg-4">
                    <p class="form-control-static text-muted">{{$solicitud[0]->nombreCliente}}</p>
                </label>

                <label for="name" class="col-lg-1 control-label">Cliente Empresa :</label>
                <label class="col-lg-1">
                    <p class="form-control-static text-muted">
                        @if (property_exists($solicitud[0], 'empresa'))
                            Si
                        @else
                            No
                        @endif
                    </p>
                </label>
            </div>
            <div class="form-group">
                <label for="name" class="col-lg-1 control-label">Tipo de Vehículo :</label>
                <label class="col-lg-3">
                    <p class="form-control-static text-muted">{{$solicitud[0]->name}}</p>
                </label>

                <label for="name" class="col-lg-1 control-label">Tipo de Trámite :</label>
                <label class="col-lg-4">
                    <p class="form-control-static text-muted">
                        @foreach ($tramites as $item)
                        {{$item->name}} -  
                        @endforeach
                    </p>
                </label>
            </div>
            <div class="form-group">
                <label for="name" class="col-lg-1 control-label">PPU :</label>
                <label class="col-lg-3">
                    <p class="form-control-static text-muted">
                        @if (is_null($solicitud[0]->termino_1))
                            Sin PPU
                        @else
                            [{{$solicitud[0]->termino_1}}, {{$solicitud[0]->termino_2}}, {{$solicitud[0]->termino_3}}]
                        @endif
                    </p>
                </label>

                <label for="name" class="col-lg-1 control-label">Crédito Directo :</label>
                <label class="col-lg-4">
                    <p class="form-control-static text-muted">
                        @if (property_exists($solicitud[0], 'credito'))
                            Si
                        @else
                            No
                        @endif
                    </p>
                </label>

                <label for="name" class="col-lg-1 control-label">Prenda :</label>
                <label class="col-lg-1">
                    <p class="form-control-static text-muted">
                        @if (property_exists($solicitud[0], 'prenda'))
                            Si
                        @else
                            No
                        @endif
                    </p>
                </label>
            </div>
            @if (!is_null($para))
            <div class="form-group">
                <label for="name" class="col-lg-1 control-label">Rut Para :</label>
                <label class="col-lg-3">
                    <p class="form-control-static text-muted">{{$para[0]->rutPara}}</p>
                </label>

                <label for="name" class="col-lg-1 control-label">Nombre Para :</label>
                <label class="col-lg-4">
                    <p class="form-control-static text-muted">{{$para[0]->namePara}}</p>
                </label>

                <label for="name" class="col-lg-1 control-label">Dirección Para :</label>
                <label class="col-lg-1">
                    <p class="form-control-static text-muted">{{$para[0]->addressPara}}</p>
                </label>
            </div>
            @endif
            <div class="form-group">
                <label for="name" class="col-lg-1 control-label">Doctos adjuntos :</label>
                <label for="name" class="col-lg-10 control-label"></label>
                <ul class="col-lg-6">
                    @foreach ($documentos as $item)
                    <li> {{$item->description}}  </li>
                    @endforeach
                </ul>
            </div>
            <div class="panel-footer">
                <h4 class="text-system"><b>Solicitud completada y enviada para su aprobación</b></h4>
            </div>
        </div>
    </div>
</form>

@endsection

@section('scripts')
@endsection
