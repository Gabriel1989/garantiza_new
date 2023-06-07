@extends("themes.$themes.layout")

@section('styles')
@endsection

@section('contenido')
@include('includes.form-error-message')
@include('includes.mensaje')

<div class="panel panel-info panel-border top">
    <div class="panel-body">
        <ul class="nav nav-pills mb-3" id="transferencia-tab" role="tablist">
            <li class="nav-item @if($solicita_data == false)  active @endif" role="presentation">
                <a class="nav-link @if($solicita_data == false)  active @endif" id="pills-consultappu-tab" data-toggle="pill" href="#pills-consultappu" role="tab" aria-controls="pills-consultappu" aria-selected="true">Ingresar PPU a consultar</a>
            </li>

            <li class="nav item @if($solicita_data == true && $id_transferencia == 0)  active @endif" role="presentation">
                <a class="nav-link @if($solicita_data == false)  disabled @endif" id="pills-datavehiculo-tab" data-toggle="pill" @if($solicita_data == false) href="#" @else  href="#pills-datavehiculo" @endif role="tab" aria-controls="pills-datavehiculo" aria-selected="true">Crear solicitud de transferencia</a>
            </li>

            <li class="nav-item @if($id_transferencia != 0 && $id_comprador == 0 && $id_transferencia_rc == 0)  active    @endif" role="presentation">
                <a class="nav-link @if($id_transferencia == 0)  disabled    @endif" id="pills-comprador-tab" data-toggle="pill" @if($id_transferencia != 0) href="#pills-comprador" @else href="#" @endif role="tab" aria-controls="pills-comprador" aria-selected="false" @if($id_transferencia == 0) aria-disabled="true" @endif>Comprador</a>
            </li>

            <li class="nav-item @if($id_transferencia != 0 && $id_comprador != 0 && $id_transferencia_rc == 0 && $id_vendedor == 0)  active    @endif" role="presentation">
                <a class="nav-link @if($id_transferencia == 0 && $id_comprador == 0)  disabled    @endif" id="pills-vendedor-tab" data-toggle="pill" @if($id_transferencia != 0 && $id_comprador != 0) href="#pills-vendedor" @else href="#" @endif role="tab" aria-controls="pills-vendedor" aria-selected="false" @if($id_transferencia == 0 && $id_comprador == 0) aria-disabled="true" @endif>Vendedor</a>
            </li>

            <li class="nav-item @if($id_transferencia != 0 && $id_comprador != 0 && $id_vendedor != 0 && $id_transferencia_rc == 0 && $id_estipulante == 0)  active    @endif" role="presentation">
                <a class="nav-link @if($id_transferencia == 0 && $id_comprador == 0 && $id_vendedor == 0)  disabled    @endif" id="pills-estipulante-tab" data-toggle="pill" @if($id_transferencia != 0 && $id_comprador != 0 && $id_vendedor != 0) href="#pills-estipulante" @else href="#" @endif role="tab" aria-controls="pills-estipulante" aria-selected="false" @if($id_transferencia == 0 && $id_comprador == 0 && $id_vendedor == 0) aria-disabled="true" @endif>Estipulante</a>
            </li>

            <li class="nav-item @if($id_transferencia != 0 && $id_comprador != 0 && $id_vendedor != 0 && $id_transferencia_rc == 0 && $id_estipulante != 0)  active    @endif" role="presentation">
                <a class="nav-link @if($id_transferencia == 0 && $id_comprador == 0 && $id_vendedor == 0 && $id_transferencia_rc == 0 && $id_estipulante == 0)  disabled    @endif" id="pills-invoice-tab" data-toggle="pill" @if($id_transferencia != 0 && $id_comprador != 0 && $id_vendedor != 0 && $id_estipulante != 0) href="#pills-invoice" @else href="#" @endif role="tab" aria-controls="pills-invoice" aria-selected="false" @if($id_transferencia == 0 && $id_comprador == 0 && $id_vendedor == 0 && $id_estipulante == 0) aria-disabled="true" @endif>Datos adicionales</a>
            </li>

        </ul>
        <div class="tab-pane fade @if($solicita_data == false)  active show in @endif" id="pills-consultappu" role="tabpanel" aria-labelledby="pills-consultappu-tab">
            @include('transferencia.consultarPPU')
        </div>
        <div class="tab-pane fade @if($solicita_data == true && $id_transferencia == 0)  active show in @endif" id="pills-datavehiculo" role="tabpanel" aria-labelledby="pills-datavehiculo-tab">
            @if($solicita_data == true)
                @include('transferencia.dataVehiculo')
            @endif
        </div>
        <div class="tab-pane fade @if($id_transferencia != 0 && $id_comprador == 0 && $id_transferencia_rc == 0) show  active in @endif" id="pills-comprador" role="tabpanel" aria-labelledby="pills-comprador-tab">
            @if($id_transferencia != 0)
                @include('transferencia.comprador')
            @endif
        </div>

        <div class="tab-pane fade @if($id_transferencia != 0 && $id_comprador != 0 && $id_transferencia_rc == 0 && $id_vendedor == 0) show  active in @endif" id="pills-vendedor" role="tabpanel" aria-labelledby="pills-vendedor-tab">
            @if($id_transferencia != 0 && $id_comprador != 0)
                @include('transferencia.vendedor')
            @endif
        </div>

        <div class="tab-pane fade @if($id_transferencia != 0 && $id_comprador != 0 && $id_vendedor != 0 && $id_transferencia_rc == 0 && $id_estipulante == 0) show  active in @endif" id="pills-estipulante" role="tabpanel" aria-labelledby="pills-estipulante-tab">
            @if($id_transferencia != 0 && $id_comprador != 0 && $id_vendedor != 0)
                @include('transferencia.estipulante')
            @endif
        </div>

        <div class="tab-pane fade @if($id_transferencia != 0 && $id_comprador != 0 && $id_vendedor != 0 && $id_transferencia_rc == 0 && $id_estipulante != 0) show  active in @endif" id="pills-invoice" role="tabpanel" aria-labelledby="pills-invoice-tab">
            @if($id_transferencia != 0 && $id_comprador != 0 && $id_vendedor != 0 && $id_transferencia_rc == 0 && $id_estipulante != 0)
                @include('transferencia.dataResumen')
            @endif
        </div>

    </div>

