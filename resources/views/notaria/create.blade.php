@extends("themes.$themes.layout")

@section('contenido')

@include('includes.form-error-message')
<form method="post" action="{{route('notaria.store')}}" role="form" class="form-horizontal">
    @csrf
    @method('POST')
    <div class="panel panel-info panel-border top">
        <div class="panel-heading">
            <span class="panel-title">Agregar Notaria</span>
        </div>
        <div class="panel-body">
            <div class="form-group">
                
                <label for="name" class="col-lg-1 control-label">Nombre Notaria</label>
                <label class="col-lg-5">
                    <input type="text" name="name" id="name" class="form-control" placeholder="Nombre de Notaria" value="{{old('name')}}">
                </label>
            </div>

            <div class="form-group">
                
                <label for="codigo_notaria_rc" class="col-lg-1 control-label">Código Notaria RC</label>
                <label class="col-lg-5">
                    <input type="text" name="codigo_notaria_rc" id="codigo_notaria_rc" class="form-control" placeholder="Nombre de Notaria" value="{{old('codigo_notaria_rc')}}">
                </label>
            </div>
        </div>
        </div>
        <div class="panel-footer">
            <button id="btn_cancelar" type="button" class="btn btn-sm btn-default" onclick="location.href='{{route('notaria.index')}}'"> Cancelar </button>
            <button type="submit" class="btn btn-sm btn-system"> Grabar </button>
        </div>
    </div>
</form>

@endsection

@section('scripts')

<script>
    $(document).ready(function() {
        
    });
</script>
@endsection