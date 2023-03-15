@extends('appAdmin')
@extends('adminlte::page')

@section('title', 'Perfil del egresado '.$student->nombre)

@section('content_header')
    <h1>Egresado {{$student->nombre}}</h1>
@stop

@section('content')

    <!-- Button trigger modal -->
    <div class="mb-3">
        <small>El egresado tiene {{$student->familiares}} familiares invitados y hay {{count($student->people)}}
            cargados</small>
        <br>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createGraduateParty">
        Agregar persona
    </button>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#informarPago">
        Registrar pago
    </button>
    <!-- Modal -->
    <div class="modal modal-center fade" id="informarPago" tabindex="-1"
         aria-labelledby="informarPagoLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="informarPagoLabel">Registrar pago:</h1>

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


    <div class="my-3 row">

        <div class="col-6">
            <h3>Resúmen:</h3>
            <h5>Descuento por cant. de egresados: {{$student->event->getEventDiscountByAmountOfStudents()}}%</h5>
            <h5>Adultos + egresado:
                ${{$student->getPriceOfAdults()}}</h5>
            <h5>Menores de 12:
                ${{$student->getPriceOfMinorsOfTwelve()}}</h5>
            <h5>Menores de 5: no pagan</h5>
            <h5>IVA: ${{($student->getPriceOfMinorsOfTwelve() + $student->getPriceOfAdults())* $student->medioDePago->iva / 100}}</h5>
            <h4>Total:
                ${{($student->getTotalPrice())}}</h4>
        </div>
        <div class="col-6">
            <h3>Pago:</h3>
            @if(count($student->payments))
                <h5>Pagos realizados:</h5>
                @foreach($student->payments as $payment)
                    <h6>${{$payment->amount}} el día {{\Illuminate\Support\Carbon::parse($payment->created_at)->add(1,'month')->format('d-m-Y')}}</h6>
                @endforeach
            @endif

            <h5>Medio de pago: {{$student->medioDePago->metodo}}</h5>
            <h5>Forma de pago: {{$student->paymentType->nombre}}</h5>
            <h5>Fecha de pago: {{\Illuminate\Support\Carbon::createFromFormat('Y-m-d', $student->fecha_pago)->format('d-m-Y')}}</h5>
            @if($student->paymentType->id != 1)

                <h5>Fechas de cuotas:</h5>
                    @if($student->paymentType->id == 2)
                        <h6>{{\Illuminate\Support\Carbon::createFromFormat('Y-m-d', $student->fecha_pago)->format('d-m-Y')}} </h6>
                        <h6>{{\Illuminate\Support\Carbon::parse($student->fecha_pago)->add(1,'month')->format('d-m-Y')}} </h6>
                        <h6>{{\Illuminate\Support\Carbon::parse($student->fecha_pago)->add(2,'month')->format('d-m-Y')}}</h6>
                    @elseif($student->paymentType->id == 3)

                        <h6>{{\Illuminate\Support\Carbon::createFromFormat('Y-m-d', $student->fecha_pago)->format('d-m-Y')}}</h6>
                        <h6>{{\Illuminate\Support\Carbon::parse($student->fecha_pago)->add(1,'month')->format('d-m-Y')}}</h6>
                        <h6>{{\Illuminate\Support\Carbon::parse($student->fecha_pago)->add(2,'month')->format('d-m-Y')}}</h6>
                        <h6>{{\Illuminate\Support\Carbon::parse($student->fecha_pago)->add(3,'month')->format('d-m-Y')}}</h6>
                        <h6>{{\Illuminate\Support\Carbon::parse($student->fecha_pago)->add(4,'month')->format('d-m-Y')}}</h6>
                        <h6>{{\Illuminate\Support\Carbon::parse($student->fecha_pago)->add(5,'month')->format('d-m-Y')}}</h6>
                    @endif


            @endif

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
                    <form id="create_graduate_party" action="{{route('store.student')}}" method="POST">
                        @csrf
                        <input class="" type="hidden" name="estudiante_id" value="{{$student->id}}" id="estudiante_id">

                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre completo</label>
                            <input type="text" class="form-control" id="nombre" name="nombre">
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

@stop

@section('css')
@stop

@section('js')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $('#eliminarAlumno').on('click', function (){
            swal.fire({
                title: '<strong>¿Seguro que deseas eliminar al egresado? Los cambios no podran deshacerse</strong>',
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
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    // title: 'Oops...',
                                    confirmButtonText:
                                        '<button id="delete_button" onclick="history.back()" class="btn w-100 h-100">OK</button>',
                                    title: '<strong>Estudiante eliminado</strong>',
                                    // footer: '<a href="">Why do I have this issue?</a>'
                                })
                                    .then(function(){
                                        history.go(-1);
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
                buttons:[
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
                        className: 'btn btn-info'
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
                ]
            })
        })


    </script>
@stop
