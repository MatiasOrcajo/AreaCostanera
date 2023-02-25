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
            </thead>
        </table>
    </div>
@stop

@section('css')
@stop

@section('js')

    <script>

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
                    { title: "GRUPO",
                        data: 'nombre'
                    },
                    {
                        title: "OPCION",
                        width: "10%",
                        sortable: false,
                        "render": function ( data, type, full, meta ) {
                            let id = full.id;
                            console.log(full);
                            return `<a title="Ver escuela" href="${Constants.BASE_URL}admin/escuela/${id}"> <i class="fa-solid fa-eye"></i> </a>`;
                        }
                    },
                ]
            })
        })


    </script>

@stop
