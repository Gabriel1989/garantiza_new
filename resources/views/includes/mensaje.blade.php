@if (session('mensaje'))
    <div class="alert alert-sm alert-border-left alert-success light alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <i class="fa fa-check pr10"></i>
        <strong>Mensaje del Sistema Garantiza</strong> <br><br>
        <ul>
            <li>{{session('mensaje')}}</li>
        </ul>
    </div>    
@endif