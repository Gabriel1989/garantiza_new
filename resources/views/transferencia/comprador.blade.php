
<form enctype="multipart/form-data" id="form-comprador" method="POST">
    @csrf
    @method('POST')
    <div class="panel panel-info panel-border top">
        <div class="panel-heading">
            <span class="panel-title">Ingreso de Solicitud Transferencia N° {{$id}} - Datos del Comprador</span>
        </div>
        <div class="panel-body">
            <div id="adquiriente">
                <div class="form-group">
                    <label for="rut" class="col-lg-2 control-label">Rut :</label>
                    <label class="col-lg-2">
                        <input type="hidden" name="adquiriente_1" id="adquiriente_1" value="{{ isset($comprador[0]->id)? $comprador[0]->id :  0}}">
                        <input type="text" name="rut" id="rut" class="form-control rut" placeholder="99.999.999-9" value="{{ isset($comprador[0]->rut)? str_replace(".","",explode("-",$comprador[0]->rut)[0]) : ''}}" required>
                    </label>
                    <label class="col-lg-2"></label>
                    
                    <label for="nombre" class="col-lg-2 control-label">Nombre :</label>
                    <label class="col-lg-4">
                        <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre del Adquirente" value="{{ isset($comprador[0]->nombre)? $comprador[0]->nombre :  ''}}" required>
                    </label>
                </div>
                <div class="form-group">
                    <label for="aPaterno" class="col-lg-2 control-label">Apellido Paterno :</label>
                    <label class="col-lg-4">
                        <input type="text" name="aPaterno" id="aPaterno" class="form-control" placeholder="Apellido Paterno" value="{{ isset($comprador[0]->aPaterno)? $comprador[0]->aPaterno: old('aPaterno')}}">
                    </label>
                    
                    <label for="aMaterno" class="col-lg-2 control-label">Apellido Materno :</label>
                    <label class="col-lg-4">
                        <input type="text" name="aMaterno" id="aMaterno" class="form-control" placeholder="Apellido Materno" value="{{ isset($comprador[0]->aMaterno)? $comprador[0]->aMaterno : old('aMaterno')}}">
                    </label>
                </div>
                <div class="form-group">
                    <label for="calle" class="col-lg-2 control-label">Dirección (calle) :</label>
                    <label class="col-lg-4">
                        <input type="text" name="calle" id="calle" class="form-control" placeholder="Calle de la dirección" value="{{ isset($comprador[0]->calle)?  $comprador[0]->calle : ''}}" required>
                    </label>
                    
                    <label for="numero" class="col-lg-2 control-label">Número :</label>
                    <label class="col-lg-2">
                        <input type="text" name="numero" id="numero" class="form-control" placeholder="Número de la dirección" value="{{ isset($comprador[0]->numero)? $comprador[0]->numero : old('numero')}}" required>
                    </label>
                    <label class="col-lg-2"></label>
                </div>
                <div class="form-group">
                    <label for="rDireccion" class="col-lg-2 control-label">Complemento de dirección :</label>
                    <label class="col-lg-4">
                        <input type="text" name="rDireccion" id="rDireccion" class="form-control" placeholder="Complemento de la dirección" value="{{ isset($comprador[0]->rDomicilio)? $comprador[0]->rDomicilio : old('rDireccion') }}">
                    </label>
                    
                    <label for="comuna" class="col-lg-2 control-label">Comuna :</label>
                    <label class="col-lg-4">
                        <select class="col-sm-12 form-select comuna" name="comuna" id="comuna" >
                            <option value="0" selected>Seleccione Comuna...</option>
                            @foreach ($comunas as $item)
                                <option value="{{$item->Codigo}}" 
                                    @if(isset($comprador[0]->comuna))   
                                        @if ($comprador[0]->comuna==$item->id) selected @endif   
                                    @endif>{{$item->Nombre}}</option>    
                            @endforeach
                        </select>
                    </label>
                </div>
                <div class="form-group">
                    <label for="email" class="col-lg-2 control-label">Email :</label>
                    <label class="col-lg-4">
                        <input type="email" name="email" id="email" class="form-control" placeholder="Email" value="{{ isset($comprador[0]->email)? $comprador[0]->email : '' }}" required>
                    </label>
                    
                    <label for="telefono" class="col-lg-2 control-label">Teléfono :</label>
                    <label class="col-lg-2">
                        <input type="text" name="telefono" id="telefono" class="form-control" placeholder="Ej. 978653214" value="{{  isset($comprador[0]->telefono)? $comprador[0]->telefono : ''}}" required>
                    </label>
                    <label class="col-lg-2"></label>
                </div>
                <div class="form-group">
                    <label for="tipoPersona" class="col-lg-2 control-label">Tipo de Persona :</label>
                    <label class="col-lg-4">
                        <select class="col-sm-12 form-select" name="tipoPersona" id="tipoPersona" >
                            @if(!isset($comprador[0]->tipo))
                                <option value="N" selected>NATURAL</option>
                                <option value="J">JURÍDICO</option>
                                <option value="E">EXTRANJERO</option>
                                <option value="O">COMUNIDAD</option>
                            @else
                                <option data-tipo="N" value="N" @if ($comprador[0]->tipo=='N') selected @endif>NATURAL</option>
                                <option data-tipo="J" value="J" @if ($comprador[0]->tipo=='J') selected @endif>JURÍDICO</option>
                                <option data-tipo="E" value="E" @if ($comprador[0]->tipo=='E') selected @endif>EXTRANJERO</option>
                                <option data-tipo="O" value="O" @if ($comprador[0]->tipo=='O') selected @endif>COMUNIDAD</option>
                            @endif
                        </select>
                    </label>
                </div>
                <hr>
            </div>
            <div id="adquiriente2" style="display: none">
                <h5> <li class="fa fa-minus-square"  style="cursor: pointer" onclick="elimina(2)"></li> Segundo Adquirente</h5>
                <div class="form-group">
                    
                    <label for="rut2" class="col-lg-2 control-label">Rut :</label>
                    <label class="col-lg-2">
                        <input type="hidden" name="adquiriente_2" id="adquiriente_2" value="{{ isset($comprador[1]->id)? $comprador[1]->id :  0}}">
                        <input type="text" name="rut2" id="rut2" class="form-control rut" placeholder="99.999.999-9" value="{{old('rut2')}}">
                    </label>
                    <label class="col-lg-2"></label>
                    
                    <label for="nombre2" class="col-lg-2 control-label">Nombre :</label>
                    <label class="col-lg-4">
                        <input type="text" name="nombre2" id="nombre2" class="form-control" placeholder="Nombre del Adquirente" value="{{old('nombre2')}}">
                    </label>
                </div>
                <div class="form-group">
                    <label for="aPaterno2" class="col-lg-2 control-label">Apellido Paterno :</label>
                    <label class="col-lg-4">
                        <input type="text" name="aPaterno2" id="aPaterno2" class="form-control" placeholder="Apellido Paterno" value="{{old('aPaterno2')}}">
                    </label>
                    
                    <label for="aMaterno2" class="col-lg-2 control-label">Apellido Materno :</label>
                    <label class="col-lg-4">
                        <input type="text" name="aMaterno2" id="aMaterno2" class="form-control" placeholder="Apellido Materno" value="{{old('aMaterno2')}}">
                    </label>
                </div>
                <div class="form-group">
                    <label for="calle2" class="col-lg-2 control-label">Dirección (calle) :</label>
                    <label class="col-lg-4">
                        <input type="text" name="calle2" id="calle2" class="form-control" placeholder="Calle de la dirección" value="{{old('calle2')}}">
                    </label>
                    
                    <label for="numero2" class="col-lg-2 control-label">Número :</label>
                    <label class="col-lg-2">
                        <input type="text" name="numero2" id="numero2" class="form-control" placeholder="Número de la dirección" value="{{old('numero2')}}">
                    </label>
                    <label class="col-lg-2"></label>
                </div>
                <div class="form-group">
                    <label for="rDireccion2" class="col-lg-2 control-label">Complemento de dirección :</label>
                    <label class="col-lg-4">
                        <input type="text" name="rDireccion2" id="rDireccion2" class="form-control" placeholder="Complemento de la dirección" value="{{old('rDireccion2')}}">
                    </label>
                    
                    <label for="comuna2" class="col-lg-2 control-label">Comuna :</label>
                    <label class="col-lg-4">
                        <select class="col-sm-12 form-select comuna" name="comuna2" id="comuna2" >
                            <option value="0" selected>Seleccione Comuna...</option>
                            @foreach ($comunas as $item)
                                <option value="{{$item->Codigo}}">{{$item->Nombre}}</option>    
                            @endforeach
                        </select>
                    </label>
                </div>
                <div class="form-group">
                    <label for="email2" class="col-lg-2 control-label">Email :</label>
                    <label class="col-lg-4">
                        <input type="email" name="email2" id="email2" class="form-control" placeholder="Email" value="{{old('email2')}}">
                    </label>
                    
                    <label for="telefono2" class="col-lg-2 control-label">Teléfono :</label>
                    <label class="col-lg-2">
                        <input type="text" name="telefono2" id="telefono2" class="form-control" placeholder="Ej. 978653214" value="{{old('telefono2')}}">
                    </label>
                    <label class="col-lg-2"></label>
                </div>
                <hr>
            </div>
            <div id="adquiriente3" style="display: none">
                <h5> <li class="fa fa-minus-square" style="cursor: pointer" onclick="elimina(3)"></li> Tercer Adquirente</h5>
                <div class="form-group">
                    <label for="rut3" class="col-lg-2 control-label">Rut :</label>
                    <label class="col-lg-2">
                        <input type="hidden" name="adquiriente_3" id="adquiriente_3" value="{{ isset($comprador[2]->id)? $comprador[2]->id :  0}}">
                        <input type="text" name="rut3" id="rut3" class="form-control rut" placeholder="99.999.999-9" value="{{old('rut3')}}">
                    </label>
                    <label class="col-lg-2"></label>
                    
                    <label for="nombre3" class="col-lg-2 control-label">Nombre :</label>
                    <label class="col-lg-4">
                        <input type="text" name="nombre3" id="nombre3" class="form-control" placeholder="Nombre del Adquirente" value="{{old('nombre3')}}">
                    </label>
                </div>
                <div class="form-group">
                    <label for="aPaterno3" class="col-lg-2 control-label">Apellido Paterno :</label>
                    <label class="col-lg-4">
                        <input type="text" name="aPaterno3" id="aPaterno3" class="form-control" placeholder="Apellido Paterno" value="{{old('aPaterno3')}}">
                    </label>
                    
                    <label for="aMaterno3" class="col-lg-2 control-label">Apellido Materno :</label>
                    <label class="col-lg-4">
                        <input type="text" name="aMaterno3" id="aMaterno3" class="form-control" placeholder="Apellido Materno" value="{{old('aMaterno3')}}">
                    </label>
                </div>
                <div class="form-group">
                    <label for="calle3" class="col-lg-2 control-label">Dirección (calle) :</label>
                    <label class="col-lg-4">
                        <input type="text" name="calle3" id="calle3" class="form-control" placeholder="Calle de la dirección" value="{{old('calle3')}}">
                    </label>
                    
                    <label for="numero3" class="col-lg-2 control-label">Número :</label>
                    <label class="col-lg-2">
                        <input type="text" name="numero3" id="numero3" class="form-control" placeholder="Número de la dirección" value="{{old('numero3')}}">
                    </label>
                    <label class="col-lg-2"></label>
                </div>
                <div class="form-group">
                    <label for="rDireccion3" class="col-lg-2 control-label">Complemento de dirección :</label>
                    <label class="col-lg-4">
                        <input type="text" name="rDireccion3" id="rDireccion3" class="form-control" placeholder="Complemento de la dirección" value="{{old('rDireccion3')}}">
                    </label>
                    
                    <label for="comuna3" class="col-lg-2 control-label">Comuna :</label>
                    <label class="col-lg-4">
                        <select class="col-sm-12 form-select comuna" name="comuna3" id="comuna3" >
                            <option value="0" selected>Seleccione Comuna...</option>
                            @foreach ($comunas as $item)
                                <option value="{{$item->Codigo}}">{{$item->Nombre}}</option>    
                            @endforeach
                        </select>
                    </label>
                </div>
                <div class="form-group">
                    <label for="email3" class="col-lg-2 control-label">Email :</label>
                    <label class="col-lg-4">
                        <input type="email" name="email3" id="email3" class="form-control" placeholder="Email" value="{{old('email3')}}">
                    </label>
                    
                    <label for="telefono2" class="col-lg-2 control-label">Teléfono :</label>
                    <label class="col-lg-3">
                        <input type="text" name="telefono3" id="telefono3" class="form-control" placeholder="Ej. 978653214" value="{{old('telefono3')}}">
                    </label>
                    <label class="col-lg-2"></label>
                </div>
                <hr>
            </div>
            <button type="button" id="agregar" name="agregar" class="btn btn-success" style="display: none"> <li class="fa fa-plus"></li> Agregar Adquirente</button>
        </div>
    <div class="panel-footer">
        <button type="submit" class="btn btn-system"> <li class="fa fa-save"></li> Grabar y Continuar</button>
    </div>
</div>
</form>