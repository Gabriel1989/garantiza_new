@extends("themes.$themes.layout")

@section('contenido')
@include('includes.mensaje')

<?php
    use App\Models\Solicitud;
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
                            <th scope="col">Sucursal</th>
                            <th scope="col">Cliente</th>
                            <th scope="col" style="width:250px">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($solicitudes as $item)
                            <tr>
                                <td scope="row">{{$item->id}}</td>
                                <td>{{date('d-m-Y h:i A', strtotime($item->created_at))}}</td>
                                <td>{{$item->sucursales}}</td>
                                <td>{{$item->cliente->razon_social_recep}}</td>
                                <td>
                                    <?php
                                    $solicitud_rc = Solicitud::getSolicitudRC($item->id);
                                    ?>
                                    @if(count($solicitud_rc) > 0)
                                        <button type="button" data-toggle="modal" data-target="#modal_solicitud" data-garantizaSol="{{$item->id}}" data-numsol="{{ $solicitud_rc[0]->numeroSol }}" class="btn btn-dark btn-sm btnRevisaSolicitud">
                                            <li class="fa fa-pencil"></li> Revisar estado solicitud en RC
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
            e.preventDefault();
            let numSolRC = $(this).data('numsol');
            let numSolGarantiza = $(this).data('garantizasol');

            $.ajax({
                url: "/solicitud/"+numSolGarantiza+"/verEstadoSolicitud",
                type: "post",
                data: {
                    id_solicitud_rc: numSolRC,
                    _token: "{{ csrf_token() }}"
                },
                success: function(data){
        
                    $("#modal_solicitud_body").html(data);
                }
            })

        })
    </script>
@endsection