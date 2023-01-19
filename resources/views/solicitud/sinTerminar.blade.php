@extends("themes.$themes.layout")

@section('contenido')
@include('includes.mensaje')
<div class="panel panel-info panel-border top">
    <div class="panel-heading">
        <span class="panel-title">Solicitudes sin Terminar</span>
        <div class="widget-menu pull-right">
            {{-- <a href="{{route('sucursal.create')}}" class="btn btn-info btn-sm"><li class="fa fa-plus"></li> Agregar Sucursal</a> --}}
        </div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <div class="section">
                    <table id="tabla-data" class="table table-bordered">
                        <thead>
                        <tr>
                            <th scope="col">Solicitud N°</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Sucursal</th>
                            <th scope="col">Cliente</th>
                            <th scope="col" style="width:250px">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($solicitudes as $item)
                            <tr>
                                <td scope="row">{{$item->id}}</td>
                                <td>{{date('d-m-Y h:i A', strtotime($item->created_at))}}</td>
                                <td>{{$item->sucursales}}</td>
                                <td>{{$item->cliente->razon_social_recep}}</td>
                                <td>
                                    <button type="button" class="btn btn-dark btn-sm" onclick="location.href='{{route('solicitud.continuar', ['id' => $item->id])}} '" data-old-onclick="location.href='{{route('solicitud.revision.cedula', ['id' => $item->id])}}'">
                                        <li class="fa fa-pencil"></li> Continuar Ingreso</button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap5.min.css">    
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tabla-data').DataTable({
                language: {
                    "url": "/json/datatable.spanish.json"
                }
            });
        });
    </script>
@endsection