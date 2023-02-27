@extends('appAdmin')
@extends('adminlte::page')

@section('title', 'Evento '.$event->school->nombre)

@section('content_header')
    <h1>Fiesta de la escuela {{$event->school->nombre}} de la fecha {{$event->fecha}}</h1>
@stop

@section('content')
    <h5>Lista de egresados:</h5>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createGraduateParty">
        Agregar persona
    </button>

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

                        <div class="form-check mb-3">
                            <input name="is_graduated" class="form-check-input" type="checkbox"
                                   id="is_graduated"
                                   checked>
                            <label class="form-check-label" for="is_graduated">
                                Es egresado
                            </label>
                        </div>

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

                        <div class="mb-3" id="menu_id">
                            <label for="menu_id" class="form-label">Menú:</label>
                            <select class="form-select" name="menu_id">
                                <option selected="true" disabled="disabled">Seleccionar menú</option>
                                @if(isset($menus))
                                    @foreach($menus as $menu)
                                        <option value="{{$menu->id}}">{{$menu->nombre}}</option>
                                    @endforeach
                                @endif
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

                        <div class="mb-3" id="familiares">
                            <label for="familiares" class="form-label">Familiares totales:</label>
                            <input name="familiares" type="number" class="form-control">
                        </div>

                        <div class="mb-3" id="menores_12">
                            <label for="menores_12" class="form-label">Menores de 12:</label>
                            <input name="menores_12" type="number" class="form-control">
                        </div>

                        <div class="mb-3" id="menores_5">
                            <label for="menores_5" class="form-label">Menores de 5:</label>
                            <input type="number" class="form-control" name="menores_5">
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

    <!-- Modal -->
    <div class="modal fade" id="estudianteModal" tabindex="-1" role="dialog" aria-labelledby="estudianteModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="estudianteModalLabel"></h5>
                </div>
                <div class="modal-body">
                    <table id="estudianteTable" class="display nowrap mt-5" style="width:100%">
                        <thead>
                            <th></th>
                            <th></th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

@stop

@section('css')
@stop

@section('js')
    <script>
        function showStudentFamily(id, nombre, familiares){
            $('#estudianteModal').modal('show');

            let url = '/api/egresado/'+id
            let table = $('#estudianteTable').DataTable();
            table.destroy();
            $('#estudianteTable').empty();

            $('#estudianteModalLabel').text(`Egresado ${nombre}`)

            $('#estudianteTable').DataTable({

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
                "columns": [
                    {
                        title: "NOMBRE",
                        data: 'nombre'
                    },
                    {
                        title: 'MENU ESPECIAL',
                        data: 'menu_especial'
                    }
                ]


            })


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
                        $('#menu_id').hide()
                        $('#familiares').hide()
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
                columnDefs: [{
                    "defaultContent": "-",
                    "targets": "_all"
                }],
                "columns": [
                    {
                        title: "NOMBRE",
                        data: 'nombre'
                    },
                    {
                        title: "MENÚ",
                        data: 'menu'
                    },
                    {
                        title: "PERSONAS",
                        data: 'personas',
                        width: "5%",
                    },
                    {
                        title: "M 12",
                        data: 'menores_12',
                        width: "5%",
                    },
                    {
                        title: "M 5",
                        data: 'menores_5',
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

                            return `<a title="Ver familiares" onclick="showStudentFamily(${id}, '${nombre}',
                            ${personas})"
                            style="cursor:
                            pointer;
                            "> <i
                            class="fa-solid fa-eye"></i> </a>`;
                        }
                    },
                ],
                buttons: [
                    'excel'
                ]
            })

        })




    </script>
@stop
