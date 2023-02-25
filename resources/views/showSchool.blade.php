@extends('appAdmin')
@extends('adminlte::page')

@section('title', 'Escuela '.$escuela->nombre)

@section('content_header')
    <h1>Escuela {{$escuela->nombre}}</h1>
@stop

@section('content')
    <h5>Eventos asignados:</h5>

    <div class="mt-3">
        <table id="events" class="display nowrap mt-5" style="width:100%">
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
                    { title: "EVENTO",
                        data: 'nombre'
                    },
                    {
                        title: "OPCION",
                        width: "10%",
                        sortable: false,
                        "render": function ( data, type, full, meta ) {
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
