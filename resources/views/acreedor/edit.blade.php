@extends("themes.$themes.layout")

@section('contenido')

@include('includes.form-error-message')
<form method="post" action="{{route('acreedor.update', ['id' => $acreedor->id])}}" role="form" class="form-horizontal">
    @csrf
    @method('PUT')
    <div class="panel panel-info panel-border top">
        <div class="panel-heading">
            <span class="panel-title">Editar Acreedor</span>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label for="id" class="col-lg-1 control-label">Código</label>
                <label class="col-lg-1">
                    <input type="text" name="id" id="id" class="form-control" value="{{$acreedor->id}}" disabled>
                </label>

                <label for="rut" class="col-lg-1 control-label">Rut</label>
                <label class="col-lg-2">
                    <input type="text"  name="rut" id="rut" class="form-control" value="{{$acreedor->rut}}">
                </label>
                
                <label for="name" class="col-lg-1 control-label">Nombre Acreedor</label>
                <label class="col-lg-5">
                    <input type="text" name="name" id="name" class="form-control" value="{{$acreedor->nombre}}">
                </label>
            </div>
            
        </div>
        <div class="panel-footer">
            <button id="btn_cancelar" type="button" class="btn btn-sm btn-default" onclick="location.href='{{route('acreedor.index')}}'"> Cancelar </button>
            <button type="submit" class="btn btn-sm btn-system"> Grabar </button>
        </div>
    </div>
</form>

@endsection

@section('scripts')
<script src="/js/jquery.rut.min.js"></script>

<script>
    $(document).ready(function() {

        var rut_format = $("#rut").val();
        rut_format = rut_format + $.computeDv(rut_format);

        $("#rut").val($.formatRut(rut_format)); 



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