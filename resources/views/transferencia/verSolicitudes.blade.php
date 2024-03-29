@extends("themes.$themes.layout")

@section('contenido')
@include('includes.mensaje')

<?php
    use App\Models\Transferencia;
    use App\Models\Limitacion;
    use App\Models\Reingreso;
?>
<div class="panel panel-info panel-border top">
    <div class="panel-heading">
        <span class="panel-title">Mis solicitudes ingresadas</span>
        <div class="widget-menu pull-right">
            {{-- <a href="{{route('sucursal.create')}}" class="btn btn-info btn-sm"><li class="fa fa-plus"></li> Agregar Sucursal</a> --}}
        </div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <div class="section">
                    <table id="tabla-data" class="table table-bordered">
                        <thead>
                        <tr>
                            <th scope="col">Solicitud N°</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Notaria</th>
                            <th scope="col">Etapas de solicitud</th>
                            <th scope="col">Cliente</th>
                            <th scope="col" style="width:250px">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $classes = ['active', 'success', 'warning'];
                            $classesCount = count($classes);
                        @endphp 
                        @foreach ($solicitudes as $index => $item)
                            @php 
                                $class = $classes[$index % $classesCount];   
                                echo "<tr class=\"$class\">";
                            @endphp
                                <td scope="row">{{$item->id}}</td>
                                <td>{{date('d-m-Y h:i A', strtotime($item->created_at))}}</td>
                                <td>{{$item->notarias}}</td>
                                <td>
                                    @if($item->estado_id == 1)
                                        <i class="fa fa-check green"></i>Solicitud creada
                                        <br>
                                        <i class="fa fa-times red"></i>Ingresar comprador
                                        <br>
                                        <i class="fa fa-times red"></i>Ingresar vendedor
                                        <br>
                                        <i class="fa fa-times red"></i>Ingresar estipulante o no
                                        <br>
                                        <i class="fa fa-times red"></i>Ingresar resumen transferencia
                                        <br>
                                        <i class="fa fa-times red"></i>Adjuntar documentación
                                        <br>
                                        <i class="fa fa-times red"></i>Ingresar limitación
                                        <br>
                                        <i class="fa fa-times red"></i>Proceso finalizado
                                    @elseif($item->estado_id == 2)
                                        <i class="fa fa-check green"></i>Solicitud creada
                                        <br>
                                        <i class="fa fa-check green"></i>Ingresar comprador
                                        <br>
                                        <i class="fa fa-times red"></i>Ingresar vendedor
                                        <br>
                                        <i class="fa fa-times red"></i>Ingresar estipulante o no
                                        <br>
                                        <i class="fa fa-times red"></i>Ingresar resumen transferencia
                                        <br>
                                        <i class="fa fa-times red"></i>Adjuntar documentación
                                        <br>
                                        <i class="fa fa-times red"></i>Ingresar limitación
                                        <br>
                                        <i class="fa fa-times red"></i>Proceso finalizado
                                    @elseif($item->estado_id == 3)
                                        <i class="fa fa-check green"></i>Solicitud creada
                                        <br>
                                        <i class="fa fa-check green"></i>Ingresar comprador
                                        <br>
                                        <i class="fa fa-check green"></i>Ingresar vendedor
                                        <br>
                                        <i class="fa fa-times red"></i>Ingresar estipulante o no
                                        <br>
                                        <i class="fa fa-times red"></i>Ingresar resumen transferencia
                                        <br>
                                        <i class="fa fa-times red"></i>Adjuntar documentación
                                        <br>
                                        <i class="fa fa-times red"></i>Ingresar limitación
                                        <br>
                                        <i class="fa fa-times red"></i>Proceso finalizado
                                    @elseif($item->estado_id == 4)
                                        <i class="fa fa-check green"></i>Solicitud creada
                                        <br>
                                        <i class="fa fa-check green"></i>Ingresar comprador
                                        <br>
                                        <i class="fa fa-check green"></i>Ingresar vendedor
                                        <br>
                                        <i class="fa fa-check green"></i>Ingresar estipulante o no
                                        <br>
                                        <i class="fa fa-times red"></i>Ingresar resumen transferencia
                                        <br>
                                        <i class="fa fa-times red"></i>Adjuntar documentación
                                        <br>
                                        <i class="fa fa-times red"></i>Ingresar limitación
                                        <br>
                                        <i class="fa fa-times red"></i>Proceso finalizado    
                                    @elseif($item->estado_id == 5)
                                        <i class="fa fa-check green"></i>Solicitud creada
                                        <br>
                                        <i class="fa fa-check green"></i>Ingresar comprador
                                        <br>
                                        <i class="fa fa-check green"></i>Ingresar vendedor
                                        <br>
                                        <i class="fa fa-check green"></i>Ingresar estipulante
                                        <br>
                                        @if($item->numeroSol != null)
                                            @if($item->nroSolicitud == null)
                                                <i class="fa fa-check green"></i>Transferencia creada en RC
                                                <br>
                                            @else
                                                <i class="fa fa-check green"></i>Reingreso pendiente en RC
                                                <br>
                                            @endif
                                        @else
                                            <i class="fa fa-check green"></i>Ingresar resumen transferencia
                                            <br>
                                        @endif
  
                                        @if($item->numeroSolDocrc != null)
                                            <i class="fa fa-check green"></i>Documentación enviada a RC
                                            <br>
                                        @else
                                            <i class="fa fa-times red"></i>Documentación NO enviada a RC
                                            <br>
                                        @endif
                                        @if($item->id_limitacion != null)
                                            @if($item->id_limitacion_rc != null)
                                                <i class="fa fa-check green"></i>Registrar limitación en RC
                                                <br>
                                            @else
                                                <i class="fa fa-check green"></i>Ingresar limitación
                                                <br>
                                            @endif
                                        @else
                                            <i class="fa fa-times red"></i>Ingresar limitación
                                            <br>
                                        @endif
  
                                        @if($item->pagada && $item->monto_inscripcion > 0)
                                            <i class="fa fa-check green"></i>Proceso finalizado
                                        @else
                                            <i class="fa fa-times red"></i>Proceso finalizado
                                        @endif
  
                                    @elseif($item->estado_id == 12)
                                        <i class="fa fa-check green"></i>Solicitud creada
                                        <br>
                                        <i class="fa fa-check green"></i>Ingresar comprador
                                        <br>
                                        <i class="fa fa-check green"></i>Ingresar vendedor
                                        <br>
                                        <i class="fa fa-check green"></i>Ingresar estipulante
                                        <br>
                                        @if($item->numeroSol != null)
                                            @if($item->nroSolicitud == null)
                                                <i class="fa fa-check green"></i>Transferencia creada en RC
                                                <br>
                                            @else
                                                <i class="fa fa-check green"></i>Reingreso pendiente en RC
                                                <br>
                                            @endif
                                        @else
                                            <i class="fa fa-check green"></i>Ingresar resumen transferencia
                                            <br>
                                        @endif
  
                                        @if($item->numeroSolDocrc != null)
                                            <i class="fa fa-check green"></i>Documentación enviada a RC
                                            <br>
                                        @else
                                            <i class="fa fa-times red"></i>Documentación NO enviada a RC
                                            <br>
                                        @endif
                                        @if($item->id_limitacion != null)
                                            @if($item->id_limitacion_rc != null)
                                                <i class="fa fa-check green"></i>Registrar limitación en RC
                                                <br>
                                            @else
                                                <i class="fa fa-check green"></i>Ingresar limitación
                                                <br>
                                            @endif
                                        @else
                                            <i class="fa fa-times red"></i>Ingresar limitación
                                            <br>
                                        @endif
                                        
                                        @if($item->pagada && $item->monto_inscripcion > 0)
                                            <i class="fa fa-check green"></i>Proceso finalizado
                                        @else
                                            <i class="fa fa-times red"></i>Proceso finalizado
                                        @endif
  
                                    @endif
                                </td>
                                <td><?php
                                if($item->cliente != ''){ 
                                    echo is_null($item->cliente->nombre)? $item->cliente->razon_social : $item->cliente->nombre .' '.$item->cliente->aPaterno.' '.$item->cliente->aMaterno; 
                                } ?></td>
                                <td>
                                    <?php
                                    $solicitud_rc = Transferencia::getTransferenciaRC($item->id);
                                    ?>
                                    @if(count($solicitud_rc) > 0)
                                        <button type="button" style="margin-bottom:5px;" data-toggle="modal" data-target="#modal_solicitud" data-garantizaSol="{{$item->id}}" data-numsol="{{ $solicitud_rc[0]->numeroSol }}" class="btn btn-dark btn-sm btnRevisaSolicitud">
                                            <li class="fa fa-eye"></li> Revisar estado solicitud en RC
                                        </button>
                                    @endif    
                                    
                                    <?php
                                    $limitacion_rc = Limitacion::getLimitacionTransferenciaRC($item->id);
                                    ?>

                                    @if(count($limitacion_rc) > 0)
                                    <button type="button" data-toggle="modal" data-target="#modal_solicitud" data-garantizaSol="{{$item->id}}" data-numsol="{{ $limitacion_rc[0]->numSol }}" class="btn btn-dark btn-sm btnRevisaLimitacion">
                                        <li class="fa fa-eye"></li> Revisar estado solicitud de limitación/prohibición en RC
                                    </button>
                                    @endif       

                                    <?php
                                    $reingreso_rc = Reingreso::where('transferencia_id',$item->id)->get();
                                    ?>

                                    @if(count($reingreso_rc) > 0)
                                    <button type="button" data-toggle="modal" data-target="#modal_solicitud" data-reingreso="{{base64_encode($reingreso_rc)}}" class="btn btn-dark btn-sm btnRevisaReingreso">
                                        <li class="fa fa-eye"></li> Revisar estado de reingreso
                                    </button>
                                    @endif      


                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
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

@endsection


@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap5.min.css">    
    <style>
        .red{
          color: #F00 !important;
        }

        .green{
          color: #37e666 !important;
        }
    </style>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tabla-data').DataTable({
                language: {
                    "url": "/json/datatable.spanish.json"
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