@extends("themes.$themes.layout")

@section('contenido')
@include('includes.mensaje')

<div class="panel panel-info panel-border top">
    <div class="panel-heading">
        <span class="panel-title">Buscador de solicitudes por RUT de adquiriente</span>
    </div>
    <div class="panel-body">
        <form id="formBusqueda">
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="form-group">
                        <label>Rut adquiriente:</label>
                        <input class="form-control rut" name="rut" id="rut">
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
                            <th scope="col">Sucursal</th>
                            <th scope="col">Etapas de la solicitud</th>
                            <th scope="col">Cliente</th>
                            <th scope="col">Trámites adicionales</th>
                            <th scope="col" style="width:250px">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-data-body">

                    </tbody>

                </table>
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
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap5.min.js"></script>
    <script src="/js/jquery.rut.min.js"></script>
    
    <script>
        $(document).ready(function() {
            var rut_format = $("#rut").val();
            if(rut_format != ""){
                rut_format = rut_format + $.computeDv(rut_format);
                $("#rut").val($.formatRut(rut_format));
            }

            $(".rut").rut({
                formatOn: 'keyup',
                minimumLength: 8, 
                validateOn: 'change' 
            });

            $(".rut").rut().on('rutInvalido', function(e) {
                new PNotify({
                    title: 'Rut de Concesionaria',
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


            $('#tabla-data').DataTable({
                language: {
                    "url": "/json/datatable.spanish.json"
                }
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
        });


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
                url: "{{ route('buscador.spiev.rutadquiriente.form') }}",
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
                }
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