@extends('appAdmin')
@extends('adminlte::page')

@section('title', 'Escuelas')

@section('content_header')
    <h1>Eventos terminados</h1>
@stop

@section('content')

    <!-- table section -->

    <div class="mt-3">
        <table id="schools" class="display nowrap mt-5" style="width:100%">
            <thead>
            <tr>
                <th></th>
                <th></th>
            </tr>
            </thead>
        </table>
    </div>

@stop

@section('css')
@stop

@section('js')

    <script>

        function openEditModal(id)
        {
            $("#editSchool"+id).modal('show');
        }

        $(document).ready(function(){
            let url = '{{route('list.finishedEvents')}}'
            let table = $('#schools').DataTable();
            table.destroy();
            $('#schools').empty();


            $('#schools').DataTable({
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
                        data: 'evento'
                    },
                    { title: "FECHA",
                        data: 'fecha'
                    },
                    {
                        title: "OPCION",
                        width: "10%",
                        sortable: false,
                        "render": function ( data, type, full, meta ) {
                            let slug = full.slug;
                            return `<a title="Ver evento" href="/admin/evento/${slug}"
                            style="cursor:
                            pointer; text-decoration: none;
                            "> <i
                            class="fa-solid fa-eye"></i> </a>`;
                        }
                        //<i class="fa-solid fa-pen-to-square"></i>
                    },
                ]
            })
        })


    </script>



@stop
