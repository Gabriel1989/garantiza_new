@extends("themes.$themes.layout")

@section('styles')
@endsection

@section('contenido')
@include('includes.form-error-message')
@include('includes.mensaje')
<form enctype="multipart/form-data" class="form-documentos form-horizontal form-solicitud" action="{{route('solicitud.saveAdquirientes', ['id' => $id])}}" method="POST">
    @csrf
    @method('PUT')
    <div class="panel panel-info panel-border top">
        <div class="panel-heading">
            <span class="panel-title">Ingreso de Solicitud N° {{$id}} - Datos de Adquirentes</span>
        </div>
        <div class="panel-body">
            <div id="adquiriente">
                <div class="form-group">
                    <label for="rut" class="col-lg-2 control-label">Rut :</label>
                    <label class="col-lg-2">
                        <input type="text" name="rut" id="rut" class="form-control rut" placeholder="99.999.999-9" value="{{$header->RUTRecep}}" required>
                    </label>
                    <label class="col-lg-2"></label>
                    
                    <label for="nombre" class="col-lg-2 control-label">Nombre :</label>
                    <label class="col-lg-4">
                        <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre del Adquirente" value="{{$header->RznSocRecep}}" required>
                    </label>
                </div>
                <div class="form-group">
                    <label for="aPaterno" class="col-lg-2 control-label">Apellido Paterno :</label>
                    <label class="col-lg-4">
                        <input type="text" name="aPaterno" id="aPaterno" class="form-control" placeholder="Apellido Paterno" value="{{old('aPaterno')}}">
                    </label>
                    
                    <label for="aMaterno" class="col-lg-2 control-label">Apellido Materno :</label>
                    <label class="col-lg-4">
                        <input type="text" name="aMaterno" id="aMaterno" class="form-control" placeholder="Apellido Materno" value="{{old('aMaterno')}}">
                    </label>
                </div>
                <div class="form-group">
                    <label for="calle" class="col-lg-2 control-label">Dirección (calle) :</label>
                    <label class="col-lg-4">
                        <input type="text" name="calle" id="calle" class="form-control" placeholder="Calle de la dirección" value="{{$header->DirRecep}}" required>
                    </label>
                    
                    <label for="numero" class="col-lg-2 control-label">Número :</label>
                    <label class="col-lg-2">
                        <input type="text" name="numero" id="numero" class="form-control" placeholder="Número de la dirección" value="{{old('numero')}}" required>
                    </label>
                    <label class="col-lg-2"></label>
                </div>
                <div class="form-group">
                    <label for="rDireccion" class="col-lg-2 control-label">Complemento de dirección :</label>
                    <label class="col-lg-4">
                        <input type="text" name="rDireccion" id="rDireccion" class="form-control" placeholder="Complemento de la dirección" value="{{old('rDireccion')}}">
                    </label>
                    
                    <label for="comuna" class="col-lg-2 control-label">Comuna :</label>
                    <label class="col-lg-4">
                        <select class="col-sm-12 form-select comuna" name="comuna" id="comuna" >
                            <option value="0" selected>Seleccione Comuna...</option>
                            @foreach ($comunas as $item)
                                <option value="{{$item->Codigo}}" @if ($header->CmnaRecep==$item->Nombre) selected @endif>{{$item->Nombre}}</option>    
                            @endforeach
                        </select>
                    </label>
                </div>
                <div class="form-group">
                    <label for="email" class="col-lg-2 control-label">Email :</label>
                    <label class="col-lg-4">
                        <input type="email" name="email" id="email" class="form-control" placeholder="Email" value="" required>
                    </label>
                    
                    <label for="telefono" class="col-lg-2 control-label">Teléfono :</label>
                    <label class="col-lg-2">
                        <input type="text" name="telefono" id="telefono" class="form-control" placeholder="Ej. 978653214" value="{{$header->Contacto}}" required>
                    </label>
                    <label class="col-lg-2"></label>
                </div>
                <div class="form-group">
                    <label for="tipoPersona" class="col-lg-2 control-label">Tipo de Persona :</label>
                    <label class="col-lg-4">
                        <select class="col-sm-12 form-select" name="tipoPersona" id="tipoPersona" >
                            <option value="N" selected>NATURAL</option>
                            <option value="J">JURÍDICO</option>
                            <option value="E">EXTRANJERO</option>
                            <option value="O">COMUNIDAD</option>
                        </select>
                    </label>
                </div>
                <hr>
            </div>
            <div id="adquiriente2" style="display: none">
                <h5> <li class="fa fa-minus-square"  style="cursor: pointer" onclick="elimina(2)"></li> Segundo Adquirente</h5>
                <div class="form-group">
                    
                    <label for="rut2" class="col-lg-2 control-label">Rut :</label>
                    <label class="col-lg-2">
                        <input type="text" name="rut2" id="rut2" class="form-control rut" placeholder="99.999.999-9" value="{{old('rut2')}}">
                    </label>
                    <label class="col-lg-2"></label>
                    
                    <label for="nombre2" class="col-lg-2 control-label">Nombre :</label>
                    <label class="col-lg-4">
                        <input type="text" name="nombre2" id="nombre2" class="form-control" placeholder="Nombre del Adquirente" value="{{old('nombre2')}}">
                    </label>
                </div>
                <div class="form-group">
                    <label for="aPaterno2" class="col-lg-2 control-label">Apellido Paterno :</label>
                    <label class="col-lg-4">
                        <input type="text" name="aPaterno2" id="aPaterno2" class="form-control" placeholder="Apellido Paterno" value="{{old('aPaterno2')}}">
                    </label>
                    
                    <label for="aMaterno2" class="col-lg-2 control-label">Apellido Materno :</label>
                    <label class="col-lg-4">
                        <input type="text" name="aMaterno2" id="aMaterno2" class="form-control" placeholder="Apellido Materno" value="{{old('aMaterno2')}}">
                    </label>
                </div>
                <div class="form-group">
                    <label for="calle2" class="col-lg-2 control-label">Dirección (calle) :</label>
                    <label class="col-lg-4">
                        <input type="text" name="calle2" id="calle2" class="form-control" placeholder="Calle de la dirección" value="{{old('calle2')}}">
                    </label>
                    
                    <label for="numero2" class="col-lg-2 control-label">Número :</label>
                    <label class="col-lg-2">
                        <input type="text" name="numero2" id="numero2" class="form-control" placeholder="Número de la dirección" value="{{old('numero2')}}">
                    </label>
                    <label class="col-lg-2"></label>
                </div>
                <div class="form-group">
                    <label for="rDireccion2" class="col-lg-2 control-label">Complemento de dirección :</label>
                    <label class="col-lg-4">
                        <input type="text" name="rDireccion2" id="rDireccion2" class="form-control" placeholder="Complemento de la dirección" value="{{old('rDireccion2')}}">
                    </label>
                    
                    <label for="comuna2" class="col-lg-2 control-label">Comuna :</label>
                    <label class="col-lg-4">
                        <select class="col-sm-12 form-select comuna" name="comuna2" id="comuna2" >
                            <option value="0" selected>Seleccione Comuna...</option>
                            @foreach ($comunas as $item)
                                <option value="{{$item->Codigo}}">{{$item->Nombre}}</option>    
                            @endforeach
                        </select>
                    </label>
                </div>
                <div class="form-group">
                    <label for="email2" class="col-lg-2 control-label">Email :</label>
                    <label class="col-lg-4">
                        <input type="email" name="email2" id="email2" class="form-control" placeholder="Email" value="{{old('email2')}}">
                    </label>
                    
                    <label for="telefono2" class="col-lg-2 control-label">Teléfono :</label>
                    <label class="col-lg-2">
                        <input type="text" name="telefono2" id="telefono2" class="form-control" placeholder="Ej. 978653214" value="{{old('telefono2')}}">
                    </label>
                    <label class="col-lg-2"></label>
                </div>
                <hr>
            </div>
            <div id="adquiriente3" style="display: none">
                <h5> <li class="fa fa-minus-square" style="cursor: pointer" onclick="elimina(3)"></li> Tercer Adquirente</h5>
                <div class="form-group">
                    <label for="rut3" class="col-lg-2 control-label">Rut :</label>
                    <label class="col-lg-2">
                        <input type="text" name="rut3" id="rut3" class="form-control rut" placeholder="99.999.999-9" value="{{old('rut3')}}">
                    </label>
                    <label class="col-lg-2"></label>
                    
                    <label for="nombre3" class="col-lg-2 control-label">Nombre :</label>
                    <label class="col-lg-4">
                        <input type="text" name="nombre3" id="nombre3" class="form-control" placeholder="Nombre del Adquirente" value="{{old('nombre3')}}">
                    </label>
                </div>
                <div class="form-group">
                    <label for="aPaterno3" class="col-lg-2 control-label">Apellido Paterno :</label>
                    <label class="col-lg-4">
                        <input type="text" name="aPaterno3" id="aPaterno3" class="form-control" placeholder="Apellido Paterno" value="{{old('aPaterno3')}}">
                    </label>
                    
                    <label for="aMaterno3" class="col-lg-2 control-label">Apellido Materno :</label>
                    <label class="col-lg-4">
                        <input type="text" name="aMaterno3" id="aMaterno3" class="form-control" placeholder="Apellido Materno" value="{{old('aMaterno3')}}">
                    </label>
                </div>
                <div class="form-group">
                    <label for="calle3" class="col-lg-2 control-label">Dirección (calle) :</label>
                    <label class="col-lg-4">
                        <input type="text" name="calle3" id="calle3" class="form-control" placeholder="Calle de la dirección" value="{{old('calle3')}}">
                    </label>
                    
                    <label for="numero3" class="col-lg-2 control-label">Número :</label>
                    <label class="col-lg-2">
                        <input type="text" name="numero3" id="numero3" class="form-control" placeholder="Número de la dirección" value="{{old('numero3')}}">
                    </label>
                    <label class="col-lg-2"></label>
                </div>
                <div class="form-group">
                    <label for="rDireccion3" class="col-lg-2 control-label">Complemento de dirección :</label>
                    <label class="col-lg-4">
                        <input type="text" name="rDireccion3" id="rDireccion3" class="form-control" placeholder="Complemento de la dirección" value="{{old('rDireccion3')}}">
                    </label>
                    
                    <label for="comuna3" class="col-lg-2 control-label">Comuna :</label>
                    <label class="col-lg-4">
                        <select class="col-sm-12 form-select comuna" name="comuna3" id="comuna3" >
                            <option value="0" selected>Seleccione Comuna...</option>
                            @foreach ($comunas as $item)
                                <option value="{{$item->Codigo}}">{{$item->Nombre}}</option>    
                            @endforeach
                        </select>
                    </label>
                </div>
                <div class="form-group">
                    <label for="email3" class="col-lg-2 control-label">Email :</label>
                    <label class="col-lg-4">
                        <input type="email" name="email3" id="email3" class="form-control" placeholder="Email" value="{{old('email3')}}">
                    </label>
                    
                    <label for="telefono2" class="col-lg-2 control-label">Teléfono :</label>
                    <label class="col-lg-3">
                        <input type="text" name="telefono3" id="telefono3" class="form-control" placeholder="Ej. 978653214" value="{{old('telefono3')}}">
                    </label>
                    <label class="col-lg-2"></label>
                </div>
                <hr>
            </div>
            <button type="button" id="agregar" name="agregar" class="btn btn-success" style="display: none"> <li class="fa fa-plus"></li> Agregar Adquirente</button>
        </div>
    <div class="panel-footer">
        <button type="submit" class="btn btn-system"> <li class="fa fa-save"></li> Grabar y Continuar</button>
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

        $('.comuna').multiselect({
            enableFiltering: true,
        });
        $('#tipoPersona').multiselect();

        $(".rut").rut({
            formatOn: 'keyup',
            minimumLength: 8, 
            validateOn: 'change' 
        });

        $(".rut").rut().on('rutInvalido', function(e) {
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

        $("#tipoPersona").on('change', function(){
            if($(this).val()=='O'){
                $('#agregar').show();
            }else{
                $('#agregar').hide();
                $('#adquiriente2').hide();
                $('#adquiriente3').hide();
                $('#rut2').val('');
                $('#rut3').val('');
            };
        });

        $('#agregar').on('click', function(){
            if(!$('#adquiriente2').is(":visible")){
                $('#calle2').val($('#calle').val());
                $('#numero2').val($('#numero').val());
                $('#rDireccion2').val($('#rDireccion').val());
                var lastSelected = $('#comuna option:selected').val();
                //OJO - problema
                $("#comuna2").multiselect('select', lastSelected);
                $('#adquiriente2').show();
            }else{
                $('#calle3').val($('#calle').val());
                $('#numero3').val($('#numero').val());
                $('#rDireccion3').val($('#rDireccion').val());
                var lastSelected = $('#comuna option:selected').val();
                //OJO - problema
                $("#comuna3").multiselect('select', lastSelected);
                $('#adquiriente3').show();
                $('#agregar').hide();
            }
        });

        

    });

    function elimina(adquiriente){
        if(adquiriente==2){
            $('#adquiriente2').hide();
            $('#rut2').val('');
        }else{
            $('#adquiriente3').hide();
            $('#rut3').val('');
        }
    }
</script>
@endsection