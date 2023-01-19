
<form enctype="multipart/form-data" class="form-documentos form-horizontal form-solicitud" action="{{route('solicitud.saveCompraPara', ['id' => $id])}}" method="POST">
    @csrf
    @method('POST')
    <div class="panel panel-info panel-border top">
        <div class="panel-heading">
            <span class="panel-title">Ingreso de Solicitud N° {{$id}} - Datos de CompraPara</span>
        </div>
        <div class="panel-body">
            <div id="adquiriente">
                <div class="form-group">
                    <label for="rut" class="col-lg-2 control-label">Rut :</label>
                    <label class="col-lg-2">
                        <input type="text" name="rut" id="rut2" class="form-control rut2" placeholder="99.999.999-9" value="{{$adquirentes[0]->rut}}" >
                    </label>
                    <label class="col-lg-2"></label>
                    
                    <label for="nombre" class="col-lg-2 control-label">Nombre :</label>
                    <label class="col-lg-4">
                        <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre del Adquiriente" value="{{$adquirentes[0]->nombre}}" >
                    </label>
                </div>
                <div class="form-group">
                    <label for="aPaterno" class="col-lg-2 control-label">Apellido Paterno :</label>
                    <label class="col-lg-4">
                        <input type="text" name="aPaterno" id="aPaterno" class="form-control" placeholder="Apellido Paterno" value="{{$adquirentes[0]->aPaterno}}">
                    </label>
                    
                    <label for="aMaterno" class="col-lg-2 control-label">Apellido Materno :</label>
                    <label class="col-lg-4">
                        <input type="text" name="aMaterno" id="aMaterno" class="form-control" placeholder="Apellido Materno" value="{{$adquirentes[0]->aMaterno}}">
                    </label>
                </div>
                <div class="form-group">
                    <label for="calle" class="col-lg-2 control-label">Dirección (calle) :</label>
                    <label class="col-lg-4">
                        <input type="text" name="calle" id="calle" class="form-control" placeholder="Calle de la dirección" value="{{$adquirentes[0]->calle}}" >
                    </label>
                    
                    <label for="numero" class="col-lg-2 control-label">Número :</label>
                    <label class="col-lg-2">
                        <input type="text" name="numero" id="numero" class="form-control" placeholder="Número de la dirección" value="{{$adquirentes[0]->numero}}" >
                    </label>
                    <label class="col-lg-2"></label>
                </div>
                <div class="form-group">
                    <label for="rDireccion" class="col-lg-2 control-label">Complemento de dirección :</label>
                    <label class="col-lg-4">
                        <input type="text" name="rDireccion" id="rDireccion" class="form-control" placeholder="Complemento de la dirección" value="{{$adquirentes[0]->rDomicilio}}">
                    </label>
                    
                    <label for="comuna" class="col-lg-2 control-label">Comuna :</label>
                    <label class="col-lg-4">
                        <select class="col-sm-12 form-select comuna" name="comuna" id="comuna" >
                            <option value="0" selected>Seleccione Comuna...</option>
                            @foreach ($comunas as $item)
                                <option value="{{$item->Codigo}}" @if ($adquirentes[0]->comuna==$item->id) selected @endif>{{$item->Nombre}}</option>    
                            @endforeach
                        </select>
                    </label>
                </div>
                <div class="form-group">
                    <label for="email" class="col-lg-2 control-label">Email :</label>
                    <label class="col-lg-4">
                        <input type="email" name="email" id="email" class="form-control" placeholder="Email" value="{{$adquirentes[0]->email}}" >
                    </label>
                    
                    <label for="telefono" class="col-lg-2 control-label">Teléfono :</label>
                    <label class="col-lg-2">
                        <input type="text" name="telefono" id="telefono" class="form-control" placeholder="Ej. 978653214" value="{{$adquirentes[0]->telefono}}" >
                    </label>
                    <label class="col-lg-2"></label>
                </div>
                <div class="form-group">
                    <label for="tipoPersona" class="col-lg-2 control-label">Tipo de Persona :</label>
                    <label class="col-lg-4">
                        <select class="col-sm-12 form-select" name="tipoPersona" id="tipoPersona2" >
                            <option value="N" @if ($adquirentes[0]->tipo=='N') selected @endif>NATURAL</option>
                            <option value="J" @if ($adquirentes[0]->tipo=='J') selected @endif>JURÍDICO</option>
                            <option value="E" @if ($adquirentes[0]->tipo=='E') selected @endif>EXTRANJERO</option>
                            <option value="O" @if ($adquirentes[0]->tipo=='O') selected @endif>COMUNIDAD</option>
                        </select>
                    </label>
                </div>
                <hr>
            </div>
        </div>
    <div class="panel-footer">
        <button type="submit" class="btn btn-system"> <li class="fa fa-save"></li> Grabar y Continuar</button>
    </div>
</div>
</form>

@section('scripts')
<script src="/js/jquery.rut.min.js"></script>
<script>
    $(document).ready(function() {
        $("#rut2").val($.formatRut($("#rut").val())); 

        $('.comuna').multiselect({
            enableFiltering: true,
        });
        $('#tipoPersona').multiselect();

        $(".rut2").rut({
            formatOn: 'keyup',
            minimumLength: 8, 
            validateOn: 'change' 
        });

        $(".rut2").rut().on('rutInvalido', function(e) {
            new PNotify({
                title: 'Rut de Compra Para',
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

        $("#tipoPersona2").on('change', function(){
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

    });

</script>
@endsection