@extends("themes.$themes.layout")

@section('contenido')

@include('includes.form-error-message')
<form method="post" action="{{route('tipo_vehiculo.update', ['id' => $tipo->id])}}" role="form" class="form-horizontal">
    @csrf
    @method('PUT')
    <div class="panel panel-info panel-border top">
        <div class="panel-heading">
            <span class="panel-title">Editar Tipo de Vehículo</span>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label for="id" class="col-lg-1 control-label">Código</label>
                <label class="col-lg-1">
                    <input type="text" name="id" id="id" class="form-control" value="{{$tipo->id}}" disabled>
                </label>
                
                <label for="name" class="col-lg-1 control-label"> Tipo de Vehículo</label>
                <label class="col-lg-5">
                    <input type="text" name="name" id="name" class="form-control" value="{{$tipo->name}}">
                </label>
            </div>
            
        </div>
        <div class="panel-footer">
            <button id="btn_cancelar" type="button" class="btn btn-sm btn-default" onclick="location.href='{{route('tipo_vehiculo.index')}}'"> Cancelar </button>
            <button type="submit" class="btn btn-sm btn-system"> Grabar </button>
        </div>
    </div>
</form>

@endsection

@section('scripts')

@endsection