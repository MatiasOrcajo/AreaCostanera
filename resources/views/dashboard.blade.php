@extends('app')
@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Fiestas de Egresados</h1>
@stop

@section('content')
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createGraduateParty">
        Crear nuevo evento
    </button>

    <!-- Modal -->
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
                    <form id="create_graduate_party" action="" method="POST">
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
                            <label for="curso_id" class="form-label">Curso</label>
                            <input type="text" class="form-control" id="curso_id" name="curso_id">
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
                        <div class="mb-3">
                            <label for="forma_pago_id" class="form-label">Menú elegido:</label>
                            <select id="forma_pago_id" class="form-select" name="forma_pago_id">
                                <option selected="true" disabled="disabled">Seleccionar forma de pago</option>
                                @if(isset($menus))
                                    @foreach($menus as $menu)
                                        <option value="{{$menu->id}}">{{$menu->nombre}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="fecha_pago" class="form-label">Fecha de pago</label>
                            <input name="fecha_pago" type="date" class="form-control" id="fecha_pago">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop
