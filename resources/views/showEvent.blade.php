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
                    <form id="create_graduate_party" action="{{route('store.school')}}" method="POST">
                        @csrf
                        <input class="" type="hidden" value="{{$event->id}}" id="graduation_id">

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" value="is_graduated" id="is_graduated"
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

                        <div class="mb-3">
                            <label for="menu_id" class="form-label">Menú:</label>
                            <select id="menu_id" class="form-select" name="menu_id">
                                <option selected="true" disabled="disabled">Seleccionar menú</option>
                                @if(isset($menus))
                                    @foreach($menus as $menu)
                                        <option value="{{$menu->id}}">{{$menu->nombre}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="forma_pago_id" class="form-label">Forma de Pago:</label>
                            <select id="forma_pago_id" class="form-select" name="forma_pago_id">
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
                        <div class="mb-3">
                            <label for="menores_12" class="form-label">Menores de 12:</label>
                            <input name="menores_12" type="number" class="form-control" id="menores_12">
                        </div>

                        <div class="mb-3">
                            <label for="menores_5" class="form-label">Menores de 5:</label>
                            <input type="number" class="form-control" id="menores_5" name="menores_5">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>

                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono:</label>
                            <input type="text" class="form-control" id="telefono" name="telefono">
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

    {{--    <div class="mt-3">--}}
    {{--        <table id="events" class="display nowrap mt-5" style="width:100%">--}}
    {{--            <thead>--}}
    {{--            <th></th>--}}
    {{--            <th></th>--}}
    {{--            <th></th>--}}
    {{--            <th></th>--}}
    {{--            <th></th>--}}
    {{--            <th></th>--}}
    {{--            <th></th>--}}
    {{--            <th></th>--}}
    {{--            </thead>--}}
    {{--        </table>--}}
    {{--    </div>--}}
@stop

@section('css')
@stop

@section('js')
    <script>
        //nombre
        //grupo familiar -> nombre y apellido egresado
        //menores 12
        //menores 5
        //menu
        //menu especial
        //fecha pago
        //forma pago
        //total
        //email
        //telefono


        $(document).ready(function () {

            $('#estudiante_select').hide()

            $('#is_graduated').change(
                function () {
                    if (!$(this).is(':checked')) {
                        $('#estudiante_select').show();
                    } else {
                        $('#estudiante_select').hide();
                    }
                });

            let url = '{{route('list.schools')}}'
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
                        title: "OPCION",
                        width: "10%",
                        sortable: false,
                        "render": function (data, type, full, meta) {
                            let id = full.id;
                            console.log(full);
                            return `<a title="Seleccione" href="${Constants.BASE_URL}admin/escuela/${id}"> <i class="fa fa-bullseye"></i> </a>`;
                        }
                    },
                ]
            })
        })


    </script>
@stop
