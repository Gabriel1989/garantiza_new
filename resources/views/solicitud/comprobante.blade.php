@section('styles')
    <!-- FlowChart CSS -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset("assets/$themes/vendor/plugins/flowchart/jquery.flowchart.min.css") }}">
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

<form method="post" id="formDescargaComprobante" role="form" class="form-horizontal form-revision">
    @csrf
    @method('post')

    <div class="panel panel-info panel-border top">
        <div class="panel-heading">
            <span class="panel-title">Ingreso de Solicitud N° {{ $id }} - Descargar comprobante de
                inscripción</span>
        </div>

        <div class="panel-body">
            <button type="button" data-garantizaSol="{{ $id }}"
                data-numsol="{{ $solicitud_rc[0]->numeroSol }}" class="btn btn-success btn-sm btnRevisaComprobante"><i
                    class="fa fa-download"></i> Descargar comprobante</button>



        </div>

    </div>

</form>

<script>
    function showErrorNotification(message) {
        new PNotify({
            title: 'Error',
            text: message,
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
            width: '290px',
            delay: 2000
        });
    }
    
    $(document).on("click", ".btnRevisaComprobante", function(e) {
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
