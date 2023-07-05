@extends("themes.$themes.layout")

@section('contenido')
@include('includes.mensaje')
<div class="panel panel-info panel-border top">
    <div class="panel-heading">
        <span class="panel-title">Estadísticas de uso</span>
        <div class="widget-menu pull-right">
            {{-- <a href="{{route('sucursal.create')}}" class="btn btn-info btn-sm"><li class="fa fa-plus"></li> Agregar Sucursal</a> --}}
        </div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <a class="btn btn-primary btnVerEstadisticaMes" role="button" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                    Estadística de consultas a RC por mes-año y convenio
                </a>

                <div class="collapse" id="collapseExample">
                    <div class="well">
                        <div id="divEstadisticaMes" style="display:none;">
                            <canvas id="myChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-6">
                <a class="btn btn-primary btnVerEstadisticaTipoSolicitud" role="button" data-toggle="collapse" href="#collapseExample2" aria-expanded="false" aria-controls="collapseExample2">
                    Estadística de consultas a RC por mes-año y tipo solicitud
                </a>

                <div class="collapse" id="collapseExample2">
                    <div class="well">
                        <div id="divEstadisticaMesTipo" style="display:none;">
                            <canvas id="myChart2"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <a class="btn btn-primary btnVerEstadisticaConvenio" role="button" data-toggle="collapse" href="#collapseExample3" aria-expanded="false" aria-controls="collapseExample3">
                    Estadística de consultas a RC por convenio
                </a>

                <div class="collapse" id="collapseExample3">
                    <div class="well">
                        <div id="divEstadisticaConvenio" style="display:none;">
                            <canvas id="myChart3"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-6">
                <a class="btn btn-primary btnVerEstadisticaTipo" role="button" data-toggle="collapse" href="#collapseExample4" aria-expanded="false" aria-controls="collapseExample4">
                    Estadística de consultas a RC por tipo de solicitud
                </a>

                <div class="collapse" id="collapseExample4">
                    <div class="well">
                        <div id="divEstadisticaTipo" style="display:none;">
                            <canvas id="myChart4"></canvas>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <a class="btn btn-primary btnVerEstadisticaMesAnio" role="button" data-toggle="collapse" href="#collapseExample5" aria-expanded="false" aria-controls="collapseExample5">
                    Estadística de consultas a RC por mes-año
                </a>

                <div class="collapse" id="collapseExample5">
                    <div class="well">
                        <div id="divEstadisticaMesAnio" style="display:none;">
                            <canvas id="myChart5"></canvas>
                        </div>
                    </div>
                </div>

            </div>


            <div class="col-lg-6 col-md-6">

                <a class="btn btn-primary btnVerEstadisticaTotalesRC" role="button" data-toggle="collapse" href="#collapseExample6" aria-expanded="false" aria-controls="collapseExample6">
                    Estadística de consultas totales a RC 
                </a>

                <div class="collapse" id="collapseExample6">
                    <div class="well">
                        <div id="divEstadisticaTotalesRC" style="display:none;">
                            <canvas id="myChart6"></canvas>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        $(document).on("click",".btnVerEstadisticaMes",function(){
            $("#divEstadisticaMes").fadeIn();
            $.ajax({
                url: '{{ route("estadisticas.getSolicitudesRCPorMes.rc") }}',
                method: 'GET',
                dataType: 'json',
                beforeSend: function() {
                    $("#divEstadisticaMes").html('<div style="margin-left: auto;margin-right: auto;" class="loader"></div>');
                },
                success: function(response) {
                    $("#divEstadisticaMes").html('<canvas id="myChart"></canvas>');
                    let data = JSON.stringify(response);
                    data = JSON.parse(data);
                    let months = [...new Set(data.map(item => item.Month))].sort();
                    let labels = [...new Set(data.map(item => item.Convenio))];
                    let datasets = labels.map(label => {
                        let dataset = months.map(month => {
                            let item = data.find(item => item.Month === month && item.Convenio === label);
                            return item ? item.Count : 0;
                        });
                        return {
                            label: label,
                            data: dataset,
                            backgroundColor: getRandomColor(), // función que genera un color aleatorio
                        };
                    });
                    let ctx = document.getElementById('myChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: months,
                            datasets: datasets
                        },
                        options: {
                            responsive: true,
                            scales: {
                                x: { 
                                    beginAtZero: true,
                                    stacked: true,
                                },
                                y: {
                                    beginAtZero: true,
                                    stacked: true,
                                }
                            }
                        }
                    });
                }
            });
        });

        $(document).on("click",".btnVerEstadisticaTipoSolicitud",function(){
            $("#divEstadisticaMesTipo").fadeIn();
            $.ajax({
                url: '{{ route("estadisticas.getSolicitudesRCPorMesNombreServicio.rc") }}',
                method: 'GET',
                dataType: 'json',
                beforeSend: function() {
                    $("#divEstadisticaMesTipo").html('<div style="margin-left: auto;margin-right: auto;" class="loader"></div>');
                },
                success: function(response) {
                    $("#divEstadisticaMesTipo").html('<canvas id="myChart2"></canvas>');
                    let data = JSON.stringify(response);
                    data = JSON.parse(data);
                    let months = [...new Set(data.map(item => item.Month))].sort();
                    let labels = [...new Set(data.map(item => item.tipo_servicio))];
                    let datasets = labels.map(label => {
                        let dataset = months.map(month => {
                            let item = data.find(item => item.Month === month && item.tipo_servicio === label);
                            return item ? item.Count : 0;
                        });
                        return {
                            label: label,
                            data: dataset,
                            backgroundColor: getRandomColor(), // función que genera un color aleatorio
                        };
                    });
                    let ctx = document.getElementById('myChart2').getContext('2d');
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: months,
                            datasets: datasets
                        },
                        options: {
                            responsive: true,
                            scales: {
                                x: { 
                                    beginAtZero: true,
                                    stacked: true,
                                },
                                y: {
                                    beginAtZero: true,
                                    stacked: true,
                                }
                            }
                        }
                    });
                }
            });
        });

        $(document).on("click",".btnVerEstadisticaConvenio",function(){
            $("#divEstadisticaConvenio").fadeIn();
            $.ajax({
                url: '{{ route("estadisticas.getSolicitudesRCPorConvenio.rc") }}',
                method: 'GET',
                dataType: 'json',
                beforeSend: function() {
                    $("#divEstadisticaConvenio").html('<div style="margin-left: auto;margin-right: auto;" class="loader"></div>');
                },
                success: function(data){
                    $("#divEstadisticaConvenio").html('<canvas id="myChart3"></canvas>');
                    var convenios = [];
                    var solicitudes = [];

                    for (var i in data) {
                        convenios.push(data[i].Convenio);
                        solicitudes.push(data[i].Count);
                    }

                    var ctx = document.getElementById('myChart3').getContext('2d');
                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: convenios,
                            datasets: [{
                                label: 'Cantidad solicitudes por convenio',
                                data: solicitudes,
                                backgroundColor: getRandomColor()
                                // Otros parámetros para configurar el gráfico
                            }]
                        }
                    });

                }
            });
        });

        $(document).on("click",".btnVerEstadisticaTipo",function(){
            $("#divEstadisticaTipo").fadeIn();
            $.ajax({
                url: '{{ route("estadisticas.getSolicitudesRCPorTipoServicio.rc") }}',
                method: 'GET',
                dataType: 'json',
                beforeSend: function() {
                    $("#divEstadisticaTipo").html('<div style="margin-left: auto;margin-right: auto;" class="loader"></div>');
                },
                success: function(data){
                    $("#divEstadisticaTipo").html('<canvas id="myChart4"></canvas>');
                    var tipoServicios = [];
                    var solicitudes = [];

                    for (var i in data) {
                        tipoServicios.push(data[i].tipo_servicio);
                        solicitudes.push(data[i].Count);
                    }

                    var ctx = document.getElementById('myChart4').getContext('2d');
                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: tipoServicios,
                            datasets: [{
                                label: 'Cantidad solicitudes por tipo de servicio solicitado',
                                data: solicitudes,
                                backgroundColor: getRandomColor()
                                // Otros parámetros para configurar el gráfico
                            }]
                        }
                    });

                }
            });
        });

        $(document).on("click",".btnVerEstadisticaMesAnio",function(){
            $("#divEstadisticaMesAnio").fadeIn();
            $.ajax({
                url: '{{ route("estadisticas.getCantidadSolicitudesMes.rc") }}',
                method: 'GET',
                dataType: 'json',
                beforeSend: function() {
                    $("#divEstadisticaMesAnio").html('<div style="margin-left: auto;margin-right: auto;" class="loader"></div>');
                },
                success: function(data){
                    $("#divEstadisticaMesAnio").html('<canvas id="myChart5"></canvas>');
                    var meses = [];
                    var solicitudes = [];

                    for (var i in data) {
                        meses.push(data[i].Month);
                        solicitudes.push(data[i].Count);
                    }

                    var ctx = document.getElementById('myChart5').getContext('2d');
                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: meses,
                            datasets: [{
                                label: 'Cantidad solicitudes por mes-año',
                                data: solicitudes,
                                backgroundColor: getRandomColor()
                                // Otros parámetros para configurar el gráfico
                            }]
                        }
                    });

                }
            });
        });

        $(document).on("click",".btnVerEstadisticaTotalesRC",function(){
            $("#divEstadisticaTotalesRC").fadeIn();
            $.ajax({
                url: '{{ route("estadisticas.getCantidadSolicitudes.rc") }}',
                method: 'GET',
                dataType: 'json',
                beforeSend: function() {
                    $("#divEstadisticaTotalesRC").html('<div style="margin-left: auto;margin-right: auto;" class="loader"></div>');
                },
                success: function(data){
                    $("#divEstadisticaTotalesRC").html('<canvas id="myChart6"></canvas>');
                    var solicitudes = [];

                    for (var i in data) {
                        solicitudes.push(data[i].Count);
                    }

                    var ctx = document.getElementById('myChart6').getContext('2d');
                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['Cantidad'],
                            datasets: [{
                                label: 'Cantidad total de solicitudes a Registro Civil',
                                data: solicitudes,
                                backgroundColor: getRandomColor()
                                // Otros parámetros para configurar el gráfico
                            }]
                        }
                    });
                }

            });
        });


        function getRandomColor() {
            let r = Math.floor(Math.random() * 256);
            let g = Math.floor(Math.random() * 256);
            let b = Math.floor(Math.random() * 256);
            return 'rgba(' + r + ',' + g + ',' + b + ', 0.75)';  // Devuelve un color en formato rgba
        }
    </script>

