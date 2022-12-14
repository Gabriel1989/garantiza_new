@extends("themes.$themes.layout")

@section('contenido')
@include('includes.mensaje')
<div class="panel panel-info panel-border top">
    <div class="panel-heading">
        <span class="panel-title">Listado de Tipos de Vehículos</span>
        <div class="widget-menu pull-right">
            <a href="{{route('tipo_vehiculo.create')}}" class="btn btn-info btn-sm"><li class="fa fa-plus"></li> Agregar Tipos de Vehículo</a>
        </div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <div class="section">
                    <table id="tabla-data" class="table table-bordered">
                        <thead>
                        <tr>
                            <th scope="col">Código</th>
                            <th scope="col">Tipos de Vehículo</th>
                            <th scope="col" style="width:250px">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($tipos as $item)
                            <tr>
                                <td scope="row">{{$item->id}}</td>
                                <td>{{$item->name}}</td>
                                <td>
                                    <form action="{{route('tipo_vehiculo.destroy', ['id' => $item->id])}}" class="form-eliminar" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-system btn-sm" onclick="location.href='{{route('tipo_vehiculo.edit', ['id' => $item->id])}}'">
                                            <li class="fa fa-trash-o"></li> Editar</button>
                                        <button type="submit" class="btn btn-danger btn-sm"><li class="fa fa-trash-o"></li> Eliminar</button>
                                    </form>
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
    <!-- Datatables Editor addon - READ LICENSING NOT MIT  -->
    {{-- <script type="text/javascript" src="{{asset("assets/$themes/vendor/plugins/datatables/extensions/Editor/js/dataTables.editor.js")}}"></script> --}}
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