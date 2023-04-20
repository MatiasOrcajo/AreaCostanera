@extends('appAdmin')
@extends('adminlte::page')

@section('title', 'Evento '.$event->school->nombre)

@section('content_header')
    <h1>Fiesta de la escuela {{$event->school->nombre}} de la fecha {{\Carbon\Carbon::parse($event->fecha)->format('d-m-Y')}}</h1>
@stop

@section('content')
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createGraduateParty">
        Agregar egresado
    </button>

    <!-- Button trigger modal -->
    @if(!$event->discount)
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createDiscount">
            Añadir descuento
        </button>
    @endif

    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editEvent">
        Editar evento
    </button>

    <button id="eliminarEvento" type="button" class="btn btn-danger">
        Eliminar
    </button>
    <h5 class="d-block mt-3">Lista de egresados:</h5>
    @if(isset($event->discount))
        <small onclick="editDiscount()" class="d-block mb-3" style="color: blue; cursor: pointer">El evento tiene un descuento especial de {{$event->discount->descuento}}%</small>
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
                    <form id="create_graduate_party" action="{{route('edit.discount', $event->id)}}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3" id="descuento">
                            <label for="descuento" class="form-label">Descuento:</label>
                            <input value="{{$event->discount ? $event->discount->descuento : 0}}" type="number" step="0.01" class="form-control" name="descuento">
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


    <div class="modal modal-center fade" id="editEvent" tabindex="-1"
         aria-labelledby="editEventLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editEventLabel">Editar Fiesta de Egresados</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="create_graduate_party" action="{{route('edit.graduate', $event->id)}}" method="POST">
                        @csrf
                        @method("PUT")
                        <div class="mb-3">
                            <label for="escuela_id" class="form-label">Escuela</label>
                            <select id="escuela_id" class="form-select" name="escuela_id">
                                <option selected="true" disabled="disabled">Seleccionar escuela</option>
                                @if(isset($escuelas))
                                    @foreach($escuelas as $escuela)
                                        <option {{$escuela->id == $event->escuela_id ? 'selected' : ''}} value="{{$escuela->id}}">{{$escuela->nombre}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="curso" class="form-label">Curso</label>
                            <input value="{{$event->curso}}" type="text" class="form-control" id="curso" name="curso">
                        </div>
                        <div class="mb-3">
                            <label  for="cantidad_egresados" class="form-label">Cantidad de egresados</label>
                            <input value="{{$event->cantidad_egresados}}" type="number" class="form-control" id="cantidad_egresados" name="cantidad_egresados">
                        </div>
                        <div class="mb-3">
                            <label for="fecha" class="form-label">Fecha del evento</label>
                            <input name="fecha" type="date" class="form-control" id="fecha">
                            <small style="color: red">Volver a elegir la fecha</small>
                        </div>
                        <div class="mb-3">
                            <label for="dia_id" class="form-label">Grupo de días:</label>
                            <select id="dia_id" class="form-select" name="dia_id">
                                <option selected="true" disabled="disabled">Seleccionar grupo de días</option>
                                @if(isset($dias))
                                    @foreach($dias as $dia)
                                        <option {{$dia->id == $event->dia_id ? 'selected' : ''}} value="{{$dia->id}}">{{$dia->nombre}}</option>
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
                                        <option {{$menu->id == $event->menu_id ? 'selected' : ''}} value="{{$menu->id}}">{{$menu->nombre}}</option>
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
                    <form id="create_graduate_party" action="{{route('create.discount', $event->id)}}" method="POST">
                        @csrf

                        <div class="mb-3" id="descuento">
                            <label for="descuento" class="form-label">Descuento:</label>
                            <input type="number" step="0.01" class="form-control" name="descuento">
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
    <div class="modal modal-center fade" id="createGraduateParty" tabindex="-1"
         aria-labelledby="createGraduatePartyLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="createGraduatePartyLabel">Añadir persona</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="create_graduate_party" action="{{route('store.student')}}" method="POST">
                        @csrf
                        <input class="" type="hidden" name="event_id" value="{{$event->id}}" id="graduation_id">

                        <div class="mb-3" id="estudiante_select">
                            <label for="estudiante_id" class="form-label">Grupo familiar:</label>
                            <select id="estudiante_id" class="form-select" name="estudiante_id">
                                <option selected="true" disabled="disabled">Seleccionar egresado</option>
                                @if(isset($event->persons))
                                    @foreach($event->persons as $person)
                                        <option value="{{$person->id}}">{{$person->nombre}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>


                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre completo</label>
                            <input type="text" class="form-control" id="nombre" name="nombre">
                        </div>

{{--                        <div class="mb-3" id="menu_id">--}}
{{--                            <label for="menu_id" class="form-label">Menú:</label>--}}
{{--                            <select class="form-select" name="menu_id">--}}
{{--                                <option selected="true" disabled="disabled">Seleccionar menú</option>--}}
{{--                                @if(isset($menus))--}}
{{--                                    @foreach($menus as $menu)--}}
{{--                                        <option value="{{$menu->id}}">{{$menu->nombre}}</option>--}}
{{--                                    @endforeach--}}
{{--                                @endif--}}
{{--                            </select>--}}
{{--                        </div>--}}

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

                        <div class="mb-3" id="fecha_pago">
                            <label for="fecha_pago" class="form-label">Fecha de pago</label>
                            <input name="fecha_pago" type="date" class="form-control">
                        </div>

                        <div class="mb-3" id="forma_pago_id">
                            <label for="forma_pago_id" class="form-label">Forma de Pago:</label>
                            <select class="form-select" name="forma_pago_id">
                                <option selected="true" disabled="disabled">Seleccionar forma de
                                    pago
                                </option>
                                @if(isset($formasPago))
                                    @foreach($formasPago as $formaPago)
                                        <option
                                            value="{{$formaPago->id}}">{{$formaPago->nombre}}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="mb-3" id="medio_pago_id">
                            <label for="medio_pago_id" class="form-label">Medio de Pago:</label>
                            <select class="form-select" name="medio_pago_id">
                                <option selected="true" disabled="disabled">Seleccionar medio de
                                    pago
                                </option>
                                @if(isset($mediosPago))
                                    @foreach($mediosPago as $formaPago)
                                        <option
                                                value="{{$formaPago->id}}">{{$formaPago->metodo}}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="mb-3" id="email">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" name="email">
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

        <div class="mt-3">
            <table id="events" class="display nowrap mt-5" style="width:100%">
                <thead>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                </thead>
            </table>
        </div>


{{--    modal de estudiante--}}

    @foreach($graduates as $graduate)
        <!-- Modal -->
        <div class="modal modal-center fade" id="editGraduate{{$graduate->id}}" tabindex="-1"
             aria-labelledby="editGraduate{{$graduate->id}}Label"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="editGraduate{{$graduate->id}}Label">Editar egresado
                            {{$graduate->nombre}}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="create_graduate_party" action="{{route('edit.egresado', $graduate->id)}}"
                              method="POST">
                            @method('PUT')
                            @csrf
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre completo</label>
                                    <input value="{{$graduate->nombre}}" type="text" class="form-control" id="nombre"
                                           name="nombre">
                                </div>

                                <div class="mb-3" id="menu_id">
                                    <label for="menu_id" class="form-label">Menú especial:</label>
                                    <select class="form-select" name="menu_especial_id">
                                        <option selected="true" disabled="disabled">Seleccionar menú especial</option>
                                        @if(isset($specialMenu))
                                            @foreach($specialMenu as $menu)
                                                <option {{$menu->id == $graduate->menu_especial_id ? 'selected' : ''}} value="{{$menu->id}}">{{$menu->nombre}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="mb-3" id="fecha_pago">
                                    <label for="fecha_pago" class="form-label">Fecha de pago</label>
                                    <input value="{{$graduate->fecha_pago}}" name="fecha_pago" type="date" class="form-control">
                                </div>

                                <div class="mb-3" id="forma_pago_id">
                                    <label for="forma_pago_id" class="form-label">Forma de Pago:</label>
                                    <select class="form-select" name="forma_pago_id">
                                        <option selected="true" disabled="disabled">Seleccionar forma de
                                            pago
                                        </option>
                                        @if(isset($formasPago))
                                            @foreach($formasPago as $formaPago)
                                                <option {{$formaPago->id == $graduate->forma_pago_id ? 'selected' : ''}}
                                                        value="{{$formaPago->id}}">{{$formaPago->nombre}}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="mb-3" id="medio_pago_id">
                                    <label for="medio_pago_id" class="form-label">Medio de Pago:</label>
                                    <select class="form-select" name="medio_pago_id">
                                        <option selected="true" disabled="disabled">Seleccionar medio de
                                            pago
                                        </option>
                                        @if(isset($mediosPago))
                                            @foreach($mediosPago as $formaPago)
                                                <option {{$formaPago->id == $graduate->medio_pago_id ? 'selected' : ''}}
                                                        value="{{$formaPago->id}}">{{$formaPago->metodo}}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="mb-3" id="email">
                                    <label for="email" class="form-label">Email:</label>
                                    <input value="{{$graduate->email}}" type="email" class="form-control" name="email">
                                </div>

                                <div class="mb-3" id="telefono">
                                    <label for="telefono" class="form-label">Teléfono:</label>
                                    <input value="{{$graduate->telefono}}" type="text" class="form-control" name="telefono">
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

        $('#eliminarEvento').on('click', function (){
            swal.fire({
                title: '<strong>¿Seguro que deseas eliminar al evento? Los cambios no podran deshacerse</strong>',
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
                        let route = '{{route('deleteEvent', $event->id)}}';
                        let id = '{{$event->id}}';
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
                                    title: '<strong>Evento eliminado</strong>',
                                    // footer: '<a href="">Why do I have this issue?</a>'
                                })
                                    .then(function(){
                                        location.replace('{{route('dashboard')}}')
                                    })
                            },
                        })
                    } else if (result.isDenied) {
                        Swal.fire('No se eliminó ningún egresado', '', 'info')
                    }
                })
        })


        function openEditModal(id)
        {
            $("#editGraduate"+id).modal('show');
        }

        function editDiscount()
        {
            $("#editDiscount").modal('show');
        }


        $(document).ready(function () {

            $('#estudiante_select').hide()
            $('#forma_pago_id').show()
            $('#menores_12').show()
            $('#menores_5').show()
            $('#email').show()
            $('#fecha_pago').show()
            $('#telefono').show()
            $('#familiares').show()
            $('#medio_pago_id').show()
            //


            $('#is_graduated').change(
                function () {
                    if (!$(this).is(':checked')) {
                        $('#estudiante_select').show();
                        $('#forma_pago_id').hide()
                        $('#menores_12').hide()
                        $('#menores_5').hide()
                        $('#email').hide()
                        $('#fecha_pago').hide()
                        $('#telefono').hide()
                        $('#menu_id').show()
                        $('#familiares').hide()
                        $('#medio_pago_id').hide()
                    } else {
                        $('#estudiante_select').hide();
                        $('#forma_pago_id').show()
                        $('#menores_12').show()
                        $('#menores_5').show()
                        $('#email').show()
                        $('#fecha_pago').show()
                        $('#telefono').show()
                        $('#menu_id').show()
                        $('#familiares').show()
                        $('#medio_pago_id').show()
                    }
                });

            let url = '{{route('list.graduateParty', $event->id)}}'
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
                dom: 'Bfrtilp',
                columnDefs: [{
                    "defaultContent": "-",
                    "targets": "_all"
                }],
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
                        title: "PERSONAS",
                        data: 'personas',
                        width: "5%",
                    },
                    {
                        title: "MENU ESPECIAL",
                        data: 'menu_especial'
                    },
                    {
                        title: "FECHA PAGO",
                        data: 'fecha_pago'
                    },
                    {
                        title: "FORMA PAGO",
                        data: 'forma_pago'
                    },
                    {
                        title: "METODO PAGO",
                        data: 'menu'
                    },
                    {
                        title: "EMAIL",
                        data: 'email'
                    },
                    {
                        title: "TELÉFONO",
                        data: 'telefono'
                    },
                    {
                        title: "TOTAL",
                        data: 'total'
                    },
                    {
                        title: "OPCION",
                        width: "5%",
                        sortable: false,
                        "render": function (data, type, full, meta) {
                            let id = full.id;
                            let nombre = full.nombre;
                            let personas = full.personas;

                            return `<a title="Ver familiares" href="/admin/estudiante/${id}"
                            style="cursor:
                            pointer; text-decoration: none;
                            "> <i
                            class="fa-solid fa-eye"></i> </a>` +
                                `<a title="Editar egresado" onclick="openEditModal(${id})"
                            style="cursor:
                            pointer;
                            "> <i
                            class="fa-solid fa-pen-to-square"></i> </a>`;;
                        }
                    },
                ],
            })

        })




    </script>
@stop
