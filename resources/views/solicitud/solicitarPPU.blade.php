

@section('styles')
@endsection


@include('includes.form-error-message')
@include('includes.mensaje')
@php
use App\Models\TipoPlacaPatente;
$tipo_placa_patente = TipoPlacaPatente::all();
@endphp
<form enctype="multipart/form-data" id="formSolicitaPPU" class="form-documentos form-horizontal form-solicitud" old-action="{{route('solicitud.consultaPPU')}}" method="POST">
    @csrf
    @method('POST')
    <div class="panel panel-info panel-border top">
        <div class="panel-heading">
            <span class="panel-title">Solicitar nueva PPU</span>
            <span class="panel-title" style="color:#f00">(*) Datos obligatorios</span>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label for="name" class="col-lg-1"><span class="panel-title" style="color:#f00">(*) </span>Región: </label>
                <label class="col-lg-5">
                    <select name="region" id="region">
                        <option value="0" selected>Seleccione Región ...</option>
                        @foreach ($region as $item)
                        <option value="{{$item->id}}"@if(!empty($solicitud_data)) @if($item->id==$solicitud_data->region_id) selected  @endif @endif>{{$item->nombre}}</option>    
                        @endforeach
                    </select>
                </label>

                
                <label for="placa_patente_id" class="col-lg-1"><span class="panel-title" style="color:#f00">(*) </span>Tipo Placa Patente: </label>
                <div class="col-lg-5">
                    <select name="placa_patente_id" id="placa_patente_id">
                        <option value="0" selected>Seleccione Tipo Placa ...</option>
                        @foreach ($tipo_placa_patente as $item)
                            <option value="{{$item->codigo}}" @if(!empty($solicitud_data)) @if($item->id==$solicitud_data->id_tipo_placa_patente) selected @endif @endif>{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                
                <div class="col-lg-5">
                    <input type="hidden" id="nombre_institucion" name="nombre_institucion" value="ACOBRO">
                </div>

            </div>
            
        </div>

    <div class="panel-footer">
        <button type="submit" class="btn btn-system"> <li class="fa fa-upload"></li> Consultar PPU y Continuar</button>
    </div>
</div>
</form>

@section('scripts')
<script>
    $(document).on("submit","#formSolicitaPPU",function(e){
        showOverlay();
        e.preventDefault();

        let formData = new FormData(document.getElementById("formSolicitaPPU"));

        $.ajax({
            data: formData,
            processData: false,
            contentType: false,
            type: "post",
            url: "{{route('solicitud.consultaPPU')}}",
            success: function(data){
                hideOverlay();
                $("#pills-home").html(data);
                $("#pills-home").toggleClass('show');
                $("#pills-ppu").removeClass('show');
                $("#pills-home-tab").attr("href","#pills-home");
                $("#pills-home-tab").toggleClass('disabled');
                $("#pills-home-tab").attr("aria-disabled",false);
                $("#pills-home-tab").click();
            }
        })

    });

</script>
@endsection