@extends("themes.$themes.layout")

@section('contenido')
@include('includes.mensaje')
<?php
    use App\Models\Solicitud;
    use App\Models\Limitacion;
    use App\Models\Reingreso;
?>
<div class="panel panel-info panel-border top">
    <div class="panel-heading">
        <span class="panel-title">Solicitudes sin Terminar</span>
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
                            <th scope="col">Sucursal</th>
                            <th scope="col">Etapas de la solicitud</th>
                            <th scope="col">Cliente</th>
                            <th scope="col">Estado Pago</th>
                            <th scope="col">Monto inscripción</th>
                            <th scope="col">Trámites adicionales</th>
                            <th scope="col" style="width:250px">Acciones</th>
                            <th scope="col" style="width:100px">Consulta RC</th>
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
                                <td>{{$item->sucursales}}</td>
                                <td>
                                  @if($item->estado_id == 1)
                                      <i class="fa fa-check green"></i>Solicitud creada
                                      <br>
                                      <i class="fa fa-times red"></i>Ingresar adquiriente
                                      <br>
                                      <i class="fa fa-times red"></i>Compra Para 
                                      <br>
                                      <i class="fa fa-times red"></i>Ingresar resumen solicitud
                                      <br>
                                      <i class="fa fa-times red"></i>Adjuntar documentación
                                      <br>
                                      <i class="fa fa-times red"></i>Ingresar limitación
                                      <br>
                                      <i class="fa fa-times red"></i>Proceso finalizado

                                  @elseif($item->estado_id == 2)
                                      <i class="fa fa-check green"></i>Solicitud creada
                                      <br>
                                      <i class="fa fa-check green"></i>Ingresar adquiriente
                                      <br>
                                      <i class="fa fa-times red"></i>Compra Para 
                                      <br>
                                      <i class="fa fa-times red"></i>Ingresar resumen solicitud
                                      <br>
                                      <i class="fa fa-times red"></i>Adjuntar documentación
                                      <br>
                                      <i class="fa fa-times red"></i>Ingresar limitación
                                      <br>
                                      <i class="fa fa-times red"></i>Proceso finalizado

                                  @elseif($item->estado_id == 3)
                                      <i class="fa fa-check green"></i>Solicitud creada
                                      <br>
                                      <i class="fa fa-check green"></i>Ingresar adquiriente
                                      <br>
                                      @if($item->rut_para != null)
                                      <i class="fa fa-check green"></i>Compra Para ingresado
                                      @else
                                      <i class="fa fa-check green"></i>Compra Para no aplica                                        
                                      @endif
                                      <br>
                                      <i class="fa fa-times red"></i>Ingresar resumen solicitud
                                      <br>
                                      <i class="fa fa-times red"></i>Adjuntar documentación
                                      <br>
                                      <i class="fa fa-times red"></i>Ingresar limitación
                                      <br>
                                      <i class="fa fa-times red"></i>Proceso finalizado

                                  @elseif($item->estado_id == 6 || $item->estado_id == 7)
                                      <i class="fa fa-check green"></i>Solicitud creada
                                      <br>
                                      <i class="fa fa-check green"></i>Ingresar adquiriente
                                      <br>
                                      @if($item->rut_para != null)
                                      <i class="fa fa-check green"></i>Compra Para ingresado
                                      @else
                                      <i class="fa fa-check green"></i>Compra Para no aplica                                        
                                      @endif
                                      <br>
                                      @if($item->numeroSol != null)
                                          @if($item->nroSolicitud == null)
                                              <i class="fa fa-check green"></i>Primera Inscripción creada en RC
                                              <br>
                                          @else
                                              <i class="fa fa-check green"></i>Reingreso pendiente en RC
                                              <br>
                                          @endif
                                      @else
                                          <i class="fa fa-check green"></i>Ingresar resumen solicitud
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

                                  @elseif($item->estado_id == 11)
                                      <i class="fa fa-check green"></i>Solicitud creada
                                      <br>
                                      <i class="fa fa-check green"></i>Ingresar adquiriente
                                      <br>
                                      @if($item->rut_para != null)
                                      <i class="fa fa-check green"></i>Compra Para ingresado
                                      @else
                                      <i class="fa fa-check green"></i>Compra Para no aplica                                        
                                      @endif
                                      <br>
                                      <i class="fa fa-check green"></i>Envío RC rechazado
                                      <br>
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
                                          <i class="fa fa-times red"></i>Proceso finalizado


                                  @elseif($item->estado_id == 12)
                                      <i class="fa fa-check green"></i>Solicitud creada
                                      <br>
                                      <i class="fa fa-check green"></i>Ingresar adquiriente
                                      <br>
                                      @if($item->rut_para != null)
                                      <i class="fa fa-check green"></i>Compra Para ingresado
                                      @else
                                      <i class="fa fa-check green"></i>Compra Para no aplica                                        
                                      @endif
                                      <br>
                                      @if($item->numeroSol != null)
                                          @if($item->nroSolicitud == null)
                                              <i class="fa fa-check green"></i>Primera Inscripción creada en RC
                                              <br>
                                          @else
                                              <i class="fa fa-check green"></i>Reingreso pendiente en RC
                                              <br>
                                          @endif
                                      @else
                                          <i class="fa fa-check green"></i>Ingresar resumen solicitud
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
                                <td>{{@$item->cliente->razon_social_recep}}</td>
                                <td>@php echo (!$item->pagada)? '<span style="background-color:#F00;color:#ffffff;">No pagada</span>': '<span style="background-color:#08bd08;color:#ffffff;">Pagada</span>'; @endphp</td>
                                <td>{{$item->monto_inscripcion}}</td>
                                <td>
                                  <label>SOAP @if(!is_null($item->incluyeSOAP))  @if($item->incluyeSOAP == 1) <i class="fa fa-check green"></i>  @else <i class="fa fa-times red"></i> @endif @else <i class="fa fa-times red"></i> @endif </label>
                                  <br>
                                  <label>TAG @if(!is_null($item->incluyeTAG))  @if($item->incluyeTAG == 1) <i class="fa fa-check green"></i>  @else <i class="fa fa-times red"></i> @endif @else <i class="fa fa-times red"></i> @endif</label>
                                  <br>
                                  <label>Permiso de circulación @if(!is_null($item->incluyePermiso))  @if($item->incluyePermiso == 1) <i class="fa fa-check green"></i>  @else <i class="fa fa-times red"></i> @endif @else <i class="fa fa-times red"></i> @endif</label>
                                </td>
                                <td>
                                    <button type="button" data-toggle="tooltip" data-placement="top" title="Continuar ingreso de solicitud donde había quedado" class="btn btn-dark btn-sm" onclick="location.href='{{route('solicitud.continuarSolicitud', ['id' => $item->id,'reingresa'=> 0,'acceso'=>'ingreso'])}} '" data-old-onclick="location.href='{{route('solicitud.revision.cedula', ['id' => $item->id])}}'">
                                        <li class="fa fa-pencil"></li> Continuar Ingreso</button>
                                    <br>
                                    <br>
                                    <button type="button" data-toggle="tooltip" data-placement="top" title="Reingresar solicitud" onclick="location.href='{{route('solicitud.continuarSolicitud', ['id' => $item->id,'reingresa'=> true,'acceso'=>'ingreso'])}} '" class="btn btn-success"><i class="fa fa-refresh"></i> Reingresar</button>
                                    <br>
                                    <br>
                                    <button type="button" data-toggle="tooltip" data-placement="top" title="Generar comprobante solicitud" class="btn btn-danger btnDescargaPdfGarantiza" data-garantizaSol="{{ $item->id }}"><i class="fa fa-file-pdf-o"></i> Generar comprobante</button>
                                </td>
                                <td>
                                  <?php
                                  $solicitud_rc = Solicitud::getSolicitudRC($item->id);
                                  ?>
                                  @if(count($solicitud_rc) > 0)
                                      <button type="button" style="margin-bottom:5px;" data-toggle="modal" data-target="#modal_solicitud" data-garantizaSol="{{$item->id}}" data-numsol="{{ $solicitud_rc[0]->numeroSol }}" class="btn btn-dark btn-sm btnRevisaSolicitud">
                                          <li class="fa fa-eye"></li> Revisar estado solicitud en RC
                                      </button>
                                  @endif    
                                  
                                  <?php
                                  $limitacion_rc = Limitacion::getLimitacionRC($item->id);
                                  ?>

                                  @if(count($limitacion_rc) > 0)
                                  <button type="button" style="white-space:normal;margin-bottom:5px;" data-toggle="modal" data-target="#modal_solicitud" data-garantizaSol="{{$item->id}}" data-numsol="{{ $limitacion_rc[0]->numSol }}" class="btn btn-dark btn-sm btnRevisaLimitacion">
                                      <li class="fa fa-eye"></li> Revisar estado solicitud de limitación/prohibición en RC
                                  </button>
                                  @endif       

                                  <?php
                                  $reingreso_rc = Reingreso::where('solicitud_id',$item->id)->get();
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

        $(document).on("click",".btnDescargaPdfGarantiza",function(e){
          showOverlay();
          e.preventDefault();
          let numSolGarantiza = $(this).data('garantizasol');
          fetch("/solicitud/" + numSolGarantiza + "/descargaComprobanteRVM", {
              method: "POST",
              headers: {
                  "Content-Type": "application/json",
                  "X-CSRF-TOKEN": "{{ csrf_token() }}"
              },
              body: JSON.stringify({
                  id_solicitud: numSolGarantiza
              })
          }).then((response) => response.json())
          .then((data) => {
              hideOverlay();
              window.open(data.file);
          });
        });

        $(document).on("click",".btnRevisaSolicitud",function(e){
            showOverlay();
            e.preventDefault();
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
                url: "/solicitud/"+numSolGarantiza+"/limitacion/verEstadoSolicitud",
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

            fetch("/solicitud/" + numSolGarantiza + "/descargaComprobanteLimi", {
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
                observacionesLabel.textContent = `Observaciones: ${JSON.parse(dato.observaciones).descrp}`;
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
    <script>
        $(function () {
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
        });
    </script>
      
@endsection