</div>

@endsection


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script>

$(document).ready(function(){
    
    if($("#pills-comprador").hasClass('show')){
        $("#pills-consultappu").addClass('hide');
        $("#pills-datavehiculo").addClass('hide');
        $("#pills-vendedor").addClass('hide');
        $("#pills-estipulante").addClass('hide');
        $("#pills-invoice").addClass('hide');
    }

    if($("#pills-vendedor").hasClass('show')){
        $("#pills-consultappu").addClass('hide');
        $("#pills-datavehiculo").addClass('hide');
        $("#pills-comprador").addClass('hide');
        $("#pills-estipulante").addClass('hide');
        $("#pills-invoice").addClass('hide');
    }

    if($("#pills-estipulante").hasClass('show')){
        $("#pills-consultappu").addClass('hide');
        $("#pills-datavehiculo").addClass('hide');
        $("#pills-comprador").addClass('hide');
        $("#pills-vendedor").addClass('hide');
        $("#pills-invoice").addClass('hide');
    }

    if($("#pills-invoice").hasClass('show')){
        $("#pills-consultappu").addClass('hide');
        $("#pills-datavehiculo").addClass('hide');
        $("#pills-comprador").addClass('hide');
        $("#pills-vendedor").addClass('hide');
        $("#pills-estipulante").addClass('hide');
    }
});




