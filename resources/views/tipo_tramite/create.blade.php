@extends("themes.$themes.layout")

@section('contenido')

@include('includes.form-error-message')
<form method="post" action="{{route('tipo_tramite.store')}}" role="form" class="form-horizontal">
    @csrf
    @method('POST')
    <div class="panel panel-info panel-border top">
        <div class="panel-heading">
            <span class="panel-title">Agregar Tipo de Trámite</span>
        </div>
        <div class="panel-body">
            <div class="form-group">               
                <label for="name" class="col-lg-1 control-label">Tipo de Trámite</label>
                <label class="col-lg-5">
                    <input type="text" name="name" id="name" class="form-control" placeholder="Nombre de Tipo de Trámite" value="{{old('name')}}">
                </label>
            </div>
        </div>
        </div>
        <div class="panel-footer">
            <button id="btn_cancelar" type="button" class="btn btn-sm btn-default" onclick="location.href='{{route('tipo_tramite.index')}}'"> Cancelar </button>
            <button type="submit" class="btn btn-sm btn-system"> Grabar </button>
        </div>
    </div>
</form>

@endsection

