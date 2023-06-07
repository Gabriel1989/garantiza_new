
<form enctype="multipart/form-data" id="form_estipulante" class="form-documentos form-horizontal form-solicitud" method="POST">
    @csrf
    
    <div class="panel panel-info panel-border top">
        <div class="panel-heading">
            <span class="panel-title">Ingreso de Solicitud N° {{$id}} - Datos de Estipulante</span>
        </div>
        <div class="panel-body">
            
                <div class="form-group">
                    <label for="rut" class="col-lg-2 control-label">Rut :</label>
                    <label class="col-lg-2">
                        <input type="hidden" data-estipulante="{{$estipulante}}" name="id_estipulante" id="id_estipulante" value="{{ !is_null($estipulante)? $estipulante->id :  0}}">
                        <input type="text" name="rut" id="rut_estipulante" class="form-control rut3" placeholder="99.999.999-9" value="{{ !is_null($estipulante)?  $estipulante->rut : ''}}" >
                    </label>
                    <label class="col-lg-2"></label>
                    
                    <label for="nombre" class="col-lg-2 control-label">Nombre :</label>
                    <label class="col-lg-4">
                        <input type="text" name="nombre" id="nombre_estipulante" class="form-control" placeholder="Nombre del Estipulante" value="{{!is_null($estipulante)?  $estipulante->nombre : ''}}" >
                    </label>
                </div>
                <div class="form-group">
                    <label for="aPaterno" class="col-lg-2 control-label">Apellido Paterno :</label>
                    <label class="col-lg-4">
                        <input type="text" name="aPaterno" id="aPaterno_estipulante" class="form-control" placeholder="Apellido Paterno" value="{{!is_null($estipulante)?  $estipulante->aPaterno : ''}}">
                    </label>
                    
                    <label for="aMaterno" class="col-lg-2 control-label">Apellido Materno :</label>
                    <label class="col-lg-4">
                        <input type="text" name="aMaterno" id="aMaterno_estipulante" class="form-control" placeholder="Apellido Materno" value="{{!is_null($estipulante)?  $estipulante->aMaterno :''}}">
                    </label>
                </div>
                <div class="form-group">
                    <label for="calle" class="col-lg-2 control-label">Dirección (calle) :</label>
                    <label class="col-lg-4">
                        <input type="text" name="calle" id="calle_estipulante" class="form-control" placeholder="Calle de la dirección" value="{{!is_null($estipulante)?  $estipulante->calle : ''}}" >
                    </label>
                    
                    <label for="numero" class="col-lg-2 control-label">Número :</label>
                    <label class="col-lg-2">
                        <input type="text" name="numero" id="numero_estipulante" class="form-control" placeholder="Número de la dirección" value="{{!is_null($estipulante)?  $estipulante->numero :''}}" >
                    </label>
                    <label class="col-lg-2"></label>
                </div>
                <div class="form-group">
                    <label for="rDireccion" class="col-lg-2 control-label">Complemento de dirección :</label>
                    <label class="col-lg-4">
                        <input type="text" name="rDireccion" id="rDireccion_estipulante" class="form-control" placeholder="Complemento de la dirección" value="{{!is_null($estipulante)?  $estipulante->rDomicilio : ''}}">
                    </label>
                    
                    <label for="comuna" class="col-lg-2 control-label">Comuna :</label>
                    <label class="col-lg-4">
                        <select class="col-sm-12 form-select comuna" name="comuna" id="comuna_estipulante" >
                            <option value="0" selected>Seleccione Comuna...</option>
                            @foreach ($comunas as $item)
                                <option value="{{$item->Codigo}}" 
                                    @if(is_null($estipulante))
                                        @if ($compradores[0]->comuna==$item->id) 
                                            selected 
                                        @endif
                                    @else
                                        @if ($estipulante->comuna==$item->id) 
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
                        <input type="email" name="email" id="email_estipulante" class="form-control" placeholder="Email" value="{{!is_null($estipulante)?  $estipulante->email :''}}" >
                    </label>
                    
                    <label for="telefono" class="col-lg-2 control-label">Teléfono :</label>
                    <label class="col-lg-2">
                        <input type="text" name="telefono" id="telefono_estipulante" class="form-control" placeholder="Ej. 978653214" value="{{!is_null($estipulante)?  $estipulante->telefono :''}}" >
                    </label>
                    <label class="col-lg-2"></label>
                </div>
                <div class="form-group">
                    <label for="tipoPersona" class="col-lg-2 control-label">Tipo de Persona :</label>
                    <label class="col-lg-4">
                        <select class="col-sm-12 form-select" name="tipoPersona" id="tipoPersona_estipulante" >
                        @if(is_null($estipulante))    
                            <option value="N" @if ($compradores[0]->tipo=='N') selected @endif>NATURAL</option>
                            <option value="J" @if ($compradores[0]->tipo=='J') selected @endif>JURÍDICO</option>
                            <option value="E" @if ($compradores[0]->tipo=='E') selected @endif>EXTRANJERO</option>
                            
                        @else
                            <option data-poto value="N" @if ($estipulante->tipo=='N') selected @endif>NATURAL</option>
                            <option data-poto value="J" @if ($estipulante->tipo=='J') selected @endif>JURÍDICO</option>
                            <option data-poto value="E" @if ($estipulante->tipo=='E') selected @endif>EXTRANJERO</option>
                            
                        @endif
                        </select>
                    </label>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">Compra con crédito:</label>
                    <label class="col-lg-4">
                        <div class="switch switch-success switch-inline">
                            <input type="checkbox" id="esProhibicion" name="esProhibicion" value="0">
                            <label for="esProhibicion"></label>
                        </div>
                    </label>
                </div>
                <hr>
        </div>
    <div class="panel-footer">
        <button type="submit" class="btn btn-system btnGuardaEstipulante"> <li class="fa fa-save"></li> Grabar y Continuar</button>
        <button type="button" class="btn btn-warning btnContinuaSolicitud"><li class="fa fa-arrow-right"></li> No registrar Estipulante y Continuar </button>
    </div>
