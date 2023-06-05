@extends("themes.$themes.layout")

@section('contenido')

@include('includes.form-error-message')
<form method="post" action="{{route('notaria.update', ['id' => $notaria->id])}}" role="form" class="form-horizontal">
    @csrf
    @method('PUT')
    <div class="panel panel-info panel-border top">
        <div class="panel-heading">
            <span class="panel-title">Editar Notaria</span>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label for="id" class="col-lg-1 control-label">Código</label>
                <label class="col-lg-1">
                    <input type="text" name="id" id="id" class="form-control" value="{{$notaria->id}}" disabled>
                </label>
                
                <label for="name" class="col-lg-1 control-label">Nombre Notaria</label>
                <label class="col-lg-5">
                    <input type="text" name="name" id="name" class="form-control" value="{{$notaria->name}}">
                </label>

                
                
                <label for="codigo_notaria_rc" class="col-lg-1 control-label">Código Notaria RC</label>
                <label class="col-lg-5">
                    <input type="text" name="codigo_notaria_rc" id="codigo_notaria_rc" class="form-control" placeholder="Nombre de Notaria" value="{{ $notaria->codigo_notaria_rc }}">
                </label>
                
            </div>
            
        </div>
        <div class="panel-footer">
            <button id="btn_cancelar" type="button" class="btn btn-sm btn-default" onclick="location.href='{{route('notaria.index')}}'"> Cancelar </button>
            <button type="submit" class="btn btn-sm btn-system"> Grabar </button>
        </div>
    </div>
</form>

@endsection
