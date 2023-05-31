@section('styles')
<!-- FlowChart CSS -->
<link rel="stylesheet" type="text/css" href="{{asset("assets/$themes/vendor/plugins/flowchart/jquery.flowchart.min.css")}}">
<style>
    .flowchart-example-container {
        width: 800px;
        height: 500px;
        background: white;
        border: 1px solid #BBB;
        margin-bottom: 10px;
    }
</style>
@endsection

@include('includes.form-error-message')

<form method="post" id="formRevisaPago" role="form" class="form-horizontal form-revision" >
    @csrf
    @method('post')

    <div class="panel panel-info panel-border top">
        <div class="panel-heading">
            <span class="panel-title">Ingreso de Solicitud N° {{$id}} - Ingreso a Portal de Registro Civil para pago de inscripción</span>
        </div>
        <div class="panel-body">
            1- Ingrese a portal de pagos del RC en <a href="https://pagosrvm.srcei.cl/RVMI/logincu/inicio.srcei">https://pagosrvm.srcei.cl/RVMI/logincu/inicio.srcei</a>
            <br>
            2- Una vez realizado el pago, debe revisar el estado acá:
            <br>
            <button type="button" data-toggle="modal" data-target="#modal_solicitud"
            data-garantizaSol="{{$id}}" data-numsol="{{ $solicitud_rc[0]->numeroSol }}"
            class="btn btn-primary btn-sm btnRevisaSolicitud">Revisar estado inscripción</button>

            <div class="modal fade" id="modal_solicitud" tabindex="-1" role="dialog" aria-labelledby="modal_solicitudLabel" aria-hidden="true">
                <div class="modal-dialog" role="document" style="min-width:450px;">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Estado Solicitud</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body" id="modal_solicitud_body">
                      
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>

</form>


<script>
$(document).on("click",".btnRevisaSolicitud",function(e){
    e.preventDefault();
    showOverlay();
    let numSolRC = $(this).data('numsol');
    let numSolGarantiza = $(this).data('garantizasol');
    $(".modal-title").text('Estado Solicitud');

    $.ajax({
        url: "/solicitud/"+numSolGarantiza+"/verEstadoSolicitud",
        type: "post",
        data: {
            id_solicitud_rc: numSolRC,
            _token: "{{ csrf_token() }}"
        },
        success: function(data){
            hideOverlay();
            $("#modal_solicitud_body").html(data);
        }
    });
})

$(document).on("click", ".btnDescargaComprobante", function(e) {
            showOverlay();
            e.preventDefault();
            let numSolRC = $(this).data('numsol');
            let numSolGarantiza = $(this).data('garantizasol');

            fetch("/solicitud/" + numSolGarantiza + "/descargaComprobanteRVM", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    id_solicitud_rc: numSolRC
                })
            })
            .then(function(response) {
                hideOverlay();
                if (response.ok) {
                    if (response.headers.get('Content-Type') === 'application/pdf') {
                        return response.blob();
                    } else {
                        return response.json();
                    }
                } else {
                    throw new Error('Error en la petición');
                }
            })
            .then(function(data) {
                if (data instanceof Blob) {
                    var blob = new Blob([data], {
                        type: 'application/pdf'
                    });
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = 'voucher.pdf';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                } else {
                    showErrorNotification(data.error);
                }
            })
            .catch(function(error) {
                hideOverlay();
                showErrorNotification(error.message);
            });
        });



</script>