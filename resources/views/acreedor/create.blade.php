@extends("themes.$themes.layout")

@section('contenido')

@include('includes.form-error-message')
<form method="post" action="{{route('acreedor.store')}}" role="form" class="form-horizontal">
    @csrf
    @method('POST')
    <div class="panel panel-info panel-border top">
        <div class="panel-heading">
            <span class="panel-title">Agregar Acreedor</span>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label for="rut" class="col-lg-1 control-label">RUT</label>
                <label class="col-lg-2">
                    <input type="text" name="rut" id="rut" class="form-control" placeholder="99.999.999-9" value="{{old('rut')}}">
                </label>
                
                <label for="name" class="col-lg-1 control-label">Acreedor</label>
                <label class="col-lg-5">
                    <input type="text" name="name" id="name" class="form-control" placeholder="Nombre de Acreedor" value="{{old('name')}}">
                </label>
            </div>
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
        $("input#rut").rut({
            formatOn: 'keyup',
            minimumLength: 8, 
            validateOn: 'change' 
        });

        $("input#rut").rut().on('rutInvalido', function(e) {
            new PNotify({
                title: 'Rut de Acreedor',
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