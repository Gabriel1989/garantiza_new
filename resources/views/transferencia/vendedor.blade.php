
<form enctype="multipart/form-data" class="form-horizontal" id="form-vendedor" method="POST">
    @csrf
    @method('POST')
    <div class="panel panel-info panel-border top">
        <div class="panel-heading">
            <span class="panel-title">Ingreso de Solicitud Transferencia N° {{ $id }} - Datos del
                Vendedor <br><span style="color:#f00">(*)</span> Datos obligatorios</span></span>
        </div>
        <div class="panel-body">
            <div id="vendedor">
                <div class="form-group">
                    <label for="rut" class="col-lg-2 control-label"><span style="color:#f00">(*)</span>Rut :</label>
                    <label class="col-lg-2">
                        <input type="hidden" name="vendedor_1" id="vendedor_1"
                            value="{{ isset($vendedor->id) ? $vendedor->id : 0 }}">
                        <input type="text" name="rut" id="rut2" class="form-control rut2"
                            placeholder="99.999.999-9"
                            value="{{ isset($vendedor->rut) ? str_replace('.', '', explode('-', $vendedor->rut)[0]) : str_replace('.', '', explode('-',$propietario_data->rut)[0]) }}"
                            required>
                    </label>
                    <label class="col-lg-2"></label>

                    <label for="nombre" class="col-lg-2 control-label"><span style="color:#f00">(*)</span>Nombre :</label>
                    <label class="col-lg-4">
                        <input type="text" name="nombre" id="nombre" class="form-control"
                            placeholder="Nombre del Vendedor"
                            value="{{ isset($vendedor->nombre) ? $vendedor->nombre : (($propietario_data->nombre != null)? $propietario_data->nombre : $propietario_data->razon_social)  }}" required>
                    </label>
                </div>
                <div class="form-group">
                    <label for="aPaterno" class="col-lg-2 control-label">Apellido Paterno :</label>
                    <label class="col-lg-4">
                        <input type="text" name="aPaterno" id="aPaterno" class="form-control"
                            placeholder="Apellido Paterno"
                            value="{{ isset($vendedor->aPaterno) ? $vendedor->aPaterno : $propietario_data->aPaterno }}">
                    </label>

                    <label for="aMaterno" class="col-lg-2 control-label">Apellido Materno :</label>
                    <label class="col-lg-4">
                        <input type="text" name="aMaterno" id="aMaterno" class="form-control"
                            placeholder="Apellido Materno"
                            value="{{ isset($vendedor->aMaterno) ? $vendedor->aMaterno : $propietario_data->aMaterno }}">
                    </label>
                </div>
                <!--
                <div class="form-group">
                    <label for="calle" class="col-lg-2 control-label">Dirección (calle) :</label>
                    <label class="col-lg-4">
                        <input type="text" name="calle" id="calle" class="form-control"
                            placeholder="Calle de la dirección"
                            value="{{ isset($vendedor->calle) ? $vendedor->calle : '' }}">
                    </label>

                    <label for="numero" class="col-lg-2 control-label">Número :</label>
                    <label class="col-lg-2">
                        <input type="text" name="numero" id="numero" class="form-control"
                            placeholder="Número de la dirección"
                            value="{{ isset($vendedor->numero) ? $vendedor->numero : old('numero') }}">
                    </label>
                    <label class="col-lg-2"></label>
                </div>-->
                <!--
                <div class="form-group">
                    <label for="rDireccion" class="col-lg-2 control-label">Complemento de dirección :</label>
                    <label class="col-lg-4">
                        <input type="text" name="rDireccion" id="rDireccion" class="form-control"
                            placeholder="Complemento de la dirección"
                            value="{{ isset($vendedor->rDomicilio) ? $vendedor->rDomicilio : old('rDireccion') }}">
                    </label>

                    <label for="comuna" class="col-lg-2 control-label">Comuna :</label>
                    <label class="col-lg-4">
                        <select class="col-sm-12 form-select comuna" name="comuna" id="comuna">
                            <option value="0" selected>Seleccione Comuna...</option>
                            @foreach ($comunas as $item)
                                <option value="{{ $item->Codigo }}"
                                    @if (isset($vendedor->comuna)) @if ($vendedor->comuna == $item->id) selected @endif
                                    @endif>{{ $item->Nombre }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>-->
                <div class="form-group">
                    <label for="email" class="col-lg-2 control-label"><span style="color:#f00">(*)</span>Email :</label>
                    <label class="col-lg-4">
                        <input type="email" name="email" id="email" class="form-control" placeholder="Email"
                            value="{{ isset($vendedor->email) ? $vendedor->email : '' }}">
                    </label>
                    <!--
                    <label for="telefono" class="col-lg-2 control-label">Teléfono :</label>
                    <label class="col-lg-2">
                        <input type="text" name="telefono" id="telefono" class="form-control"
                            placeholder="Ej. 978653214"
                            value="{{ isset($vendedor->telefono) ? $vendedor->telefono : '' }}" >
                    </label>
                    <label class="col-lg-2"></label>-->
                    <label for="tipoPersona2" class="col-lg-2 control-label"><span style="color:#f00">(*)</span>Tipo de Persona :</label>
                    <label class="col-lg-4">
                        <select class="col-sm-12 form-select" name="tipoPersona" id="tipoPersona2" required>
                            @if (!isset($vendedor->tipo))
                                <option value="N" selected>NATURAL</option>
                                <option value="J">JURÍDICO</option>
                                <option value="E">EXTRANJERO</option>
                                <option value="O">COMUNIDAD</option>
                            @else
                                <option data-tipo="N" value="N"
                                    @if ($vendedor->tipo == 'N') selected @endif>NATURAL</option>
                                <option data-tipo="J" value="J"
                                    @if ($vendedor->tipo == 'J') selected @endif>JURÍDICO</option>
                                <option data-tipo="E" value="E"
                                    @if ($vendedor->tipo == 'E') selected @endif>EXTRANJERO</option>
                                <option data-tipo="O" value="O"
                                    @if ($vendedor->tipo == 'O') selected @endif>COMUNIDAD</option>
                            @endif
                        </select>
                    </label>
                </div>
                <div class="form-group" style="display: none" id="integrantesComunidad2">
                    <label for="cantidad_integrantes_vende" class="col-lg-2 control-label"><span style="color:#f00">(*)</span>Cantidad Integrantes Comunidad :</label>
                    <label class="col-lg-3">
                        <input type="number" name="cantidad_integrantes_vende" id="cantidad_integrantes_vende" class="form-control"
                            value="{{ isset($vendedor->cantidadIntegrantes) ? $vendedor->cantidadIntegrantes : old('cantidadIntegrantes') }}">
                    </label>
                </div>
                <div class="form-group">
                    
                </div>
                <hr>
            </div>
        </div>
        <div class="panel-footer">
            <button type="submit" class="btn btn-system">
                <li class="fa fa-save"></li> Grabar y Continuar
            </button>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {

        var rut_format = $(".rut2").val();
        console.log(rut_format);
        if(rut_format.trim() != ''){
            rut_format = rut_format + $.computeDv(rut_format);
            $(".rut2").val($.formatRut(rut_format)); 
        }

        $('.comuna').multiselect({
            enableCaseInsensitiveFiltering: true,
            enableFiltering: true
        });
        $('#tipoPersona2').multiselect();

        $("#tipoPersona2").on('change', function() {
            if ($(this).val() == 'O') {
                //$('#agregar').show();
                $('#integrantesComunidad2').show();
            } else {
                /*$('#agregar').hide();
                $('#comprador2').hide();
                $('#comprador3').hide();
                $('#rut2').val('');
                $('#rut3').val('');*/
                $('#integrantesComunidad2').hide();
            };
        });

        $(".rut2").rut({
            formatOn: 'keyup',
            minimumLength: 8,
            validateOn: 'change'
        });

        $(".rut2").rut().on('rutInvalido', function(e) {
            new PNotify({
                title: 'Rut de Vendedor',
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


        $(document).on("click", "#btnCancelProcess", function() {
            if (confirm(
                "¿Está seguro de eliminar la solicitud de transferencia y los datos asociados?")) {
                showOverlay();
                $.ajax({
                    url: "/transferencia/delete/{{ $id }}",
                    type: "delete",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function() {

                        window.location.href = "https://" + "{{ $_SERVER['HTTP_HOST'] }}" +
                            "/transferencia/index";
                    }
                });
            }

        });


        $('#tipoPersona2').trigger('change');
    });

    $(document).on("submit", "#form-vendedor", function(e) {
        e.preventDefault();
        showOverlay();
        let formData = new FormData(document.getElementById('form-vendedor'));

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            data: formData,
            url: "/transferencia/{{ $id }}/saveVendedor",
            type: "POST",
            processData: false, // tell jQuery not to process the data
            contentType: false, // tell jQuery not to set contentType
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
            success: function(data) {
                hideOverlay();
                var jsonString = JSON.stringify(data);
                let json = JSON.parse(jsonString);
                if (json.status == "OK") {
                    if (parseFloat($("#vendedor_1").val()) == 0) {
                        $("#pills-estipulante").html(json.html);
                        $("#pills-vendedor").removeClass('show');
                        $("#pills-vendedor").addClass('hide');
                        $("#pills-estipulante").removeClass('hide');
                        $("#pills-estipulante").addClass('show');
                        $("#pills-estipulante-tab").attr("href", "#pills-estipulante");
                        $("#pills-estipulante-tab").toggleClass('disabled');
                        $("#pills-estipulante-tab").attr("aria-disabled", false);
                        $("#pills-estipulante-tab").click();
                        $("#vendedor_1").val(json.id_vendedor);
                    } else {
                        new PNotify({
                            title: 'Editar vendedor',
                            text: 'Vendedor editado correctamente',
                            shadow: true,
                            opacity: '1',
                            addclass: 'stack_top_right',
                            type: 'success',
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
                } else {
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
