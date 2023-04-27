@extends('appAdmin')
@extends('adminlte::page')

@section('title', 'Invitados de '.$student->nombre)

@section('content_header')
    <h1>Egresado {{$student->nombre}}</h1>
@stop

@section('content')

    <!-- Button trigger modal -->
    <div class="mb-3">
        {{--        <small>El egresado tiene {{$student->familiares}} invitados y hay {{count($student->people)}}--}}
        {{--            cargados</small>--}}
        <small>{{$student->resumen ? 'El precio está cerrado': 'El precio está sin cerrar'}}</small>
        <br>
        <small>El egresado tiene un descuento de {{$student->resumen ? $student->resumen->descuento_estudiante : $student->descuento_especial}}%</small>
        <br>
        <small>Descuento por cantidad de egresados: {{$student->resumen ? $student->resumen->descuento_cantidad_egresados : $student->event->getEventDiscountByAmountOfStudents()}}%</small>
        <br>
        <small>Descuento por día elegido: {{$student->resumen ? $student->resumen->descuento_dia_elegido : $student->event->day->descuento}}%</small>
        <br>
    </div>
    <a href="{{route('show.graduate', $student->event->slug)}}" style="text-decoration: none">
        <button type="button" class="btn btn-primary">
            Volver al evento
        </button>
    </a>
    <button {{$student->resumen ? 'disabled':''}} type="button" class="btn btn-success"
            onclick="closePrice({{$student->id}})">
        Cerrar precio
    </button>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createGraduateParty">
        {{$student->resumen ? 'Agregar persona fuera de término' : 'Agregar persona'}}
    </button>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#informarPago">
        Registrar adelanto
    </button>

    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createDiscount">
        {{$student->descuento_especial != 0 ? 'Editar descuento' : 'Añadir descuento'}}
    </button>

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
                    <form id="create_graduate_party" action="{{route('create.studentDiscount', $student->id)}}"
                          method="POST">
                        @csrf

                        <div class="mb-3" id="descuento">
                            <label for="descuento" class="form-label">Descuento:</label>
                            <input value="{{$student->descuento_especial}}" type="number" step="0.01"
                                   class="form-control" name="descuento">
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
    <div class="modal modal-center fade" id="informarPago" tabindex="-1"
         aria-labelledby="informarPagoLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="informarPagoLabel">Registrar adelanto:</h1>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="create_graduate_party" action="{{route('payDebt', $student->id)}}" method="POST">
                        @csrf

                        <div class="mb-3" id="pago">
                            <label for="pago" class="form-label">Cantidad:</label>
                            <input type="text" class="form-control" name="pago">
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
    <button id="eliminarAlumno" type="button" class="btn btn-danger">
        Eliminar
    </button>


    <div class="mt-2 mb-5 row position-relative" id="toPrint">
        {{--        @dd($student->getTotalPrice())--}}
        <div class="col-6">
            <h3>Resúmen:</h3>
            <h4>Precio unitario:
                ${{($student->resumen ? $student->resumen->precio_unitario: $student->event->menu->precio) - ($student->resumen ? $student->resumen->precio_unitario: $student->event->menu->precio) * $student->getTotalDiscounts() / 100}}</h4>
            <h4>Egresado: </h4>
            <h6 class="d-block ms-3">1- {{$student->nombre}}:
                @if($student->resumen)
                    ${{$student->getPriceOfAdults() / (count($student->people->where('tipo', 'adulto')->where('fuera_termino', 0)) + 1  ) - ($student->resumen->precio_unitario * $student->descuento_especial / 100)}}
                @else
                    ${{$student->getPriceOfAdults() / (count($student->people->where('tipo', 'adulto')->where('fuera_termino', 0)) + 1) - ($student->event->menu->precio * $student->descuento_especial / 100)}}
                @endif


            </h6>
            <h4>Adultos:</h4>
            @foreach($student->people->where('tipo', 'adulto') as $people)
                @if($people->fuera_termino == 1)
                    <h6 class="d-block ms-3">{{$loop->iteration}}- {{$people->nombre}}: ${{$people->total}}</h6>
                @else
                    <h6 class="d-block ms-3">{{$loop->iteration}}- {{$people->nombre}}:
                        @if($student->resumen)
                            ${{$student->resumen->precio_unitario_descuentos}}
                        @else
                            ${{$student->getPriceOfAdults() / (count($student->people->where('tipo', 'adulto')->where('fuera_termino', 0)) + 1)}}
                        @endif

                    </h6>
                @endif

            @endforeach
            <h4>Menores de 12:</h4>
            @foreach($student->people->where('tipo', 'menor_12') as $people)
                @if($people->tipo == 'menor_12')
                    @if($people->fuera_termino == 1)
                        <h6 class="d-block ms-3">{{$loop->iteration}}- {{$people->nombre}}: ${{$people->total}}</h6>
                    @else
                        <h6 class="d-block ms-3">{{$loop->iteration}}- {{$people->nombre}}:
                            ${{($student->resumen ? $student->resumen->precio_unitario_descuentos : $student->getPriceOfAdults() / (count($student->people->where('tipo', 'adulto')->where('fuera_termino', 0)) + 1)) / 2}}</h6>
                    @endif

                @endif
            @endforeach
            <h4>Menores de 5:</h4>
            @foreach($student->people->where('tipo', 'menor_5') as $people)
                <h6 class="d-block ms-3">{{$loop->iteration}}- {{$people->nombre}}:
                    $0</h6>
            @endforeach
            <h4>Medio de pago: {{$student->medioDePago->metodo}}</h4>
            <h4>IVA:
                ${{$student->resumen ? round($student->resumen->iva) :
            round(($student->getPriceOfMinorsOfTwelve() + $student->getPriceOfAdults())* $student->medioDePago->iva / 100)
}}</h4>
            <h4>Total:
                ${{$student->getTotalPrice()}}</h4>
            <h4>Total pendiente:
                ${{($student->getTotalPriceWithAdvancePayments() - $student->getDuesPayedAmount())}}</h4>
        </div>
        <div class="position-absolute w-50" style="top:0; right: 0">
            <h3>Pagos:</h3>
            @if(count($student->payments->where('tipo', 'adelanto')))
                <h4>Adelantos realizados:</h4>
                @foreach($student->payments as $payment)
                    @if($payment->tipo == 'adelanto')
                        <h6 style="margin: 0; color: blue; cursor: pointer"
                            onclick="deshacerAdelanto({{$payment->id}})">${{$payment->amount}} el
                            día {{\Illuminate\Support\Carbon::parse($payment->created_at)->format('d-m-Y')}}</h6>
                    @endif
                @endforeach
            @endif

            <h4>Forma de pago: {{$student->paymentType->nombre}}</h4>
            <h4>Interés por pago en cuotas: {{$student->resumen ? $student->resumen->interes_cuotas : $student->paymentType->interes}}%</h4>
            <h4>Fecha de
                pago: {{\Illuminate\Support\Carbon::createFromFormat('Y-m-d', $student->fecha_pago)->format('d-m-Y')}}</h4>

            <h4>Fechas de cuotas:</h4>
            <div class="row">
                @foreach($student->cuotas as $cuota)
                    <div class="col-4">
                        <h6 style="margin: 0; color: blue; cursor: pointer"
                            onclick="pagarCuota({{$cuota->id}})">{{\Illuminate\Support\Carbon::parse($cuota->fecha_estipulada)->format('d-m-Y')}} </h6>
                        @if($cuota->status == 0)
                            @if(\Illuminate\Support\Carbon::now() > \Illuminate\Support\Carbon::parse($cuota->fecha_estipulada))
                                <small>Impaga</small>
                                <br>
                                <small>Deuda con interés por mora</small>
                                <small
                                    class="d-block mb-3">${{round(($student->getTotalPriceWithAdvancePayments() - $student->getDuesPayedAmount()) / $student->getRemainingDuesCount())}}
                                    +
                                    ${{(round(($student->getTotalPriceWithAdvancePayments() - $student->getDuesPayedAmount()) / $student->getRemainingDuesCount())) * \App\Models\InteresCuota::first()->interes/ 100}}</small>
                            @else
                                <small>Impaga</small>
                                <small
                                    class="d-block mb-3">${{round(($student->getTotalPriceWithAdvancePayments() - $student->getDuesPayedAmount()) / $student->getRemainingDuesCount())}}</small>
                            @endif
                        @else
                            <small>Pagada
                                el {{ Illuminate\Support\Carbon::parse($cuota->fecha_pago)->format('d-m-Y')}}</small>
                            <small
                                class="d-block mb-3">${{\App\Models\Pago::where('estudiantes_cuotas_id', $cuota->id)->first()->amount}}
                                {{\App\Models\Pago::where('estudiantes_cuotas_id', $cuota->id)->first()->interes ? '+ $'.\App\Models\Pago::where('estudiantes_cuotas_id', $cuota->id)->first()->interes . ' interés por mora' : '' }}</small>
                        @endif

                    </div>
                @endforeach
            </div>

        </div>

    </div>

    <div class="mt-3">
        <table id="events" class="display nowrap mt-5" style="width:100%">
            <thead>
            <th></th>
            <th></th>
            </thead>
        </table>
    </div>


    <!-- Modal -->
    <div class="modal modal-center fade" id="createGraduateParty" tabindex="-1"
         aria-labelledby="createGraduatePartyLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="createGraduatePartyLabel">Añadir familiar:</h1>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="create_graduate_party" action="{{route('store.invitado')}}" method="POST">
                        @csrf
                        <input class="" type="hidden" name="estudiante_id" value="{{$student->id}}" id="estudiante_id">

                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre completo</label>
                            <input type="text" class="form-control" id="nombre" name="nombre">
                        </div>

                        <div class="mb-3" id="tipo">
                            <label for="tipo" class="form-label">Tipo:</label>
                            <select class="form-select" name="tipo">
                                <option selected="true" disabled="disabled">Seleccionar tipo</option>
                                <option value="adulto">Adulto</option>
                                <option value="menor_12">Menor de 12</option>
                                <option value="menor_5">Menor de 5</option>
                            </select>
                        </div>

                        <div class="mb-3" id="menu_id">
                            <label for="menu_id" class="form-label">Menú especial:</label>
                            <select class="form-select" name="menu_especial_id">
                                <option selected="true" disabled="disabled">Seleccionar menú especial</option>
                                @if(isset($specialMenu))
                                    @foreach($specialMenu as $menu)
                                        <option value="{{$menu->id}}">{{$menu->nombre}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="mb-3" id="telefono">
                            <label for="telefono" class="form-label">Teléfono:</label>
                            <input type="text" class="form-control" name="telefono">
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

    @foreach($student->people as $family)
        <!-- Modal -->
        <div class="modal modal-center fade" id="editFamily{{$family->id}}" tabindex="-1"
             aria-labelledby="editFamily{{$family->id}}Label"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="createGraduatePartyLabel">Editar familiar:</h1>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="create_graduate_party" action="{{route('editFamily', $family->id)}}" method="POST">
                            @csrf
                            <input class="" type="hidden" name="family_id" value="{{$family->id}}" id="family_id">

                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre completo</label>
                                <input value="{{$family->nombre}}" type="text" class="form-control" id="nombre"
                                       name="nombre">
                            </div>

                            <div class="mb-3" id="menu_id">
                                <label for="menu_id" class="form-label">Menú especial:</label>
                                <select class="form-select" name="menu_especial_id">
                                    <option>Seleccionar menú especial</option>
                                    @if(isset($specialMenu))
                                        @foreach($specialMenu as $menu)
                                            <option
                                                {{$family->menu_especial == $menu->id ? 'selected' : ''}} value="{{$menu->id}}">{{$menu->nombre}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="mb-3" id="telefono">
                                <label for="telefono" class="form-label">Teléfono:</label>
                                <input value="{{$family->telefono}}" type="text" class="form-control" name="telefono">
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
    @endforeach

@stop

@section('css')
@stop

@section('js')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>

        function openEditModal(id) {
            $("#editFamily" + id).modal('show');
        }

        function closePrice(id) {
            swal.fire({
                title: '<h1>¿Deseas cerrar el precio? Esto significa que el precio para el egresado no podrá variar</h1>',
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
                            url: `/api/cerrar-precio/${id}`,
                            type: "POST",
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
                                    title: '<h1>Precio cerrado</h1>',
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

        // deshacerAdelanto
        function deshacerAdelanto(id) {
            swal.fire({
                title: '<h1>¿Deseas deshacer este adelanto?</h1>',
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
                            url: `/api/deshacer-adelanto/${id}`,
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


        function pagarCuota(id) {
            $.ajax({
                url: `/api/cuota/${id}`,
                type: "GET",
                datatype: "json",
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function (response) {
                    if (response == 0) {
                        swal.fire({
                            title: '<h1>¿Deseas registrar esta cuota como saldada?</h1>',
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
                                        url: `/api/saldar-cuota/${id}`,
                                        type: "PUT",
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
                                                title: '<h1>Cuota saldada</h1>',
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
                    } else {
                        swal.fire({
                            title: '<h1>¿Deseas registrar esta cuota como no saldada?</h1>',
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
                                        url: `/api/saldar-cuota/${id}`,
                                        type: "PUT",
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
                                                title: '<h1>Cuota registrada como no saldada</h1>',
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

                },
            })
        }

        $('#eliminarAlumno').on('click', function () {
            swal.fire({
                title: '<h1>¿Seguro que deseas eliminar al egresado? Los cambios no podran deshacerse</h1>',
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
                        let route = '{{route('deleteStudent', $student->id)}}';
                        let id = {{$student->id}};
                        $.ajax({
                            url: route,
                            type: "DELETE",
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
                                    title: '<h1>Estudiante eliminado</h1>',
                                    // footer: '<a href="">Why do I have this issue?</a>'
                                })
                                    .then(function () {
                                        let route = "{{route('show.graduate', $student->event->slug)}}"
                                        location.replace(route)
                                    })
                            },
                        })
                    } else if (result.isDenied) {
                        Swal.fire('No se eliminó ningún egresado', '', 'info')
                    }
                })
        })

        let id = {{$student->id}}
        $(document).ready(function () {
            let url = '/api/egresado/' + id
            let table = $('#events').DataTable();
            table.destroy();
            $('#events').empty();


            $('#events').DataTable({
                deferRender: true,
                "autoWidth": true,
                "paging": true,
                stateSave: true,
                "processing": true,
                "ajax": url,
                columnDefs: [{
                    "defaultContent": "-",
                    "targets": "_all"
                }],
                dom: 'Bfrtilp',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fas fa-file-excel"></i>',
                        titleAttr: 'Exportar a Excel',
                        className: 'btn btn-success'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i>',
                        titleAttr: 'Imprimir',
                        className: 'btn btn-info',
                        customize: function (win) {
                            $(win.document.body)
                                .css('font-size', '10pt')
                                .prepend(
                                    document.getElementById('toPrint').innerHTML
                                )
                                .prepend(
                                    '<img src="https://areacostaneraegresados.com/storage/images/Logo%20Area.png" style="position: absolute; top: 50%;left: 50%;width: 500px; height: 500px;margin-top: -250px; margin-left: -250px; opacity: 0.1" />'
                                );

                            $(win.document.body).find('table')
                                .addClass('compact')
                                .css('font-size', 'inherit');
                        }
                    }
                ],
                "columns": [
                    {
                        title: "NOMBRE",
                        data: 'nombre'
                    },
                    {
                        title: "MENU ESPECIAL",
                        data: 'menu_especial'
                    },
                    {
                        title: "TELÉFONO",
                        data: 'telefono'
                    },
                    {
                        title: "OPCION",
                        width: "5%",
                        sortable: false,
                        "render": function (data, type, full, meta) {
                            let id = full.id;
                            let nombre = full.nombre;
                            let personas = full.personas;

                            return `<a title="Editar familiar" onclick="openEditModal(${id})"
                            style="cursor:
                            pointer; text-decoration: none;
                            "> <i
                            class="fa-solid fa-edit"></i> </a>` +
                                `<a title="Eliminar familiar" href="/admin/eliminar-familia/${id}"
                            style="cursor:
                            pointer;
                            "> <i
                            class="fa-solid fa-trash"></i> </a>`;
                            ;
                        }
                    },
                ]
            })
        })


    </script>
@stop
