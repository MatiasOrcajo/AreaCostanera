@extends('appAdmin')
@extends('adminlte::page')

@section('title', 'Grupos de días')

@section('content_header')
    <h1>Grupos de días</h1>
@stop

@section('content')
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createDayGroup">
        Crear nuevo grupo
    </button>

    <!-- Modal -->
    <div class="modal modal-center fade" id="createDayGroup" tabindex="-1"
         aria-labelledby="createDayGroupLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="createDayGroupLabel">Crear grupos de días</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="create_graduate_party" action="{{route('store.days')}}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Días:</label>
                            <small>(Por ejemplo: "Lunes a Miércoles")</small>
                            <input type="text" class="form-control" id="nombre" name="nombre">
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
        <table id="days_groups" class="display nowrap mt-5" style="width:100%">
            <thead>
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

    @foreach($days as $day)
        <!-- Modal -->
        <div class="modal modal-center fade" id="editDay{{$day->id}}" tabindex="-1"
             aria-labelledby="editDay{{$day->id}}Label"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="editDay{{$day->id}}Label">Editar día</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="create_graduate_party" action="{{route('edit.day', $day->id)}}" method="POST">
                            @method('PUT')
                            @csrf
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre:</label>
                                <input value="{{$day->nombre}}" type="text" class="form-control" id="nombre"
                                       name="nombre">
                            </div>
                            <h4>Descuentos</h4>
                            <div class="mb-3">
                                <label for="20_a_30" class="form-label">20 a 30:</label>
                                <input value="{{$day->descuentos["descuento_20_a_30"]}}" type="number" class="form-control" id="20_a_30"
                                       name="20_a_30">
                            </div>
                            <div class="mb-3">
                                <label for="31_a_50" class="form-label">31 a 50:</label>
                                <input value="{{$day->descuentos["descuento_31_a_50"]}}" type="number" class="form-control" id="31_a_50"
                                       name="31_a_50">
                            </div>
                            <div class="mb-3">
                                <label for="51_a_70" class="form-label">51 a 70:</label>
                                <input value="{{$day->descuentos["descuento_51_a_70"]}}" type="number" class="form-control" id="51_a_70"
                                       name="51_a_70">
                            </div>
                            <div class="mb-3">
                                <label for="71_a_100" class="form-label">71 a 100:</label>
                                <input value="{{$day->descuentos["descuento_71_a_100"]}}" type="number" class="form-control" id="71_a_100"
                                       name="71_a_100">
                            </div>
                            <div class="mb-3">
                                <label for="101_a_150" class="form-label">101 a 150:</label>
                                <input value="{{$day->descuentos["descuento_101_a_150"]}}" type="number" class="form-control" id="101_a_150"
                                       name="101_a_150">
                            </div>
                            <div class="mb-3">
                                <label for="151_o_mas" class="form-label">151 o mas:</label>
                                <input value="{{$day->descuentos["descuento_151_o_mas"]}}" type="number" class="form-control" id="151_o_mas"
                                       name="151_o_mas">
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

    <script>

        function openEditModal(id)
        {
            $("#editDay"+id).modal('show');
        }

        $(document).ready(function(){
            let url = '{{route('list.days')}}'
            let table = $('#days_groups').DataTable();
            table.destroy();
            $('#days_groups').empty();


            $('#days_groups').DataTable({
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
                    { title: "NOMBRE",
                        data: 'nombre'
                    },
                    { title: "20 a 30",
                        data: '20_a_30'
                    },
                    { title: "31 a 50",
                        data: '31_a_50'
                    },
                    { title: "51 a 70",
                        data: '51_a_70'
                    },
                    { title: "71 a 100",
                        data: '71_a_100'
                    },
                    { title: "101 a 150",
                        data: '101_a_150'
                    },
                    { title: "151 o mas",
                        data: '151_o_mas'
                    },
                    {
                        title: "OPCION",
                        width: "10%",
                        sortable: false,
                        "render": function ( data, type, full, meta ) {
                            let id = full.id;
                            console.log(full);
                            return `<a title="Ver escuela" onclick="openEditModal(${id})"> <i class="fa-solid
                            fa-pen-to-square"></i> </a>`;
                        }
                    },
                ]
            })
        })


    </script>

@stop