</div>
</form>


<script>
    $(document).ready(function() {
        var rut_format = $("#rut2").val();
        if(rut_format.trim() != ''){
            rut_format = rut_format + $.computeDv(rut_format);
            $("#rut2").val($.formatRut(rut_format)); 
        }

        $("#rut_estipulante").val($.formatRut($("#rut_estipulante").val())); 

        $('.comuna').multiselect({
            enableFiltering: true,
        });
        $('#tipoPersona_estipulante').multiselect();

        $(".rut3").rut({
            formatOn: 'keyup',
            minimumLength: 8, 
            validateOn: 'change' 
        });

        $(".rut3").rut().on('rutInvalido', function(e) {
            new PNotify({
                title: 'Rut de Estipulante',
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


    $(document).on("click",".btnContinuaSolicitud",function(e){
        e.preventDefault();
        showOverlay();
        let formData = new FormData(document.getElementById("form_estipulante"));
        formData.append('guardar',"NO");
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "/transferencia/{{$id}}/saveEstipulante",
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

    $(document).on("submit","#form_estipulante",function(e){
        e.preventDefault();
        showOverlay();
        let formData = new FormData(document.getElementById("form_estipulante"));
        formData.append('guardar',"SI");

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "/transferencia/{{$id}}/saveEstipulante",
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
                    if(parseFloat($("#id_estipulante").val()) == 0){
                        $("#pills-invoice").html(data);
                        $("#pills-invoice").toggleClass('show');
                        $("#pills-home").removeClass('show');
                        $("#pills-contact").removeClass('show');
                        $("#pills-profile").removeClass('show');
                        $("#pills-invoice-tab").attr("href","#pills-invoice");
                        $("#pills-invoice-tab").toggleClass('disabled');
                        $("#pills-invoice-tab").attr("aria-disabled",false);
                        $("#pills-invoice-tab").click();
                        $("#id_estipulante").val(json.id_estipulante);
                    }
                    else{
                        new PNotify({
                            title: 'Editar Estipulante',
                            text: 'Datos de Estipulante editados correctamente',
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
