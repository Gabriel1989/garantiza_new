@if ($errors->any())
    <div class="alert alert-sm alert-border-left alert-danger light alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <i class="fa fa-remove pr10"></i>
        <strong>El formulario contiene errores:</strong> <br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>
    </div>    
@endif