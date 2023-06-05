@extends("themes.$themes.layout")

@section('contenido')

@include('includes.form-error-message')
<form method="post" action="{{route('concesionaria.update', ['id' => $concesionaria->id])}}" role="form" class="form-horizontal">
    @csrf
    @method('PUT')
    <div class="panel panel-info panel-border top">
        <div class="panel-heading">
            <span class="panel-title">Editar Concesionaria</span>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label for="id" class="col-lg-1 control-label">Código</label>
                <label class="col-lg-1">
                    <input type="text" name="id" id="id" class="form-control" value="{{$concesionaria->id}}" disabled>
                </label>

                <label for="rut" class="col-lg-1 control-label">Rut</label>
                <label class="col-lg-1">
                    <input type="text" name="rut" id="rut" class="form-control" value="{{number_format($concesionaria->rut, 0, ',', '.') . '-' . $concesionaria->dv}}">
                </label>
                
                <label for="name" class="col-lg-1 control-label">Concesionaria</label>
                <label class="col-lg-5">
                    <input type="text" name="name" id="name" class="form-control" value="{{$concesionaria->name}}">
                </label>

                <label for="razon_social" class="col-lg-1 control-label">Razón social</label>
                <label class="col-lg-5">
                    <input type="text" name="razon_social" id="name" class="form-control" value="{{$concesionaria->name}}">
                </label>
            </div>
            
        </div>
        <div class="panel-footer">
            <button id="btn_cancelar" type="button" class="btn btn-sm btn-default" onclick="location.href='{{route('concesionaria.index')}}'"> Cancelar </button>
            <button type="submit" class="btn btn-sm btn-system"> Grabar </button>
        </div>
    </div>
</form>

@endsection

@section('scripts')
<script src="/js/jquery.rut.min.js"></script>

<script>
    $(document).ready(function() {
        $("input#rut").rut({
            formatOn: 'keyup',
            minimumLength: 8, 
            validateOn: 'change' 
        });

        $("input#rut").rut().on('rutInvalido', function(e) {
            Garantiza.notificaciones('El Rut ingresado no es válido.', 'Garantiza', 'danger');
        });
    });
</script>
@endsection