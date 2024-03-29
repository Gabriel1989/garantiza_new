
<form enctype="multipart/form-data" id="form_compraPara" class="form-documentos form-horizontal form-solicitud" method="POST">
    @csrf
    
    <div class="panel panel-info panel-border top">
        <div class="panel-heading">
            <span class="panel-title">Ingreso de Solicitud N° {{$id}} - Datos de CompraPara (opcional en caso de ingresar una inscripción con Compra Para)</span>
            <br>
            <span class="panel-title" style="color:#f00">(*) Datos obligatorios</span>
        </div>
        <div class="panel-body">
            
                <div class="form-group">
                    <label for="rut" class="col-lg-2 control-label"><span class="panel-title" style="color:#f00">(*) </span>Rut :</label>
                    <label class="col-lg-2">
                        <input type="hidden" data-comprapara="{{$comprapara}}" name="id_comprapara" id="id_comprapara" value="{{ !is_null($comprapara)? $comprapara->id :  0}}">
                        <input type="text" name="rut" id="rut_comprapara" class="form-control rut2" placeholder="99.999.999-9" value="{{ !is_null($comprapara)?  $comprapara->rut : ''}}" >
                    </label>
                    <label class="col-lg-2"></label>
                    
                    <label for="nombre" class="col-lg-2 control-label"><span class="panel-title" style="color:#f00">(*) </span>Nombre :</label>
                    <label class="col-lg-4">
                        <input type="text" name="nombre" id="nombre_comprapara" class="form-control" placeholder="Nombre del Compra Para" value="{{!is_null($comprapara)?  $comprapara->nombre : ''}}" >
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
                    <label for="calle" class="col-lg-2 control-label"><span class="panel-title" style="color:#f00">(*) </span>Dirección (calle) :</label>
                    <label class="col-lg-4">
                        <input type="text" name="calle" id="calle_comprapara" class="form-control" placeholder="Calle de la dirección" value="{{!is_null($comprapara)?  $comprapara->calle : ''}}" >
                    </label>
                    
                    <label for="numero" class="col-lg-2 control-label"><span class="panel-title" style="color:#f00">(*) </span>Número :</label>
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
                    
                    <label for="comuna" class="col-lg-2 control-label"><span class="panel-title" style="color:#f00">(*) </span>Comuna :</label>
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
                    <label for="email" class="col-lg-2 control-label"><span class="panel-title" style="color:#f00">(*) </span>Email :</label>
                    <label class="col-lg-4">
                        <input type="email" name="email" id="email_comprapara" class="form-control" placeholder="Email" value="{{!is_null($comprapara)?  $comprapara->email :''}}" >
                    </label>
                    
                    <label for="telefono" class="col-lg-2 control-label"><span class="panel-title" style="color:#f00">(*) </span>Teléfono :</label>
                    <label class="col-lg-2">
                        <input type="text" name="telefono" id="telefono_comprapara" class="form-control" placeholder="Ej. 978653214" value="{{!is_null($comprapara)?  $comprapara->telefono :''}}" >
                    </label>
                    <label class="col-lg-2"></label>
                </div>
                <div class="form-group">
                    <label for="tipoPersona" class="col-lg-2 control-label"><span class="panel-title" style="color:#f00">(*) </span>Tipo de Persona :</label>
                    <label class="col-lg-4">
                        <select class="col-sm-12 form-select" name="tipoPersona" id="tipoPersona2" >
                        @if(is_null($comprapara))    
                            <option value="N" @if ($adquirentes[0]->tipo=='N') selected @endif>NATURAL</option>
                            <option value="J" @if ($adquirentes[0]->tipo=='J') selected @endif>JURÍDICO</option>
                            <option value="E" @if ($adquirentes[0]->tipo=='E') selected @endif>EXTRANJERO</option>
                        @else
                            <option data-poto value="N" @if ($comprapara->tipo=='N') selected @endif>NATURAL</option>
                            <option data-poto value="J" @if ($comprapara->tipo=='J') selected @endif>JURÍDICO</option>
                            <option data-poto value="E" @if ($comprapara->tipo=='E') selected @endif>EXTRANJERO</option>
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
            enableCaseInsensitiveFiltering: true
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
            url: "{{ (Auth::user()->rol_id == 1 || Auth::user()->rol_id == 3) ? '/solicitud/'.$id.'/saveCompraPara': '/solicitud/'.$id.'/saveCompraParaConces'}}",
            data: formData,
            processData: false,
            contentType: false,
            type: "POST",
            error: function(jqXHR, textStatus, errorThrown) {
                hideOverlay();
                if (jqXHR.status == 400) { // Verificar el código de estado
                    var errors = JSON.parse(jqXHR.responseText).errors;
                    for (var errorKey in errors) {
                        if (errors.hasOwnProperty(errorKey)) {
                            var messages = errors[errorKey];
                            for (var i = 0; i < messages.length; i++) {
                                new PNotify({
                                    title: 'Error',
                                    text: messages[i],
                                });
                            }
                        }
                    }
                } else {
                    // Acción cuando hay un error.
                    new PNotify({
                        title: 'Error',
                        text: "Error: " + textStatus + ' : ' + errorThrown,
                        shadow: true,
                        opacity: '1',
                        addclass: 'stack_top_right',
                        type: 'danger',
                        stack: {
                            "dir1": "down",
                            "dir2": "left",
                            "push": "top",
                            "spacing1": 10,
                            "spacing2": 10
                        },
                        width: '500px'
                    });
                }

            },
            success: function(data){
                hideOverlay();
                var jsonString = JSON.stringify(data);
                let json = JSON.parse(jsonString);
                if (json.status == "OK") {
                    $("#pills-invoice").html(json.html);
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
                        title: 'Error',
                        text: "Error: " + json.msj,
                        shadow: true,
                        opacity: '1',
                        addclass: 'stack_top_right',
                        type: 'danger',
                        stack: {
                            "dir1": "down",
                            "dir2": "left",
                            "push": "top",
                            "spacing1": 10,
                            "spacing2": 10
                        },
                        width: '500px'
                    });
                }
                
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
            url: "{{ (Auth::user()->rol_id == 1 || Auth::user()->rol_id == 3) ? '/solicitud/'.$id.'/saveCompraPara': '/solicitud/'.$id.'/saveCompraParaConces'}}",
            data: formData,
            processData: false,
            contentType: false,
            type: "POST",
            error: function(jqXHR, textStatus, errorThrown) {
                hideOverlay();
                if (jqXHR.status == 400) { // Verificar el código de estado
                    var errors = JSON.parse(jqXHR.responseText).errors;
                    for (var errorKey in errors) {
                        if (errors.hasOwnProperty(errorKey)) {
                            var messages = errors[errorKey];
                            for (var i = 0; i < messages.length; i++) {
                                new PNotify({
                                    title: 'Error',
                                    text: messages[i],
                                });
                            }
                        }
                    }
                } else {
                    // Acción cuando hay un error.
                    new PNotify({
                        title: 'Error',
                        text: "Error: " + textStatus + ' : ' + errorThrown,
                        shadow: true,
                        opacity: '1',
                        addclass: 'stack_top_right',
                        type: 'danger',
                        stack: {
                            "dir1": "down",
                            "dir2": "left",
                            "push": "top",
                            "spacing1": 10,
                            "spacing2": 10
                        },
                        width: '500px'
                    });
                }

            },
            success: function(data){
                hideOverlay();
                var jsonString = JSON.stringify(data);
                let json = JSON.parse(jsonString);
                if (json.status == "OK") {
                    if(parseFloat($("#id_comprapara").val()) == 0){
                        $("#pills-invoice").html(json.html);
                        $("#pills-invoice").toggleClass('show');
                        $("#pills-home").removeClass('show');
                        $("#pills-contact").removeClass('show');
                        $("#pills-profile").removeClass('show');
                        $("#pills-invoice-tab").attr("href","#pills-invoice");
                        $("#pills-invoice-tab").toggleClass('disabled');
                        $("#pills-invoice-tab").attr("aria-disabled",false);
                        $("#pills-invoice-tab").click();
                        $("#id_comprapara").val(json.id_para);
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
                else{
                    new PNotify({
                        title: 'Error',
                        text: "Error: " + json.msj,
                        shadow: true,
                        opacity: '1',
                        addclass: 'stack_top_right',
                        type: 'danger',
                        stack: {
                            "dir1": "down",
                            "dir2": "left",
                            "push": "top",
                            "spacing1": 10,
                            "spacing2": 10
                        },
                        width: '500px'
                    });
                }

            }
        });

    });

</script>
