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

    @if (session()->has('Errors'))
        <div class="alert alert-danger">
            {{session()->get('Errors')}}
        </div>
    @endif

    @if(session()->has('Success'))
        <div class="alert alert-success">
            {{ session()->get('Success') }}
        </div>
    @endif

    <!-- Button trigger modal -->
    @if($event->discount == 0.0 || $event->discount == 0 || isset($event->payments))
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

    <br>

{{--    Informacion del evento--}}
    <div class="row">
        <div class="d-flex">
            <div class="col-8">
                <h2 class="d-block mt-3">Información del evento:</h2>
                <h5 class="d-block mb-3" style="color: blue;">Descuento por cantidad de comensales de {{$event->getDiscountByDiners()}}%</h5>
                @if($event->discount !== 0.0)
                    <h5 onclick="editDiscount()" class="d-block mb-3" style="color: blue; cursor: pointer">El evento tiene un
                        descuento especial de {{$event->discount}}%</h5>
                @endif
                <h4>Fecha del evento: {{$event->getFormatedDate()}}</h4>
                <h4>Menú: {{$event->menu->nombre}}</h4>
                <h4>Cantidad de comensales: {{$event->diners}}</h4>
                <h4>Platos pagos: {{$event->getCountOfPayedDishes()}}</h4>
                <h4>Platos por pagar: {{$event->diners - $event->getCountOfPayedDishes()}}</h4>
                <h4>Total pago: ${{$event->getAmountOfPayments()}}</h4>
                <h4>Total pendiente: ${{$event->total}}</h4>
            </div>
            <div class="col-4">
                <h2 class="d-block mt-3">Historial de pagos:</h2>
                @foreach($event->payments as $payment)
                    <h6 style="margin: 0; color: blue; cursor: pointer" onclick="deshacerPago({{$payment->id}})">{{\Carbon\Carbon::parse($payment->created_at)->format('d-m-Y')}} | {{$payment->diners_quantity}} platos | ${{$payment->payment}}</h6>
                @endforeach
            </div>
        </div>
    </div>


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
                            <label for="diners_quantity" class="form-label">Cantidad de platos a pagar:</label>
                            <br>
                            <small style="color: red">Precio unitario por plato: <strong>${{$event->returnMenuPriceWithDiscounts()}}</strong></small>
                            <input type="number" class="form-control"
                                   id="diners_quantity" name="diners_quantity">
                        </div>
{{--                        <div class="mb-3">--}}
{{--                            <label for="payment" class="form-label">Monto:</label>--}}
{{--                            <input type="number" class="form-control"--}}
{{--                                   id="payment" name="payment">--}}
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
                    <form id="edit_social_event" action="{{route('edit.social.event', $event->id)}}" method="POST">
                        @csrf
                        @method("PUT")
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre</label>
                            <input value="{{$event->name}}" type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="mb-3">
                            <label for="diners" class="form-label">Cantidad de comensales</label>
                            <input value="{{$event->diners}}" type="number" class="form-control"
                                   id="diners" name="diners">
                        </div>
                        <div class="mb-3">
                            <label for="fecha" class="form-label">Fecha del evento</label>
                            <input name="fecha" value="{{\Carbon\Carbon::parse($event->fecha)->format('Y-m-d')}}" type="date" class="form-control" id="fecha">
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
                            <br>
                            <small style="color: red">Atención: el descuento es por sobre el menú unitario con el descuento por cantidad de comensales ya aplicado</small>
                            <br>
                            <small><strong>total = valor plato con descuento de cantidad de comensales - descuento especial</strong></small>
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


        function deshacerPago(id) {
            swal.fire({
                title: '<h1>¿Deseas deshacer este pago?</h1>',
                icon: 'question',
                showCloseButton: true,
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText:
                    'Si',
                cancelButtonText:
                    'No',
            })
                .then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/admin/delete-payment/social-event/${id}`,
                            method: "POST",
                            datatype: "json",
                            data: {
                                "_token": "{{ csrf_token() }}",
                            },
                            success: function (response) {
                                Swal.fire({
                                    icon: 'success',
                                    // title: 'Oops...',
                                    confirmButtonText:
                                        '<button id="delete_button" class="btn w-100 h-100">OK</button>',
                                    title: '<h1>Cambios confirmados</h1>',
                                    // footer: '<a href="">Why do I have this issue?</a>'
                                })
                                    .then(function () {
                                        location.reload();
                                    })

                            },
                        })
                    } else if (result.isDenied) {
                        Swal.fire('No se registró ningún cambio', '', 'info')
                    }
                })

        }

        $('#eliminarEvento').on('click', function () {
            swal.fire({
                title: '<h1>¿Seguro que deseas eliminar el evento? Los cambios no podran deshacerse</h1>',
                icon: 'question',
                showCloseButton: true,
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText:
                    'Si',
                cancelButtonText:
                    'No',
            })
                .then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        let route = '{{route('deleteSocialEvent', $event->id)}}';
                        let id = {{$event->id}};
                        $.ajax({
                            url: route,
                            type: "delete",
                            datatype: "json",
                            data: {
                                "_token": "{{ csrf_token() }}",
                                id: id,
                            },
                            success: function (response) {
                                Swal.fire({
                                    icon: 'success',
                                    // title: 'Oops...',
                                    confirmButtonText:
                                        '<button id="delete_button" onclick="history.back()" class="btn w-100 h-100">OK</button>',
                                    title: '<h1>Evento eliminado</h1>',
                                    // footer: '<a href="">Why do I have this issue?</a>'
                                })
                                    .then(function () {
                                        let route = "{{route('dashboard')}}"
                                        location.replace(route)
                                    })
                            },
                            error: function (err){
                                console.log(err)
                            }
                        })
                    } else if (result.isDenied) {
                        Swal.fire('No se eliminó ningún egresado', '', 'info')
                    }
                })
        })





    </script>
@stop
