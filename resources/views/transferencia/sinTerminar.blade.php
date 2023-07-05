@extends("themes.$themes.layout")

@section('contenido')
@include('includes.mensaje')


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
                    <table id="tabla-data" class="table">
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
                                    <button type="button" class="btn btn-dark btn-sm" onclick="location.href='{{route('transferencia.continuarSolicitud', ['id' => $item->id,'reingresa'=> 0,'acceso'=>'ingreso'])}} '">
                                        <li class="fa fa-pencil"></li> Continuar Ingreso</button>
                                    <br>
                                    <br>
                                    <button type="button" data-toggle="tooltip" data-placement="top" title="Reingresar transferencia" onclick="location.href='{{route('transferencia.continuarSolicitud', ['id' => $item->id,'reingresa'=> true,'acceso'=>'ingreso'])}} '" class="btn btn-success"><i class="fa fa-refresh"></i> Reingresar</button>
                                    <br>
                                    <br>
                                    <button type="button" data-toggle="tooltip" data-placement="top" title="Generar comprobante solicitud" class="btn btn-danger btnDescargaPdfGarantiza" data-garantizaSol="{{ $item->id }}"><i class="fa fa-file-pdf-o"></i> Generar comprobante</button>
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
          color: #F00 !important;
        }

        .green{
          color: #37e666 !important;
        }
      </style>   
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
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