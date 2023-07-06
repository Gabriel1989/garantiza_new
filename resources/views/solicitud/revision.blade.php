@extends("themes.$themes.layout")

@section('contenido')
@include('includes.mensaje')
<div class="panel panel-info panel-border top">
    <div class="panel-heading">
        <span class="panel-title">Solicitudes para Revisión</span>
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
                            <th scope="col">Concesionaria</th>
                            <th scope="col">Cliente</th>
                            <th scope="col">Estado Pago</th>
                            <th scope="col">Monto inscripción</th>
                            <th scope="col" style="width:250px">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($solicitudes as $item)
                            <tr>
                                <td scope="row">{{$item->id}}</td>
                                <td>{{date('d-m-Y h:i A', strtotime($item->created_at))}}</td>
                                <td>{{$item->concesionaria}}</td>
                                <td>
                                    {{@$item->nombreCliente->razon_social_recep}}
                                </td>
                                <td>@php echo (!$item->pagada)? '<span style="background-color:#F00;color:#ffffff;">No pagada</span>': '<span style="background-color:#08bd08;color:#ffffff;">Pagada</span>'; @endphp</td>
                                <td>{{$item->monto_inscripcion}}</td>
                                <td>
                                    <button type="button" class="btn btn-dark btn-sm" onclick="location.href='{{route('solicitud.revision.cedula', ['id' => $item->id])}}'">
                                        <li class="fa fa-pencil"></li> Revisar</button>
                                    <br>
                                    <button type="button" class="btn btn-sm btn-primary" data-solicitud="{{$item->id}}" data-toggle="modal" data-target="#modal-pago-form" onclick="registrarPagoForm({{$item->id}})">
                                        <li class="fa fa-money"></li> Registrar Pago</button>
                                    </button>
                                    <br>
                                    <button type="button" class="btn btn-sm btn-primary" data-solicitud="{{$item->id}}" data-toggle="modal" data-target="#modal-docs-form" onclick="verDocsSolicitud({{$item->id}})">
                                        <li class="fa fa-file"></li> Ver Documentos</button>
                                    </button>
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
                            Comprobante de inscripción Pagada</span>
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
                url: "/documento/"+id+"/get",
                type: "get",
                success: function(data2) {
                    let jsondata = JSON.parse(data2);
                    let html = jsondata.data;
                    $("#tableDocsBody3").html(html);
                    $('#tableDocs3 tr > *:nth-child(3)').hide();
                }
            });

        }

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
                url: "/documento/"+formData.get('registra_pago_sol_id')+"/cargapago",
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
    </script>
@endsection