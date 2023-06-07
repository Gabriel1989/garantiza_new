@section('styles')
@endsection


@include('includes.form-error-message')
@include('includes.mensaje')
<form enctype="multipart/form-data" id="formConsultaPPU" class="form-transferencia" method="POST">
    @csrf
    @method('POST')
    <div class="panel panel-info panel-border top">
        <div class="panel-heading">
            <span class="panel-title">Consulta datos de vehículo a transferir</span>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label for="name" class="col-lg-1">Ingrese PPU de vehículo a consultar: </label>
                <label class="col-lg-5">
                    <input type="text" name="ppu_request" id="ppu_request" placeholder="" maxlength="6" value="<?php if($solicitud_data != null){ echo trim($solicitud_data->vehiculo->ppu);}?>
                    ">
                </label>
            </div>
            
        </div>

    <div class="panel-footer">
        <button type="submit" class="btn btn-system"> <li class="fa fa-upload"></li> Consultar datos y Continuar</button>
    </div>
</div>
</form>

@section('scripts')
<script>

    $(document).on("submit","#formConsultaPPU",function(e){
        showOverlay();
        e.preventDefault();

        let formData = new FormData(document.getElementById("formConsultaPPU"));

        $.ajax({
            data: formData,
            processData: false,
            contentType: false,
            type: "post",
            url: "{{route('transferencia.consultaDataVehiculo')}}",
            error: function(jqXHR, textStatus, errorThrown) {
                hideOverlay();
                // Acción cuando hay un error.
                new PNotify({
                        title: 'Error',
                        text: "AJAX error: " + textStatus + ' : ' + errorThrown,
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
                        delay: 5000
                });
            },
            success: function(data){
                hideOverlay();
                let json = JSON.parse(data);
                if(json.status == "ERROR"){
                    new PNotify({
                        title: 'Consultar datos',
                        text: json.msj,
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
                        width: '290px'
                    });
                }
                else{
                    $("#pills-datavehiculo").html(json.html);
                    $("#pills-datavehiculo").toggleClass('show');
                    $("#pills-consultappu").removeClass('show');
                    $("#pills-consultappu").addClass('hide');
                    $("#pills-datavehiculo-tab").attr("href","#pills-datavehiculo");
                    $("#pills-datavehiculo-tab").toggleClass('disabled');
                    $("#pills-datavehiculo-tab").attr("aria-disabled",false);
                    $("#pills-datavehiculo-tab").click();
                }
                
            }
        });

    });

</script>
@endsection