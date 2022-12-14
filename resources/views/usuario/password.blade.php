@extends("themes.$themes.layout")

@section('contenido')

@include('includes.form-error-message')
<form method="POST" action="{{ route('password.update') }}" role="form" class="form-horizontal form-solicitud">
    @csrf

    <input type="hidden" name="token" value="{{ $usuario->remember_token }}">
    <input type="hidden" name="email" value="{{ $usuario->email }}">
    <div class="panel panel-info panel-border top">
        <div class="panel-heading">
            <span class="panel-title">Cambio de Password</span>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label for="name" class="col-lg-2 control-label mt10">Usuario : </label>
                <label class="col-lg-5">
                    <p class="form-control-static text-muted" id="name">{{$usuario->name}}</p>
                </label>
                <div class="col-lg-12"></div>
            </div>
            {{-- <div class="form-group">
                <label for="old_pass" class="col-lg-2 control-label mt10">Password Actual :</label>
                <label class="col-lg-3">
                    <input type="password" name="old_pass" id="old_pass" class="form-control" value="">
                </label>
                <div class="col-lg-12"></div>
            </div> --}}
            <div class="form-group">
                <label for="password" class="col-lg-2 control-label mt10">Nueva Password :</label>
                <label class="col-lg-3">
                    <input type="password" name="password" id="password" class="form-control" value="">
                </label>
                <div class="col-lg-12"></div>
            </div>
            <div class="form-group">
                <label for="password-confirm" class="col-lg-2 control-label mt10">Reingresar Nueva Password :</label>
                <label class="col-lg-3">
                    <input type="password" name="password-confirm" id="password-confirm" class="form-control" value="">
                </label>
                <div class="col-lg-12"></div>
            </div>
        </div>
        </div>
        <div class="panel-footer">
            <button id="btn_cancelar" type="button" class="btn btn-sm btn-default" onclick="location.href='{{route('usuario.index')}}'"> Cancelar </button>
            <button type="submit" class="btn btn-sm btn-system"> Grabar </button>
        </div>
    </div>
</form>

@endsection