$(document).on("click","#pills-datavehiculo-tab",function(e){
    //Ocultamos las demás pestañas
    $("#pills-consultappu").removeClass('show');
    $("#pills-consultappu").addClass('hide');

    $("#pills-comprador").removeClass('show');
    $("#pills-comprador").addClass('hide');

    $("#pills-vendedor").removeClass('show');
    $("#pills-vendedor").addClass('hide');

    $("#pills-estipulante").removeClass('show');
    $("#pills-estipulante").addClass('hide');

    $("#pills-invoice").removeClass('show');
    $("#pills-invoice").addClass('hide');

    //Quitamos hide a nav consultada
    $("#pills-datavehiculo").removeClass('hide');
});

$(document).on("click","#pills-consultappu-tab",function(e){
    //Ocultar las demás pestañas
    $("#pills-datavehiculo").removeClass('show');
    $("#pills-datavehiculo").addClass('hide');

    $("#pills-comprador").removeClass('show');
    $("#pills-comprador").addClass('hide');

    $("#pills-vendedor").removeClass('show');
    $("#pills-vendedor").addClass('hide');

    $("#pills-estipulante").removeClass('show');
    $("#pills-estipulante").addClass('hide');

    $("#pills-invoice").removeClass('show');
    $("#pills-invoice").addClass('hide');

    //Quitamos hide a nav consultada
    $("#pills-consultappu").removeClass('hide');
});

$(document).on("click","#pills-comprador-tab",function(e){
    //Ocultar las demás pestañas
    $("#pills-datavehiculo").removeClass('show');
    $("#pills-datavehiculo").addClass('hide');

    $("#pills-consultappu").removeClass('show');
    $("#pills-consultappu").addClass('hide');

    $("#pills-vendedor").removeClass('show');
    $("#pills-vendedor").addClass('hide');

    $("#pills-estipulante").removeClass('show');
    $("#pills-estipulante").addClass('hide');

    $("#pills-invoice").removeClass('show');
    $("#pills-invoice").addClass('hide');
    //Quitamos hide a nav consultada
    $("#pills-comprador").removeClass('hide');
});

$(document).on("click","#pills-vendedor-tab",function(e){
    //Ocultar las demás pestañas
    $("#pills-datavehiculo").removeClass('show');
    $("#pills-datavehiculo").addClass('hide');

    $("#pills-consultappu").removeClass('show');
    $("#pills-consultappu").addClass('hide');

    $("#pills-comprador").removeClass('show');
    $("#pills-comprador").addClass('hide');

    $("#pills-estipulante").removeClass('show');
    $("#pills-estipulante").addClass('hide');

    $("#pills-invoice").removeClass('show');
    $("#pills-invoice").addClass('hide');
    //Quitamos hide a nav consultada
    $("#pills-vendedor").removeClass('hide');
});

$(document).on("click","#pills-estipulante-tab",function(e){
    //Ocultar las demás pestañas
    $("#pills-datavehiculo").removeClass('show');
    $("#pills-datavehiculo").addClass('hide');

    $("#pills-consultappu").removeClass('show');
    $("#pills-consultappu").addClass('hide');

    $("#pills-comprador").removeClass('show');
    $("#pills-comprador").addClass('hide');

    $("#pills-vendedor").removeClass('show');
    $("#pills-vendedor").addClass('hide');

    $("#pills-invoice").removeClass('show');
    $("#pills-invoice").addClass('hide');
    //Quitamos hide a nav consultada
    $("#pills-estipulante").removeClass('hide');
});

$(document).on("click","#pills-invoice-tab",function(e){
    //Ocultar las demás pestañas
    $("#pills-datavehiculo").removeClass('show');
    $("#pills-datavehiculo").addClass('hide');

    $("#pills-consultappu").removeClass('show');
    $("#pills-consultappu").addClass('hide');

    $("#pills-comprador").removeClass('show');
    $("#pills-comprador").addClass('hide');

    $("#pills-vendedor").removeClass('show');
    $("#pills-vendedor").addClass('hide');

    $("#pills-estipulante").removeClass('show');
    $("#pills-estipulante").addClass('hide');
    //Quitamos hide a nav consultada
    $("#pills-invoice").removeClass('hide');
});
</script>