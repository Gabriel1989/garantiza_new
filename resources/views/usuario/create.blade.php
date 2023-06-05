@extends("themes.$themes.layout")

@section('contenido')

@include('includes.form-error-message')
<form method="post" action="{{route('usuario.store')}}" role="form" class="form-horizontal">
    @csrf
    @method('POST')
    <div class="panel panel-info panel-border top">
        <div class="panel-heading">
            <span class="panel-title">Agregar Usuario</span>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label for="name" class="col-lg-1 control-label">Nombre</label>
                <label class="col-lg-5">
                    <input type="text" name="name" id="name" class="form-control" placeholder="Nombre de Usuario" value="{{old('name')}}">
                </label>
                <label for="rol_id" class="col-lg-1 control-label">Rol</label>
                <label class="col-lg-5">
                    <select class="col-sm-12 form-select" name="rol_id" id="rol_id" >
                        <option selected>Seleccione Rol...</option>
                        @foreach ($roles as $item)
                        @if ($item->id!=1)
                        <option value="{{$item->id}}">{{$item->name}}</option>    
                        @endif
                        @endforeach
                    </select>
                </label>
            </div>
            <div class="form-group">
                <label for="email" class="col-lg-1 control-label">Email</label>
                <label class="col-lg-5">
                    <input type="email" name="email" id="email" class="form-control" placeholder="Email de Usuario" value="{{old('email')}}">
                </label>
                <label for="password" class="col-lg-1 control-label">Password</label>
                <label class="col-lg-2">
                    <input type="password" name="password" id="password" class="form-control">
                </label>
                
            </div>
            <div id="concesionaria" class="form-group">
                <label for="concesionaria_id" class="col-lg-1 control-label">Concesionaria</label>
                <label class="col-lg-5">
                    <select class="col-sm-12 form-select" name="concesionaria_id" id="concesionaria_id" >
                        <option value="0" selected>Seleccione Concesionaria...</option>
                        @foreach ($concesionarias as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>    
                        @endforeach
                    </select>
                </label>
            </div>

            <div id="notaria" class="form-group">
                <label for="notaria_id" class="col-lg-1 control-label">Notaria</label>
                <label class="col-lg-5">
                    <select class="col-sm-12 form-select" name="notaria_id" id="notaria_id" >
                        <option value="0" selected>Seleccione Notaria...</option>
                        @foreach ($notarias as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>    
                        @endforeach
                    </select>
                </label>
            </div>
        </div>
        </div>
        <div class="panel-footer">
            <button id="btn_cancelar" type="button" class="btn btn-sm btn-default" onclick="location.href='{{route('usuario.index')}}'"> Cancelar </button>
            <button type="submit" class="btn btn-sm btn-system"> Grabar </button>
        </div>
    </div>
</form>

@endsection

@section('scripts')
<script type="text/javascript">
    jQuery(document).ready(function() {
        $('#rol_id').multiselect();
        $('#concesionaria_id').multiselect();
        $('#notaria_id').multiselect();
        $('#concesionaria').hide();
        $('#notaria').hide();

        $('#rol_id').change(function () {  
            var id = $('#rol_id').find(':selected')[0].value;
            if(id<4){
                //Usuarios de Garantiza NO necesitan tener notaria y/o concesionaria
                $('#concesionaria').hide();
                $('#notaria').hide();
            }
            else if(id == 7){
                //Usuarios de Notaria tienen notaria
                $('#notaria').show();
                $('#concesionaria').hide();
            }
            else{
                //Usuarios de concesionaria tienen concesionaria
                $('#concesionaria').show();
                $('#notaria').hide();
            }
        });
    });
</script>
@endsection