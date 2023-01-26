
<section id="content_error" class="animated fadeIn error-page sb-l-o sb-r-c">
<div class="center-block mt50 mw800 animated fadeIn">
    <h1 class="error-title"> Error 500 </h1>
    <h2 class="error-subtitle">{{$glosa}}</h2>
</div>

<div class="mid-section animated fadeIn">
    <div class="mid-content clearfix">
        <div class="pull-left">
            <blockquote class="blockquote-primary">
                <p> 
                    <ol>
                        <li>Verifique si está conectado a la red del Registro Civil.</li> 
                        <li>Presione F5 para refrescar la pantalla.</li> 
                        <li>En caso de continuar con el problema, comuníquese con el administrador del Sistema.</li> 
                    </ol>
                </p>
            </blockquote>
        </div>
        

    </div>
</div>
</section>


@section('scripts')
<script type="text/javascript">
    jQuery(document).ready(function() {
        $('#content_error').addClass('error-page sb-l-o sb-r-c')
    });
</script>
@endsection