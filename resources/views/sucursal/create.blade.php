@extends("themes.$themes.layout")

@section('contenido')

@include('includes.form-error-message')
<form method="post" action="{{route('sucursal.store')}}" role="form" class="form-horizontal">
    @csrf
    @method('POST')
    <div class="panel panel-info panel-border top">
        <div class="panel-heading">
            <span class="panel-title">Agregar Sucursal</span>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label for="name" class="col-lg-1 control-label">Sucursal</label>
                <label class="col-lg-5">
                    <input type="text" name="name" id="name" class="form-control" placeholder="Nombre de Sucursal" value="{{old('name')}}">
                </label>
                <label for="concesionaria_id" class="col-lg-1 control-label">Concesionaria</label>
                <label class="col-lg-5">
                    <select class="col-sm-12 form-select" name="concesionaria_id" id="concesionaria_id" >
                        <option selected>Seleccione Concesionaria...</option>
                        @foreach ($concesionarias as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>    
                        @endforeach
                    </select>
                </label>
            </div>
            <div class="form-group">
                <label for="calle" class="col-lg-1 control-label">Dirección</label>
                <label class="col-lg-5">
                    <input type="text" name="calle" id="calle" class="form-control" placeholder="Calle" value="{{old('calle')}}">
                </label>
                <label for="numero" class="col-lg-1 control-label">Número</label>
                <label class="col-lg-1">
                    <input type="text" name="numero" id="numero" class="form-control" placeholder="Número" value="{{old('numero')}}">
                </label>
            </div>
            <div class="form-group">
                <label for="comuna" class="col-lg-1 control-label">Comuna</label>
                <label class="col-lg-5">
                    <select class="col-sm-12 form-select" name="comuna" id="comuna" >
                        <option selected>Seleccione Comuna...</option>
                        @foreach ($comunas as $item)
                            <option value="{{$item->Codigo}}">{{$item->Nombre}}</option>    
                        @endforeach
                    </select>
                </label>

                <label for="region" class="col-lg-1 control-label">Región</label>
                <label class="col-lg-5">
                    <!--<input type="text" name="region" id="region" class="form-control" placeholder="Región" value="{{old('region')}}">-->
                    <select class="col-sm-12 form-select" name="region" id="region" >
                        <option selected>Seleccione Región...</option>
                        @foreach ($regiones as $item)
                            <option value="{{$item->id}}">{{$item->nombre}}</option>    
                        @endforeach
                    </select>
                </label>
            </div>
        </div>
        <div class="panel-footer">
            <button id="btn_cancelar" type="button" class="btn btn-sm btn-default" onclick="location.href='{{route('sucursal.index')}}'"> Cancelar </button>
            <button type="submit" class="btn btn-sm btn-system"> Grabar </button>
        </div>
    </div>
</form>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#concesionaria_id').multiselect();
        $('#comuna').multiselect({
            enableCaseInsensitiveFiltering: true,
            enableFiltering: true
        });
        $('#region').multiselect({
            enableCaseInsensitiveFiltering: true,
            enableFiltering: true
        });
    });
</script>
@endsection