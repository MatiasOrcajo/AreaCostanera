@extends('appAdmin')
@extends('adminlte::page')

@section('title', 'Historial')

@section('content_header')
    <h1>Historial</h1>
@stop

@section('content')
    <!-- Button trigger modal -->
    {{--    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createGraduateParty">--}}
    {{--        Volver--}}
    {{--    </button>--}}

    <table id="listado_historial" class="display nowrap mt-5" style="width:100%">
        <thead>
        <th></th>
        </thead>
    </table>


@stop

@section('css')
@stop

@section('js')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>


        $(document).ready(function () {

            let url = '{{route('list.history')}}'
            let table = $('#listado_historial').DataTable();
            table.destroy();
            $('#listado_historial').empty();


            $('#listado_historial').DataTable({

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
                        dom: 'Bfrtip',
                        className: 'btn btn-info',
                        customize: function (win) {
                            $(win.document.body)
                                .css('font-size', '10pt')
                                .prepend(
                                    '<h1>HISTORIAL</h1>'
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
                        title: "USUARIO",
                        data: 'user'
                    },
                    {
                        title: "MENSAJE",
                        data: 'message',
                        render: function(data, type, row) {
                            // 'display' es el tipo utilizado para mostrar datos en la tabla
                            if (type === 'display') {
                                // Utiliza jQuery para crear un elemento div y establecer el HTML
                                return $('<div/>').html(data).text();
                            }
                            return data;
                        }
                    },
                    {
                        title: "FECHA",
                        data: 'date'
                    },
                    // {
                    //     title: "OPCION",
                    //     width: "5%",
                    //     sortable: false,
                    //     "render": function (data, type, full, meta) {
                    //         let id = full.id;
                    //         let nombre = full.nombre;
                    //         let personas = full.personas;
                    //
                    //         return `<a title="Ver Informes" href="/admin/informes/eventos/${id}"
                    //         style="cursor:
                    //         pointer; text-decoration: none;
                    //         "> <i
                    //         class="fa-solid fa-eye"></i> </a>`;
                    //     }
                    // },
                ],
            })



        })

    </script>
@stop
