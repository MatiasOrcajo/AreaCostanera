@extends('appAdmin')
@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Fiestas de Egresados</h1>
@stop

@section('content')

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createGraduateParty">
        Nuevo evento egresados
    </button>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSocialEvent">
        Nuevo evento social
    </button>

    <!-- Modal egresados -->
    <div class="modal modal-center fade" id="createGraduateParty" tabindex="-1"
         aria-labelledby="createGraduatePartyLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="createGraduatePartyLabel">Creación de Fiesta de Egresados</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="create_graduate_party" action="{{route('store.graduate')}}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="escuela_id" class="form-label">Escuela</label>
                            <select id="escuela_id" class="form-select" name="escuela_id">
                                <option selected="true" disabled="disabled">Seleccionar escuela</option>
                                @if(isset($escuelas))
                                    @foreach($escuelas as $escuela)
                                        <option value="{{$escuela->id}}">{{$escuela->nombre}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="curso" class="form-label">Curso</label>
                            <input type="text" class="form-control" id="curso" name="curso">
                        </div>
                        <div class="mb-3">
                            <label for="cantidad_egresados" class="form-label">Cantidad de egresados</label>
                            <input type="number" class="form-control" id="cantidad_egresados" name="cantidad_egresados">
                        </div>
                        <div class="mb-3">
                            <label for="fecha" class="form-label">Fecha del evento</label>
                            <input name="fecha" type="date" class="form-control" id="fecha">
                        </div>
                        <div class="mb-3">
                            <label for="dia_id" class="form-label">Grupo de días:</label>
                            <select id="dia_id" class="form-select" name="dia_id">
                                <option selected="true" disabled="disabled">Seleccionar grupo de días</option>
                                @if(isset($dias))
                                    @foreach($dias as $dia)
                                        <option value="{{$dia->id}}">{{$dia->nombre}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="menu_id" class="form-label">Menú elegido:</label>
                            <select id="menu_id" class="form-select" name="menu_id">
                                <option selected="true" disabled="disabled">Seleccionar menú elegido</option>
                                @if(isset($menus))
                                    @foreach($menus as $menu)
                                        <option value="{{$menu->id}}">{{$menu->nombre}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        {{--                        <div class="mb-3">--}}
                        {{--                            <label for="forma_pago_id" class="form-label">Forma de Pago:</label>--}}
                        {{--                            <select id="forma_pago_id" class="form-select" name="forma_pago_id">--}}
                        {{--                                <option selected="true" disabled="disabled">Seleccionar forma de pago</option>--}}
                        {{--                                @if(isset($formasPago))--}}
                        {{--                                    @foreach($formasPago as $formaPago)--}}
                        {{--                                        <option value="{{$formaPago->id}}">{{$formaPago->nombre}}</option>--}}
                        {{--                                    @endforeach--}}
                        {{--                                @endif--}}
                        {{--                            </select>--}}
                        {{--                        </div>--}}
                        {{--                        <div class="mb-3">--}}
                        {{--                            <label for="fecha_pago" class="form-label">Fecha de pago</label>--}}
                        {{--                            <input name="fecha_pago" type="date" class="form-control" id="fecha_pago">--}}
                        {{--                        </div>--}}
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal evento social-->
    <div class="modal modal-center fade" id="createSocialEvent" tabindex="-1"
         aria-labelledby="createSocialEventLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="createSocialEventLabel">Creación de evento social</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="create_graduate_party" action="{{route('store.social.event')}}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre de evento</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">Fecha del evento</label>
                            <input name="date" type="date" class="form-control" id="date">
                        </div>
                        <div class="mb-3">
                            <label for="diners" class="form-label">Cantidad de comensales</label>
                            <input name="diners" type="text" class="form-control" id="diners">
                        </div>
                        <div class="mb-3">
                            <label for="menu_id" class="form-label">Menú elegido:</label>
                            <select id="menu_id" class="form-select" name="menu_id">
                                <option selected="true" disabled="disabled">Seleccionar menú elegido</option>
                                @if(isset($menusSociales))
                                    @foreach($menusSociales as $menu)
                                        <option value="{{$menu->id}}">{{$menu->nombre}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="my-3 mt-5">

        @if(isset($socialEvents))
            <div class="d-flex flex-wrap align-items-center">
                @foreach($socialEvents as $event)
                    @if($event)
                        <div class=" col-5 rounded m-3 w-3 h-3 p-2"
                             style="{{$event->getCssForPartyBox()}}; box-shadow: 10px 9px 6px 2px rgba(0,0,0,0.1);">
                            <a href="{{route('show.social.event', $event->slug)}}">
                                <div class="col-10">
                                    <h3>
                                        Social: {{$event->name}}
                                    </h3>
                                    <span>
                                        Fecha: {{$event->fecha}}
                                    <br>
                                        Personas: {{$event->diners}}
                                    </span>
                                </div>
                            </a>
                        </div>
                    @endif

                @endforeach
            </div>
        @endif


        @if(isset($graduateParties))
            <div class="d-flex flex-wrap align-items-center">
                @foreach($graduateParties as $event)
                    @if($event)
                        <div class=" col-5 rounded m-3 w-3 h-3 p-2"
                             style="{{$event->getCssForPartyBox()}}; box-shadow: 10px 9px 6px 2px rgba(0,0,0,0.1);">
                            <a href="{{route('show.graduate', $event->slug)}}">
                                <div class="col-10">
                                    <h3>
                                        Escuela {{$event->school->nombre}}, curso {{$event->curso}}
                                    </h3>
                                    <span>
                                        Fecha: {{$event->fecha}}
                                    <br>
                                        Personas: {{$event->cantidad_egresados}}
                                    </span>
                                </div>
                            </a>
                        </div>
                    @endif

                @endforeach
            </div>
        @endif
    </div>
@stop

@section('css')
    <style>
        /* unvisited link */
        a:link {
            color: white;
            text-decoration: none;
        }

        /* visited link */
        a:visited {
            color: white;
            text-decoration: none;
        }

        /* mouse over link */
        a:hover {
            color: black !important;
            text-decoration: none;
        }

        /* selected link */
        a:active {
            color: white;
            text-decoration: none;
        }
    </style>
@stop

@section('js')
@stop
