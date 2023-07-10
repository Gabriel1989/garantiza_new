@extends("themes.$themes.layout")

@section('contenido')
@include('includes.mensaje')

<div class="panel panel-info panel-border top">
    <div class="panel-heading">
        <span class="panel-title">Buscador de transferencias por N° Documento</span>
    </div>
    <div class="panel-body">
        <form id="formBusqueda">
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="form-group">
                        <label>N° Documento:</label>
                        <input class="form-control" name="num_doc" id="num_doc">
                    </div>
                    <div class="form-group">
                        <button type="submit" id="buscaSolicitudesForm" class="btn btn-primary">Buscar</button>
                    </div>
                </div>
            </div>
        </form>
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <table id="tabla-data" class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Solicitud N°</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Notaria</th>
                            <th scope="col">Etapas de solicitud</th>
                            <th scope="col">Cliente</th>
                            <th scope="col">Estado Pago</th>
                            <th scope="col">Monto inscripción</th>
                            <th scope="col" style="width:250px">Acciones</th>
                            <th scope="col" style="width:100px">Consulta RC</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-data-body">

                    </tbody>

                </table>
            </div>
        </div>
        <div class="modal fade" id="modal-pago-form" tabindex="-1" role="dialog" aria-labelledby="modal-pago-formLabel" aria-hidden="true">
            <div class="modal-dialog" role="document" style="min-width:450px;">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Registrar Pago Solicitud</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body" id="modal-pago-form_body">
                  <form id="formRegistraPago">
                    <input type="hidden" name="registra_pago_sol_id" id="registra_pago_sol_id" value="">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Monto</label>
                        <input type="number" class="form-control" id="monto_pago_form" name="monto_pago_form" placeholder="Monto">
                    </div>
                    <div class="form-group">
                        <span class="btn btn-warning fileinput-button col-sm-12" name="ComprobantePDF" id="ComprobantePDF">
                            Comprobante de transferencia RC Pagada</span>
                        <input id="Comprobante_PDF" name="Comprobante_PDF" type="file" style="display:none" accept="application/pdf" />
                        <label id="lbl_Comprobante_PDF"></label>
                    </div>
                    <br>
                    <div class="form-group">
                        <button type="submit" class="btn btn-system"> <li class="fa fa-save"></li> Grabar Pago</button>
                    </div>

                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
              </div>
            </div>
        </div>

        <div class="modal fade" id="modal-docs-form" tabindex="-1" role="dialog" aria-labelledby="modal-docs-formLabel" aria-hidden="true">
            <div class="modal-dialog" role="document" style="min-width:450px;">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Ver Documentos Solicitud</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body" id="modal-docs-form_body">
                    <table id="tableDocs3" class="table table-bordered"><thead>
                        <tr>
                            <th>Nombre Archivo</th>
                            <th>Tipo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tableDocsBody3">


                    </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
              </div>
            </div>
        </div>

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

@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap5.min.css"> 
    <style>
        .custom-tooltip {
          position: absolute;
          z-index: 1070;
          display: block;
          font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
          font-size: 12px;
          font-style: normal;
          font-weight: 400;
          line-height: 1.42857143;
          text-align: left;
          text-decoration: none;
          word-wrap: break-word;
          opacity: 0;
          transition: opacity 150ms;
        }
        .custom-tooltip.show {
          opacity: 0.9;
        }
        .custom-tooltip .tooltip-inner {
          max-width: 200px;
          padding: 3px 8px;
          color: #fff;
          text-align: center;
          background-color: #000;
          border-radius: 4px;
        }
        .custom-tooltip .tooltip-arrow {
          position: absolute;
          display: block;
          width: 0;
          height: 0;
          border-color: transparent;
          border-style: solid;
        }

        .red{
          color: #F00;
        }

        .green{
          color: #37e666;
        }
      </style>   
@endsection

