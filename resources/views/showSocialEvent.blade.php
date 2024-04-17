@extends('appAdmin')
@extends('adminlte::page')

@section('title', 'Evento '.$event->name)

@section('content_header')
    <h1>Evento: {{$event->name}}</h1>
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

    @if(session()->has('Success'))
        <div class="alert alert-success">
            {{ session()->get('Success') }}
        </div>
    @endif

    <!-- Button trigger modal -->
    @if(!$event->discount)
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createDiscount">
            Añadir descuento
        </button>
    @endif

    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editEvent">
        Editar evento
    </button>

    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registerPayment">
        Registrar pago
    </button>

    <button id="eliminarEvento" type="button" class="btn btn-danger">
        Eliminar
    </button>
    <h5 class="d-block mt-3">Lista de egresados:</h5>
    @if(isset($event->discount))
        <small onclick="editDiscount()" class="d-block mb-3" style="color: blue; cursor: pointer">El evento tiene un
            descuento especial de {{$event->discount}}%</small>
    @endif

    <!-- Modal -->
    <div class="modal modal-center fade" id="editDiscount" tabindex="-1"
         aria-labelledby="editDiscountLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editDiscountLabel">Añadir descuento</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="create_graduate_party" action="{{route('social.event.edit.discount', $event->id)}}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3" id="discount">
                            <label for="discount" class="form-label">Descuento:</label>
                            <input value="{{$event->discount ?? 0}}" type="number"
                                   step="0.01" class="form-control" name="discount">
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

    <!-- modal registrar pago -->
    <div class="modal modal-center fade" id="registerPayment" tabindex="-1"
         aria-labelledby="registerPaymentLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="registerPaymentLabel">Registrar pago</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="create_graduate_party" action="{{route('social.event.register.payment', $event->id)}}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="payment" class="form-label">Monto:</label>
                            <input type="number" class="form-control"
                                   id="payment" name="payment">
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


    <!-- modal editar evento -->
    <div class="modal modal-center fade" id="editEvent" tabindex="-1"
         aria-labelledby="editEventLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editEventLabel">Editar Evento</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="create_graduate_party" action="{{route('edit.graduate', $event->id)}}" method="POST">
                        @csrf
                        @method("PUT")
                        <div class="mb-3">
                            <label for="escuela_id" class="form-label">Nombre</label>
                            <input value="{{$event->name}}" type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="mb-3">
                            <label for="cantidad_egresados" class="form-label">Cantidad de comensales</label>
                            <input value="{{$event->diners}}" type="number" class="form-control"
                                   id="cantidad_egresados" name="cantidad_egresados">
                        </div>
                        <div class="mb-3">
                            <label for="fecha" class="form-label">Fecha del evento</label>
                            <input name="fecha" type="date" class="form-control" id="fecha">
                            <small style="color: red">Volver a elegir la fecha</small>
                        </div>
                        <div class="mb-3">
                            <label for="menu_id" class="form-label">Menú elegido:</label>
                            <select id="menu_id" class="form-select" name="menu_id">
                                <option selected="true" disabled="disabled">Seleccionar menú elegido</option>
                                @if(isset($menus))
                                    @foreach($menus as $menu)
                                        <option
                                            {{$menu->id == $event->menu_id ? 'selected' : ''}} value="{{$menu->id}}">{{$menu->nombre}}</option>
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

    <!-- Modal -->
    <div class="modal modal-center fade" id="createDiscount" tabindex="-1"
         aria-labelledby="createDiscountLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="createDiscountLabel">Añadir descuento</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="create_graduate_party" action="{{route('social.event.add.discount', $event->id)}}" method="POST">
                        @csrf

                        <div class="mb-3" id="discount">
                            <label for="discount" class="form-label">Descuento:</label>
                            <input type="number" step="0.01" class="form-control" name="discount">
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

@stop

@section('css')
@stop

@section('js')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function editDiscount() {
            $("#editDiscount").modal('show');
        }
    </script>
@stop
