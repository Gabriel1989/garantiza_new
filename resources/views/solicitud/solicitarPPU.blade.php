@extends("themes.$themes.layout")

@section('styles')
@endsection

@section('contenido')
@include('includes.form-error-message')
@include('includes.mensaje')
<form enctype="multipart/form-data" class="form-documentos form-horizontal form-solicitud" action="{{route('solicitud.consultaPPU')}}" method="POST">
    @csrf
    @method('POST')
    <div class="panel panel-info panel-border top">
        <div class="panel-heading">
            <span class="panel-title">Solicitar nueva PPU</span>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label for="name" class="col-lg-1">Región: </label>
                <label class="col-lg-5">
                    <select name="region" id="region">
                        <option value="0" selected>Seleccione Región ...</option>
                        @foreach ($region as $item)
                        <option value="{{$item->id}}">{{$item->nombre}}</option>    
                        @endforeach
                    </select>
                </label>

                
                <label for="placa_patente_id" class="col-lg-1">Tipo Placa Patente: </label>
                <div class="col-lg-5">
                    <select name="placa_patente_id" id="placa_patente_id">
                        <option value="0" selected>Seleccione Tipo Placa ...</option>
                        <option value="A">Vehículos livianos, medianos y pesados.</option>
                        <option value="M">Motos</option>
                        <option value="R">Remolques / Semi Remolque.</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="tipoVehiculos_id" class="col-lg-1">Nombre de Institución: </label>
                <div class="col-lg-5">
                    <input id="nombre_institucion" name="nombre_institucion" class="form-control">
                </div>

            </div>
            
        </div>

    <div class="panel-footer">
        <button type="submit" class="btn btn-system"> <li class="fa fa-upload"></li> Consultar PPU y Continuar</button>
    </div>
</div>
</form>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {

        $('#sucursal_id').multiselect();
        $('#tipoVehiculos_id').multiselect();

        $('#FacturaXML').on('click', function() {
            $('#Factura_XML').trigger('click');
        });

        $('#Factura_XML').on('change', function(){
            $('#lbl_Factura_XML').text($('#Factura_XML').val());
        });

    });

    $(".form-documentos").on('submit', function () {
        if($('#sucursal_id').val()=='0'){
            new PNotify({
                title: 'Crear Solicitud',
                text: 'Debe Seleccionar la Sucursal',
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
            return false;
        };     

        if($('#tipoVehiculos_id').val()=='0'){
            new PNotify({
                title: 'Crear Solicitud',
                text: 'Debe Seleccionar el Tipo de Vehículo',
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
            return false;
        };    

        if ($('#Factura_XML').val().length==0){
            new PNotify({
                title: 'Crear Solicitud',
                text: 'Debe adjuntar la Factura en formato XML',
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
            return false;
        };

        
    });

</script>
@endsection