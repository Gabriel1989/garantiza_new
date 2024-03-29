@extends("themes.$themes.layout")

@section('contenido')

@include('includes.form-error-message')
<form method="post" action="{{route('concesionaria.store')}}" role="form" class="form-horizontal">
    @csrf
    @method('POST')
    <div class="panel panel-info panel-border top">
        <div class="panel-heading">
            <span class="panel-title">Agregar Concesionarias</span>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label for="rut" class="col-lg-1 control-label">Rut</label>
                <label class="col-lg-1">
                    <input type="text" name="rut" id="rut" class="form-control" placeholder="99.999.999-9" value="{{old('rut')}}">
                </label>
                
                <label for="name" class="col-lg-1 control-label">Concesionaria</label>
                <label class="col-lg-5">
                    <input type="text" name="name" id="name" class="form-control" placeholder="Nombre de Concesionaria" value="{{old('name')}}">
                </label>

                <label for="razon_social" class="col-lg-1 control-label">Razón social</label>
                <label class="col-lg-5">
                    <input type="text" name="razon_social" id="razon_social" class="form-control" placeholder="Razón social de Concesionaria" value="{{old('razon_social')}}">
                </label>
            </div>
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
            new PNotify({
                title: 'Rut de Concesionaria',
                text: 'El Rut ingresado no es válido.',
                shadow: true,
                opacity: '0.75',
                addclass: 'stack_top_right',
                type: 'danger',
                stack: {
                    "dir1": "down",
                    "dir2": "left",
                    "push": "top",
                    "spacing1": 10,
                    "spacing2": 10
                },
                width: '290px',
                delay: 2000
            });
        });
    });
</script>
@endsection