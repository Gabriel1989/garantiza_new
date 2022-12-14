@extends("themes.$themes.layout")

@section('contenido')

@include('includes.form-error-message')
<form method="post" action="{{route('solicitud.store')}}" role="form" class="form-horizontal form-solicitud">
    @csrf
    @method('POST')
    <div class="panel panel-info panel-border top">
        <div class="panel-heading">
            <span class="panel-title">Nueva Solicitud</span>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label for="name" class="col-lg-1 control-label">Ejecutivo</label>
                <label class="col-lg-5">
                    <p class="form-control-static text-muted">{{Auth::user()->name}}</p>
                </label>

                
                <label for="sucursal_id" class="col-lg-1 control-label">Sucursal</label>
                <div class="col-lg-5">
                    <select name="sucursal_id" id="sucursal_id">
                        <option value="0" selected>Seleccione Sucursal ...</option>
                        @foreach ($sucursales as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>    
                        @endforeach
                    </select>
                </div>

            </div>
            <div class="form-group">
                <label for="rut" class="col-lg-1 control-label">Rut del Cliente</label>
                <label class="col-lg-1">
                    <input type="text" name="rut" id="rut" class="form-control" placeholder="99.999.999-9" value="{{old('rut')}}">
                </label>
                
                <label for="name" class="col-lg-2 control-label">Nombre del Cliente</label>
                <label class="col-lg-5">
                    <input type="text" name="name" id="name" class="form-control" placeholder="Nombre del Cliente" value="{{old('name')}}">
                </label>

                <label class="col-lg-1 control-label">Cliente Empresa</label>
                <div class="col-lg-1">
                    <div class="switch switch-info switch-inline">
                        <input id="empresa" type="checkbox" id="empresa" name="empresa" value="1">
                        <label for="empresa"></label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="tipovehiculo_id" class="col-lg-1 control-label">Tipo de Vehículo</label>
                <div class="col-lg-5">
                    <select name="tipovehiculo_id" id="tipovehiculo_id">
                        <option value="0" selected>Seleccione Tipo de Vehículo ...</option>
                        @foreach ($tipo_vehiculos as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>    
                        @endforeach
                    </select>
                </div>

                <label for="tipotramite_id" class="col-lg-1 control-label">Tipo de Trámite</label>
                <div class="col-lg-5">
                    <select name="tipotramite_id" id="tipotramite_id" multiple="multiple">
                        @foreach ($tipo_tramites as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>    
                        @endforeach
                    </select>
                    <input type="hidden" name="tipotramite" id="tipotramite" value=""/>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel panel-default" id="info_primera" hidden>
                        <div class="panel-heading">
                            <span class="panel-title">Información de Primera Inscripción</span>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <div class="col-sm-2"><label>Terminación de PPU</label></div>
                                <div class="col-sm-2"></div>
                                <div class="col-sm-1"><label>Crédito Directo</label></div>
                                <div class="col-sm-2"></div>
                                <div class="col-sm-1"><label>Prenda</label></div>
                            </div>
                            <div class="col-sm-1">
                                <div class="form-group">
                                    <div class="col-sm-12 pl15">
                                        <div class="checkbox-custom  mb5">
                                            <input type="checkbox" id="ppu0" name="ppu" value="0">
                                            <label for="ppu0">0</label>
                                        </div>
                                        <div class="checkbox-custom  mb5">
                                            <input type="checkbox" id="ppu1" name="ppu" value="1">
                                            <label for="ppu1">1</label>
                                        </div>
                                        <div class="checkbox-custom  mb5">
                                            <input type="checkbox" id="ppu2" name="ppu" value="2">
                                            <label for="ppu2">2</label>
                                        </div>
                                        <div class="checkbox-custom  mb5">
                                            <input type="checkbox" id="ppu3" name="ppu" value="3">
                                            <label for="ppu3">3</label>
                                        </div>
                                        <div class="checkbox-custom  cmb5">
                                            <input type="checkbox" id="ppu4" name="ppu" value="4">
                                            <label for="ppu4">4</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-1">
                                <div class="form-group">
                                    <div class="col-sm-12 pl15">
                                        <div class="checkbox-custom  mb5">
                                            <input type="checkbox" id="ppu5" name="ppu" value="5">
                                            <label for="ppu5">5</label>
                                        </div>
                                        <div class="checkbox-custom  mb5">
                                            <input type="checkbox" id="ppu6" name="ppu" value="5">
                                            <label for="ppu6">6</label>
                                        </div>
                                        <div class="checkbox-custom  mb5">
                                            <input type="checkbox" id="ppu7" name="ppu" value="7">
                                            <label for="ppu7">7</label>
                                        </div>
                                        <div class="checkbox-custom  mb5">
                                            <input type="checkbox" id="ppu8" name="ppu" value="8">
                                            <label for="ppu8">8</label>
                                        </div>
                                        <div class="checkbox-custom  mb5">
                                            <input type="checkbox" id="ppu9" name="ppu" value="9">
                                            <label for="ppu9">9</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="sel_ppu" id="sel_ppu"/>
                            <div class="col-sm-2"></div>

                            <div class="col-sm-1">
                                <div class="form-group">
                                    <div class="col-sm-12 pl15">
                                        <div class="radio-custom  mb5">
                                            <input type="radio" id="credito_si" name="credito" value="1">
                                            <label for="credito_si">Si</label>
                                        </div>
                                        <div class="radio-custom  mb5" >
                                            <input type="radio" id="credito_no" name="credito" value="0" checked>
                                            <label for="credito_no">No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-2"></div>
                            
                            <div class="col-sm-1">
                                <div class="form-group">
                                    <div class="col-sm-12 pl15">
                                        <div class="radio-custom  mb5">
                                            <input type="radio" id="prenda_si" name="prenda" value="1">
                                            <label for="prenda_si">Si</label>
                                        </div>
                                        <div class="radio-custom  mb5" >
                                            <input type="radio" id="prenda_no" name="prenda" value="0" checked>
                                            <label for="prenda_no">No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <span class="panel-title">
                                <span class="glyphicon glyphicon-cog hidden"></span>Información de Compra/Para
                            </span>
                            <div class="widget-menu pull-right mr10 mt10">
                                <div class="switch switch-purple2 switch-inline switch-xs">
                                    <input id="switchCompraPara" name="switchCompraPara" type="checkbox" value="1">
                                    <label for="switchCompraPara"></label>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body" >
                            <div class="form-group">
                                <label for="rut" class="col-lg-1 control-label">Rut </label>
                                <label class="col-lg-1">
                                    <input type="text" name="rut_para" id="rut_para" class="form-control" placeholder="99.999.999-9" value="{{old('rut_para')}}" disabled>
                                </label>
                                
                                <label for="name" class="col-lg-2 control-label">Nombre </label>
                                <label class="col-lg-5">
                                    <input type="text" name="name_para" id="name_para" class="form-control" placeholder="Nombre" value="{{old('name_para')}}" disabled>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-lg-1 control-label">Domicilio </label>
                                <label class="col-lg-8">
                                    <input type="text" name="domicilio_para" id="domicilio_para" class="form-control" placeholder="Domicilio" value="{{old('domicilio_para')}}" disabled>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="rut" class="col-lg-1 control-label">Observación</label>
                <label class="col-lg-11">
                    <textarea class="form-control textarea-grow" id="comment" name="comment" value=""></textarea>
                    <span class="help-block mt5"> Espacio para realizar todos los comentarios necesarios.</span>
                </label>

            </div>
            
        </div>
        <div class="panel-footer">
            <button type="submit" class="btn btn-system"><li class="fa fa-save"></li>  Grabar y Continuar con Solicitud </button>
        </div>
    </div>
</form>

@endsection

@section('scripts')
<script src="/js/jquery.rut.min.js"></script>

<script type="text/javascript">
    jQuery(document).ready(function() {
        $('#sucursal_id').multiselect();
        $('#tipovehiculo_id').multiselect();
        $('#tipotramite_id').multiselect();

        $('#tipotramite_id').on('change', function(e) {
            $('#info_primera').hide();
            var xsel = '';
            if ($('#tipotramite_id').val() != null){
                var sel = $('#tipotramite_id').val();
                for (i = 0; i < sel.length; ++i) {
                    xsel = xsel.concat(sel[i],',');
                    if (sel[i]==1){
                        $('#info_primera').show();
                    }
                }
            }
            $('#tipotramite').val(xsel);
        });

        $("input[name='ppu']").on('click', function(e) {
            var xsel = '';
            i=0;
            $("input[name='ppu']").each(function(index) {
                if(this.checked){
                    i++;
                    xsel = xsel.concat($(this).val(),',');
                }
            });
            if(i>=3){
                $("input[name='ppu']").each(function(index) {
                    if(!this.checked){
                        $(this).prop('disabled', true);
                    }
                });   
            }else{
                $("input[name='ppu']").each(function(index) {
                    $(this).prop('disabled', false);
                }); 
            }
            $('#sel_ppu').val(xsel);
        });

        $('#switchCompraPara').on('change', function(e) {
            if ($('#switchCompraPara').prop('checked')==true){
                $('#rut_para').prop('disabled', false);
                $('#name_para').prop('disabled', false);
                $('#domicilio_para').prop('disabled', false);
            }else{
                $('#rut_para').prop('disabled', true);
                $('#name_para').prop('disabled', true);
                $('#domicilio_para').prop('disabled', true)
            }
        });

        $("input#rut").rut({
            formatOn: 'keyup',
            minimumLength: 8, 
            validateOn: 'change' 
        });

        $("input#rut").rut().on('rutInvalido', function(e) {
            new PNotify({
                title: 'Rut del Cliente',
                text: 'El Rut ingresado no es válido.',
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
        });

        $("input#rut_para").rut({
            formatOn: 'keyup',
            minimumLength: 8, 
            validateOn: 'change' 
        });

        $("input#rut_para").rut().on('rutInvalido', function(e) {
            new PNotify({
                title: 'Rut del Para',
                text: 'El Rut ingresado no es válido.',
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
        });

        $(".form-solicitud").on('submit', function () {
            var tipos = $('#tipotramite').val();
            var arr = tipos.split(','); 
            for(var i = 0; i < arr.length; i++){
                if(arr[i]=='1'){
                    var arrx = $('#sel_ppu').val().split(','); 
                    if(arrx.length!=4){
                        new PNotify({
                            title: 'Selección de PPU',
                            text: 'Debe seleccionar 3 PPU.',
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
                    }
                }
            }
        });
    });
</script>
@endsection