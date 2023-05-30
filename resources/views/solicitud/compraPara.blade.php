
<form enctype="multipart/form-data" id="form_compraPara" class="form-documentos form-horizontal form-solicitud" method="POST">
    @csrf
    
    <div class="panel panel-info panel-border top">
        <div class="panel-heading">
            <span class="panel-title">Ingreso de Solicitud N° {{$id}} - Datos de CompraPara</span>
        </div>
        <div class="panel-body">
            
                <div class="form-group">
                    <label for="rut" class="col-lg-2 control-label">Rut :</label>
                    <label class="col-lg-2">
                        <input type="hidden" data-comprapara="{{$comprapara}}" name="id_comprapara" id="id_comprapara" value="{{ !is_null($comprapara)? $comprapara->id :  0}}">
                        <input type="text" name="rut" id="rut_comprapara" class="form-control rut2" placeholder="99.999.999-9" value="{{ !is_null($comprapara)?  $comprapara->rut : ''}}" >
                    </label>
                    <label class="col-lg-2"></label>
                    
                    <label for="nombre" class="col-lg-2 control-label">Nombre :</label>
                    <label class="col-lg-4">
                        <input type="text" name="nombre" id="nombre_comprapara" class="form-control" placeholder="Nombre del Adquiriente" value="{{!is_null($comprapara)?  $comprapara->nombre : ''}}" >
                    </label>
                </div>
                <div class="form-group">
                    <label for="aPaterno" class="col-lg-2 control-label">Apellido Paterno :</label>
                    <label class="col-lg-4">
                        <input type="text" name="aPaterno" id="aPaterno_comprapara" class="form-control" placeholder="Apellido Paterno" value="{{!is_null($comprapara)?  $comprapara->aPaterno : ''}}">
                    </label>
                    
                    <label for="aMaterno" class="col-lg-2 control-label">Apellido Materno :</label>
                    <label class="col-lg-4">
                        <input type="text" name="aMaterno" id="aMaterno_comprapara" class="form-control" placeholder="Apellido Materno" value="{{!is_null($comprapara)?  $comprapara->aMaterno :''}}">
                    </label>
                </div>
                <div class="form-group">
                    <label for="calle" class="col-lg-2 control-label">Dirección (calle) :</label>
                    <label class="col-lg-4">
                        <input type="text" name="calle" id="calle_comprapara" class="form-control" placeholder="Calle de la dirección" value="{{!is_null($comprapara)?  $comprapara->calle : ''}}" >
                    </label>
                    
                    <label for="numero" class="col-lg-2 control-label">Número :</label>
                    <label class="col-lg-2">
                        <input type="text" name="numero" id="numero_comprapara" class="form-control" placeholder="Número de la dirección" value="{{!is_null($comprapara)?  $comprapara->numero :''}}" >
                    </label>
                    <label class="col-lg-2"></label>
                </div>
                <div class="form-group">
                    <label for="rDireccion" class="col-lg-2 control-label">Complemento de dirección :</label>
                    <label class="col-lg-4">
                        <input type="text" name="rDireccion" id="rDireccion_comprapara" class="form-control" placeholder="Complemento de la dirección" value="{{!is_null($comprapara)?  $comprapara->rDomicilio : ''}}">
                    </label>
                    
                    <label for="comuna" class="col-lg-2 control-label">Comuna :</label>
                    <label class="col-lg-4">
                        <select class="col-sm-12 form-select comuna" name="comuna" id="comuna_comprapara" >
                            <option value="0" selected>Seleccione Comuna...</option>
                            @foreach ($comunas as $item)
                                <option value="{{$item->Codigo}}" 
                                    @if(is_null($comprapara))
                                        @if ($adquirentes[0]->comuna==$item->id) 
                                            selected 
                                        @endif
                                    @else
                                        @if ($comprapara->comuna==$item->id) 
                                            selected 
                                        @endif

                                    @endif
                                    
                                    >{{$item->Nombre}}</option>    
                            @endforeach
                        </select>
                    </label>
                </div>
                <div class="form-group">
                    <label for="email" class="col-lg-2 control-label">Email :</label>
                    <label class="col-lg-4">
                        <input type="email" name="email" id="email_comprapara" class="form-control" placeholder="Email" value="{{!is_null($comprapara)?  $comprapara->email :''}}" >
                    </label>
                    
                    <label for="telefono" class="col-lg-2 control-label">Teléfono :</label>
                    <label class="col-lg-2">
                        <input type="text" name="telefono" id="telefono_comprapara" class="form-control" placeholder="Ej. 978653214" value="{{!is_null($comprapara)?  $comprapara->telefono :''}}" >
                    </label>
                    <label class="col-lg-2"></label>
                </div>
                <div class="form-group">
                    <label for="tipoPersona" class="col-lg-2 control-label">Tipo de Persona :</label>
                    <label class="col-lg-4">
                        <select class="col-sm-12 form-select" name="tipoPersona" id="tipoPersona2" >
                        @if(is_null($comprapara))    
                            <option value="N" @if ($adquirentes[0]->tipo=='N') selected @endif>NATURAL</option>
                            <option value="J" @if ($adquirentes[0]->tipo=='J') selected @endif>JURÍDICO</option>
                            <option value="E" @if ($adquirentes[0]->tipo=='E') selected @endif>EXTRANJERO</option>
                            <option value="O" @if ($adquirentes[0]->tipo=='O') selected @endif>COMUNIDAD</option>
                        @else
                            <option data-poto value="N" @if ($comprapara->tipo=='N') selected @endif>NATURAL</option>
                            <option data-poto value="J" @if ($comprapara->tipo=='J') selected @endif>JURÍDICO</option>
                            <option data-poto value="E" @if ($comprapara->tipo=='E') selected @endif>EXTRANJERO</option>
                            <option data-poto value="O" @if ($comprapara->tipo=='O') selected @endif>COMUNIDAD</option>
                        @endif
                        </select>
                    </label>
                </div>
                <hr>
        </div>
    <div class="panel-footer">
        <button type="submit" class="btn btn-system btnGuardaCompraPara"> <li class="fa fa-save"></li> Grabar y Continuar</button>
        <button type="button" class="btn btn-warning btnContinuaSolicitud"><li class="fa fa-arrow-right"></li> No registrar Compra Para y Continuar </button>
    </div>
