@extends("themes.$themes.layout")

@section('contenido')
@include('includes.mensaje')

<div class="panel panel-info panel-border top">
    <div class="panel-heading">
        <span class="panel-title">Listado de Usuarios</span>
        <div class="widget-menu pull-right">
            <a href="{{route('usuario.create')}}" class="btn btn-info btn-sm"><li class="fa fa-plus"></li> Agregar Usuario</a>
        </div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <div class="section">
                    <table id="tabla-data" class="table table-bordered">
                        <thead>
                        <tr>
                            <th scope="col">Nombre</th>
                            <th scope="col">Email</th>
                            <th scope="col">Rol</th>
                            <th scope="col" style="width:350px">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($usuarios as $item)
                            <tr>
                                <td scope="row">{{$item->name}}</td>
                                <td>{{$item->email}}</td>
                                <td>{{$item->rol}}</td>
                                <td>
                                    <form action="{{route('usuario.destroy', ['id' => $item->id])}}" class="form-eliminar" method="post">
                                        @csrf
                                        @method('DELETE')

                                        @if ($item->rol_id!=1)
                                        <button type="button" class="btn btn-system btn-sm" onclick="location.href='{{route('usuario.edit', ['id' => $item->id])}}'">
                                            <li class="fa fa-pencil"></li> Editar</button>
                                        @endif
                                        <button type="button" class="btn btn-default btn-sm" onclick="location.href='{{route('usuario.password', ['id' => $item->id])}}'">
                                            <li class="glyphicons glyphicons-keys"></li> Cambiar Password</button>
                                        @if ($item->rol_id!=1)
                                        <button type="submit" class="btn btn-danger btn-sm"><li class="fa fa-trash-o"></li> Eliminar</button>
                                        @endif
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