@section('scripts')
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap5.min.js"></script>
    <script src="/js/jquery.rut.min.js"></script>
    
    <script>
        $(document).ready(function() {
            

            $(document).ready(function() {
                $('#tabla-data').DataTable({
                    language: {
                        "url": "/json/datatable.spanish.json"
                    }
                });
            });

            $(document).on("click",".btnDescargaPdfGarantiza",function(e){
                showOverlay();
                e.preventDefault();
                let numSolGarantiza = $(this).data('garantizasol');
                fetch("/transferencia/" + numSolGarantiza + "/descargaComprobanteTransferencia", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        id_transferencia: numSolGarantiza
                    })
                }).then((response) => response.json())
                .then((data) => {
                    hideOverlay();
                    window.open(data.file);
                });
            });
        });

        function registrarPagoForm(id){
            $('#modal-pago-form').modal('show');
            $('#ComprobantePDF').on('click', function() {
                $('#Comprobante_PDF').trigger('click');
            });

            $('#Comprobante_PDF').on('change', function() {
                $('#lbl_Comprobante_PDF').text($('#Comprobante_PDF').val());
            });

            $("#registra_pago_sol_id").val(id);
        }

        function verDocsSolicitud(id){
            $('#modal-docs-solicitud').modal('show');
            $.ajax({
                url: "/documentoTransferencia/"+id+"/get",
                type: "get",
                success: function(data2) {
                    let jsondata = JSON.parse(data2);
                    let html = jsondata.data;
                    $("#tableDocsBody3").html(html);
                    $('#tableDocs3 tr > *:nth-child(3)').hide();
                }
            });

        }

        $(document).on("submit","#formRegistraPago",function(e){
            showOverlay();
            e.preventDefault();
            
            let formData = new FormData(document.getElementById("formRegistraPago"));

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "/documento/transferencia/"+formData.get('registra_pago_sol_id')+"/cargapago",
                type: "post",
                processData: false,
                contentType: false,
                data: formData,
                success: function(data){
                    hideOverlay();
                    let json = JSON.parse(data);
                    if(json.status == "ERROR"){
                        new PNotify({
                            title: 'Registro Pago',
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
                        return false;
                    }
                    else{
                        new PNotify({
                            title: 'Registro Pago',
                            text: json.msj,
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
                            width: '290px'
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Acción cuando hay un error.
                    hideOverlay();
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
                        width: '290px'
                    });
                },
            });

        });

        function createTooltip(element) {
            var tooltip = document.createElement("div");
            tooltip.className = "custom-tooltip";
            tooltip.innerHTML = '<div class="tooltip-arrow"></div><div class="tooltip-inner">' + element.getAttribute("title") + "</div>";
            document.body.appendChild(tooltip);
            return tooltip;
        }
      
        function showTooltip(element, tooltip) {
        tooltip.classList.add("show");
        var popperInstance = Popper.createPopper(element, tooltip, {
            placement: element.dataset.placement || "top",
            modifiers: [
            {
                name: "offset",
                options: {
                offset: [0, 8],
                },
            },
            ],
        });
        return popperInstance;
        }
      
        function hideTooltip(tooltip, popperInstance) {
        popperInstance.destroy();
        tooltip.classList.remove("show");
        }


        $(document).on("submit","#formBusqueda",function(e){
            e.preventDefault();
            showOverlay();
            let formData = new FormData(document.getElementById("formBusqueda"));

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ route('buscador.stev.numerodoc.form') }}",
                type: "post",
                processData: false,
                contentType: false,
                data: formData,
                dataType: "html",
                success: function(data){
                    hideOverlay();
                    var table = $('#tabla-data').DataTable();
                    table.clear();
                    $("#tabla-data-body").html(data);

                    table.rows.add($(data)).draw();

                    $('[data-toggle="tooltip"]').each(function () {
                        var element = this;
                        var tooltip = createTooltip(element);
                        var popperInstance;
      
                        $(element).on("mouseenter", function () {
                        popperInstance = showTooltip(element, tooltip);
                        });
      
                        $(element).on("mouseleave", function () {
                        if (popperInstance) {
                            hideTooltip(tooltip, popperInstance);
                        }
                        });
                    });
                }
            });
        });

        $(document).on("click",".btnRevisaSolicitud",function(e){
            showOverlay();
            e.preventDefault();
            let numSolRC = $(this).data('numsol');
            let numSolGarantiza = $(this).data('garantizasol');
            $(".modal-title").text('Estado Solicitud');

            $.ajax({
                url: "/transferencia/"+numSolGarantiza+"/verEstadoSolicitud",
                type: "post",
                data: {
                    id_transferencia_rc: numSolRC,
                    _token: "{{ csrf_token() }}"
                },
                beforeSend: function() {
                    $("#modal_solicitud_body").html('<div style="margin-left: auto;margin-right: auto;" class="loader"></div>');
                },
                success: function(data){
                    hideOverlay();
                    $("#modal_solicitud_body").html(data);
                }
            })

        })

        $(document).on("click",".btnRevisaLimitacion",function(e){
            showOverlay();
            e.preventDefault();
            let numSolRC = $(this).data('numsol');
            let numSolGarantiza = $(this).data('garantizasol');
            $(".modal-title").text('Estado de Limitación/Prohibición');

            $.ajax({
                url: "/transferencia/"+numSolGarantiza+"/limitacion/verEstadoSolicitud",
                type: "post",
                data: {
                    id_solicitud_rc: numSolRC,
                    _token: "{{ csrf_token() }}"
                },
                beforeSend: function() {
                    $("#modal_solicitud_body").html('<div style="margin-left: auto;margin-right: auto;" class="loader"></div>');
                },
                success: function(data){
                    hideOverlay();
                    $("#modal_solicitud_body").html(data);
                }
            })

        });
        
        $(document).on("click", ".btnDescargaComprobanteLimi", function(e) {
            showOverlay();
            e.preventDefault();
            let numSolRC = $(this).data('numsol');
            let numSolGarantiza = $(this).data('garantizasol');

            fetch("/transferencia/" + numSolGarantiza + "/descargaComprobanteLimi", {
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

        $(document).on("click", ".btnDescargaComprobante", function(e) {
            showOverlay();
            e.preventDefault();
            let numSolRC = $(this).data('numsol');
            let numSolGarantiza = $(this).data('garantizasol');

            fetch("/transferencia/" + numSolGarantiza + "/descargaComprobanteTransferencia", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    id_transferencia_rc: numSolRC
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



        $(document).on("click",".btnRevisaReingreso",function(e){
            e.preventDefault();
            let data = atob($(this).data('reingreso'));
            data = JSON.parse(data);
            let html = '';
            $("#modal_solicitud_body").html('');
            const contenedor = document.getElementById('modal_solicitud_body');
            data.forEach((dato) => {
                const idLabel = document.createElement('label');
                idLabel.textContent = `ID: ${dato.id}`;
                contenedor.appendChild(idLabel);
                contenedor.appendChild(document.createElement('br'));

                const ppuLabel = document.createElement('label');
                ppuLabel.textContent = `PPU: ${dato.ppu}`;
                contenedor.appendChild(ppuLabel);
                contenedor.appendChild(document.createElement('br'));

                const estadoIdLabel = document.createElement('label');
                estadoIdLabel.textContent = `Estado ID: ${dato.estado_id}`;
                contenedor.appendChild(estadoIdLabel);
                contenedor.appendChild(document.createElement('br'));

                const solicitudIdLabel = document.createElement('label');
                solicitudIdLabel.textContent = `Solicitud ID: ${dato.solicitud_id}`;
                contenedor.appendChild(solicitudIdLabel);
                contenedor.appendChild(document.createElement('br'));

                const nroSolicitudLabel = document.createElement('label');
                nroSolicitudLabel.textContent = `Nro Solicitud: ${dato.nroSolicitud}`;
                contenedor.appendChild(nroSolicitudLabel);
                contenedor.appendChild(document.createElement('br'));

                const updatedAtLabel = document.createElement('label');
                updatedAtLabel.textContent = `Actualizado en: ${formatearFecha(dato.updated_at)}`;
                contenedor.appendChild(updatedAtLabel);
                contenedor.appendChild(document.createElement('br'));

                const observacionesLabel = document.createElement('label');
                if(JSON.parse(dato.observaciones).descrp != undefined){
                    observacionesLabel.textContent = `Observaciones: ${JSON.parse(dato.observaciones).descrp}`;
                }
                else{
                    observacionesLabel.textContent = `Observaciones: ${JSON.parse(dato.observaciones).Observa}`;
                }
                contenedor.appendChild(observacionesLabel);
                contenedor.appendChild(document.createElement('br'));

                // Agrega un separador para mejorar la legibilidad
                const separador = document.createElement('hr');
                contenedor.appendChild(separador);
            });
            
            $(".modal-title").text('Estado de Reingreso');
            
        });

        function formatearFecha(fecha) {
            const date = new Date(fecha);
            const dia = String(date.getDate()).padStart(2, '0');
            const mes = String(date.getMonth() + 1).padStart(2, '0');
            const año = date.getFullYear();
            const hora = String(date.getHours()).padStart(2, '0');
            const minuto = String(date.getMinutes()).padStart(2, '0');
            const segundo = String(date.getSeconds()).padStart(2, '0');

            return `${dia}-${mes}-${año} ${hora}:${minuto}:${segundo}`;
        }

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

        
    </script>
    
      
@endsection