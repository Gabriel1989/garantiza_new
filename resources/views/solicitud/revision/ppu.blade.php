@extends("themes.$themes.layout")

@section('contenido')

@include('includes.form-error-message')
<form method="post" action="{{route('solicitud.updateRevisionPPU', ['id' => $id])}}" role="form" class="form-horizontal">
    @csrf
    @method('PUT')
    <div class="panel panel-info panel-border top">
        <div class="panel-heading">
            <span class="panel-title">Revisión de Solicitud N° {{$id}} - PPU Disponible</span>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label for="id" class="col-lg-2 control-label">PPU Seleccionadas</label>
                <label class="col-lg-1">
                    <input type="text" name="id" id="id" class="form-control" value="{{$solicitud_PPU[0]->termino_1 . ', ' . $solicitud_PPU[0]->termino_2 . ', ' . $solicitud_PPU[0]->termino_3}}" disabled>
                </label>
                
                <label for="ppu_terminacion" class="col-lg-2 control-label">PPU Disponibles</label>
                <label class="col-lg-4">
                    <select name="ppu_terminacion" id="ppu_terminacion">
                        <option value="" selected>Seleccione PPU ...</option>
                        @foreach ($ppu as $item)
                            <option value="{{$item['Terminacion']}}">{{$item['Terminacion']}}</option>    
                        @endforeach
                    </select>
                </label>
            </div>
            
        </div>
        <div class="panel-footer">
            <button type="submit" class="btn btn-sm btn-system"> Grabar y Continuar Revisión  </button>
        </div>
    </div>
</form>

@endsection

@section('scripts')
<script type="text/javascript">
    jQuery(document).ready(function() {
        $('#ppu_terminacion').multiselect();
    });
</script>
@endsection