</div>
</form>


<script>
    $(document).ready(function() {
        $("#rut_comprapara").val($.formatRut($("#rut_comprapara").val())); 

        $('.comuna').multiselect({
            enableFiltering: true,
        });
        $('#tipoPersona2').multiselect();

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


    $(document).on("click",".btnContinuaSolicitud",function(e){
        e.preventDefault();
        showOverlay();
        let formData = new FormData(document.getElementById("form_compraPara"));
        formData.append('guardar',"NO");
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "/solicitud/{{$id}}/saveCompraPara",
            data: formData,
            processData: false,
            contentType: false,
            type: "POST",
            success: function(data){
                hideOverlay();
                $("#pills-invoice").html(data);
                $("#pills-invoice").toggleClass('show');
                $("#pills-home").removeClass('show');
                $("#pills-contact").removeClass('show');
                $("#pills-profile").removeClass('show');
                $("#pills-invoice-tab").attr("href","#pills-invoice");
                $("#pills-invoice-tab").toggleClass('disabled');
                $("#pills-invoice-tab").attr("aria-disabled",false);
                $("#pills-invoice-tab").click();
                
            }
        });



    })

    $(document).on("submit","#form_compraPara",function(e){
        e.preventDefault();
        showOverlay();
        let formData = new FormData(document.getElementById("form_compraPara"));
        formData.append('guardar',"SI");

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "/solicitud/{{$id}}/saveCompraPara",
            data: formData,
            processData: false,
            contentType: false,
            type: "POST",
            success: function(data){
                hideOverlay();
                if(parseFloat($("#id_comprapara").val()) == 0){
                    $("#pills-invoice").html(data);
                    $("#pills-invoice").toggleClass('show');
                    $("#pills-home").removeClass('show');
                    $("#pills-contact").removeClass('show');
                    $("#pills-profile").removeClass('show');
                    $("#pills-invoice-tab").attr("href","#pills-invoice");
                    $("#pills-invoice-tab").toggleClass('disabled');
                    $("#pills-invoice-tab").attr("aria-disabled",false);
                    $("#pills-invoice-tab").click();
                }
                else{
                    new PNotify({
                        title: 'Editar Compra Para',
                        text: 'Datos de Compra Para editados correctamente',
                        shadow: true,
                        opacity: '0.75',
                        addclass: 'stack_top_right',
                        type: 'success',
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
                }

            }
        });

    });

</script>
