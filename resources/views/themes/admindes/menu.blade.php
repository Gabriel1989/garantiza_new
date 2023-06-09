        <!-- sidebar menu -->
        <ul class="nav sidebar-menu">
            @if (Auth::user()->rol_id>=4 && Auth::user()->rol_id < 7)
            <li class="sidebar-label pt20">Menu</li>
            <li>
                <a href="{{route('solicitud.verSolicitudes')}}">
                    <span class="glyphicons glyphicons-inbox_in"></span>
                    <span class="sidebar-title">Mis Solicitudes </span>
                    <span class="sidebar-title-tray">
                        <span class="label label-xs bg-primary">{{App\Models\Solicitud::getCountFromUser(Auth::user()->id)[0]->cantidad}}</span>
                    </span>
                </a>
            </li>

            <li>
                <a href="{{route('solicitud.sinTerminar')}}">
                    <span class="fa fa-warning"></span>
                    <span class="sidebar-title">Solicitudes sin terminar </span>
                    <span class="sidebar-title-tray">
                        <span class="label label-xs bg-warning">
                            {{App\Models\Solicitud::getCountFromUnterminated(Auth::user()->id)[0]->cantidad}}
                        </span>
                    </span>
                </a>
            </li>
            
            <li>
                
                
                <a href="{{route('solicitud.solicitarPPU')}}">
                    <span class="glyphicons glyphicons-inbox_plus"></span>
                    <span class="sidebar-title">Nueva Solicitud </span>
                </a>

                <!--
                <a class="accordion-toggle 
                    @php
                        if (Route::is('solicitud.*')||
                            Route::is('transferencia.*')) {
                            echo 'menu-open';
                        }   
                    @endphp
                    " 
                    href="#">
                    <span class="glyphicons glyphicons-inbox_plus"></span>
                    <span class="sidebar-title">Nueva Solicitud</span>
                    <span class="caret"></span>
                </a>
                <ul class="nav sub-nav">
                    <li class="{{!Route::is('solicitud.*') ?: 'active'}}">
                        <a href="{{route('solicitud.solicitarPPU')}}">
                            <span class="glyphicons glyphicons-inbox_plus"></span> Primera inscripción </a>
                    </li>
                    <li class="{{!Route::is('transferencia.*') ?: 'active'}}">
                        <a href="{{route('transferencia.index')}}">
                            <span class="glyphicons glyphicons-car"></span> Transferencia </a>
                    </li>
                </ul>-->
            </li>
            @endif

            @if(Auth::user()->rol_id==7)
            <li class="sidebar-label pt20">Menu</li>
            
            <li>
                <a href="{{route('transferencia.verSolicitudes')}}">
                    <span class="glyphicons glyphicons-inbox_in"></span>
                    <span class="sidebar-title">Mis Solicitudes </span>
                    <span class="sidebar-title-tray">
                        <span class="label label-xs bg-primary">{{App\Models\Transferencia::getCountFromUser(Auth::user()->id)[0]->cantidad}}</span>
                    </span>
                </a>
            </li>

            <li>
                <a href="{{route('transferencia.sinTerminar')}}">
                    <span class="fa fa-warning"></span>
                    <span class="sidebar-title">Solicitudes sin terminar </span>
                    <span class="sidebar-title-tray">
                        <span class="label label-xs bg-warning">
                            {{App\Models\Transferencia::getCountFromUnterminated(Auth::user()->id)[0]->cantidad}}
                        </span>
                    </span>
                </a>
            </li>
            
            <li>
                
                
                <a href="{{route('transferencia.index')}}">
                    <span class="glyphicons glyphicons-inbox_plus"></span>
                    <span class="sidebar-title">Nueva Solicitud </span>
                </a>

                <!--
                <a class="accordion-toggle 
                    @php
                        if (Route::is('solicitud.*')||
                            Route::is('transferencia.*')) {
                            echo 'menu-open';
                        }   
                    @endphp
                    " 
                    href="#">
                    <span class="glyphicons glyphicons-inbox_plus"></span>
                    <span class="sidebar-title">Nueva Solicitud</span>
                    <span class="caret"></span>
                </a>
                <ul class="nav sub-nav">
                    <li class="{{!Route::is('solicitud.*') ?: 'active'}}">
                        <a href="{{route('solicitud.solicitarPPU')}}">
                            <span class="glyphicons glyphicons-inbox_plus"></span> Primera inscripción </a>
                    </li>
                    <li class="{{!Route::is('transferencia.*') ?: 'active'}}">
                        <a href="{{route('transferencia.index')}}">
                            <span class="glyphicons glyphicons-car"></span> Transferencia </a>
                    </li>
                </ul>-->
            </li>


            @endif

            @if (Auth::user()->rol_id==1)
            <li class="sidebar-label pt15">Configuración</li>
            <li>
                <a class="accordion-toggle 
                    @php
                        if (Route::is('concesionaria.*')||
                            Route::is('sucursal.*')||
                            Route::is('estado.*')||
                            Route::is('tipo_vehiculo.*')||
                            Route::is('tipo_tramite.*')||
                            Route::is('tipo_documento.*') ||
                            Route::is('acreedor.*') ||
                            Route::is('rechazo.*') ||
                            Route::is('notaria.*')) {
                            echo 'menu-open';
                        }   
                    @endphp
                    " 
                    href="#">
                    <span class="glyphicons glyphicons-settings"></span>
                    <span class="sidebar-title">Tablas Corporativas</span>
                    <span class="caret"></span>
                </a>
                <ul class="nav sub-nav">
                    <li class="{{!Route::is('concesionaria.*') ?: 'active'}}">
                        <a href="{{route('concesionaria.index')}}">
                            <span class="glyphicons glyphicons-vcard"></span> Concesionarias </a>
                    </li>
                    <li class="{{!Route::is('sucursal.*') ?: 'active'}}">
                        <a href="{{route('sucursal.index')}}">
                            <span class="glyphicons glyphicons-car"></span> Sucursales </a>
                    </li>
                    <li class="{{!Route::is('estado.*') ?: 'active'}}">
                        <a href="{{route('estado.index')}}">
                            <span class="glyphicons glyphicons-road"></span> Estados de Solicitud </a>
                    </li>
                    <li class="{{!Route::is('tipo_vehiculo.*') ?: 'active'}}">
                        <a href="{{route('tipo_vehiculo.index')}}">
                            <span class="glyphicons glyphicons-cars"></span> Tipo de Vehículos </a>
                    </li>
                    <li class="{{!Route::is('tipo_tramite.*') ?: 'active'}}">
                        <a href="{{route('tipo_tramite.index')}}">
                            <span class="glyphicons glyphicons-notes_2"></span> Tipo de Trámites </a>
                    </li>
                    <li class="{{!Route::is('tipo_documento.*') ?: 'active'}}">
                        <a href="{{route('tipo_documento.index')}}">
                            <span class="glyphicon glyphicon-file"></span> Tipo de Documentos </a>
                    </li>
                    <li  class="{{!Route::is('acreedor.*') ?: 'active'}}">
                        <a href="{{route('acreedor.index')}}">
                            <span class="glyphicon glyphicon-briefcase"></span> Acreedores </a>
                    </li>
                    <li  class="{{!Route::is('rechazo.*') ?: 'active'}}">
                        <a href="{{route('rechazo.index')}}">
                            <span class="glyphicon glyphicon-minus-sign"></span> Estado Rechazos </a>
                    </li>
                    <li  class="{{!Route::is('notaria.*') ?: 'active'}}">
                        <a href="{{route('notaria.index')}}">
                            <span class="glyphicon glyphicon-folder-close"></span> Notarias </a>
                    </li>
                </ul>
            </li>
            <li>
                <a class="accordion-toggle 
                @php
                    if (Route::is('usuario.*')) {
                        echo 'menu-open';
                    }   
                @endphp
                "  href="#">
                    <span class="glyphicons glyphicons-keys"></span>
                    <span class="sidebar-title">Accesos</span>
                    <span class="caret"></span>
                </a>
                <ul class="nav sub-nav">
                    <li>
                        <a href="{{route('rol.index')}}">
                            <span class="glyphicons glyphicons-adjust_alt"></span> Roles </a>
                    </li>
                    <li class="{{!Route::is('usuario.*') ?: 'active'}}">
                        <a href="{{route('usuario.index')}}">
                            <span class="glyphicons glyphicons-user"></span> Usuarios </a>
                    </li>
                </ul>
            </li>
            @endif

            @if (Auth::user()->rol_id<=3)
            <li class="sidebar-label pt15">SOLICITUDES</li>
            <li>
                <a href="{{route('solicitud.revision')}}">
                    <span class="glyphicons glyphicons-check"></span>
                    <span class="sidebar-title">Para Revisar</span>
                    <span class="sidebar-title-tray">
                        <!--<span class="label label-xs bg-danger">3</span>-->
                    </span>
                </a>
            </li>
            <!--<li>
                <a href="email_templates.html">
                    <span class="glyphicons glyphicons-cogwheels"></span>
                    <span class="sidebar-title">Procesamiento</span>
                </a>
            </li>-->
            @endif


            <!-- sidebar progress bars -->
            <!--<li class="sidebar-label pt25 pb10">Mediciones del Mes</li>
            <li class="sidebar-stat mb10">
                <a href="#" class="fs11">
                    <span class="fa fa-inbox text-warning"></span>
                    <span class="sidebar-title text-muted">Solicitudes Pendientes</span>
                    <span class="pull-right mr20 text-muted">35%</span>
                    <div class="progress progress-bar-xs ml20 mr20">
                        <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 35%">
                            <span class="sr-only">35% Complete</span>
                        </div>
                    </div>
                </a>
            </li>-->
            {{-- <li class="sidebar-stat mb10">
                <a href="#projectOne" class="fs11">
                    <span class="fa fa-dropbox text-warning"></span>
                    <span class="sidebar-title text-muted">Bandwidth</span>
                    <span class="pull-right mr20 text-muted">58%</span>
                    <div class="progress progress-bar-xs ml20 mr20">
                        <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 58%">
                            <span class="sr-only">58% Complete</span>
                        </div>
                    </div>
                </a>
            </li> --}}
        </ul>