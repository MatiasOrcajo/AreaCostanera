@extends('appAdmin')
@extends('adminlte::page')

@section('title', 'Evento '.$event->school->nombre)

@section('content_header')
    <h1>Fiesta de la escuela {{$event->school->nombre}} de la
        fecha {{\Carbon\Carbon::parse($event->fecha)->format('d-m-Y')}}</h1>
@stop

@section('content')
    <!-- Button trigger modal -->
    <a href="{{route('show.graduate', $event->slug)}}">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createGraduateParty">
            Volver
        </button>
    </a>

    <ul class="nav nav-tabs mt-4 mb-3" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="egresados_tab-tab" data-bs-toggle="tab" data-bs-target="#egresados_tab"
                    type="button" role="tab" aria-controls="egresados_tab" aria-selected="true">Listado de Egresados
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="invitados_tab-tab" data-bs-toggle="tab" data-bs-target="#invitados_tab"
                    type="button" role="tab" aria-controls="invitados_tab" aria-selected="false">Listado de Invitados
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="menues_tab-tab" data-bs-toggle="tab" data-bs-target="#menues_tab" type="button"
                    role="tab" aria-controls="menues_tab" aria-selected="false">Menues Especiales
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="deudores_tab-tab" data-bs-toggle="tab" data-bs-target="#deudores_tab"
                    type="button" role="tab" aria-controls="deudores_tab" aria-selected="false">Deudores
            </button>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="egresados_tab" role="tabpanel" aria-labelledby="home-tab">
            <table id="listado_egresados" class="display nowrap mt-5" style="width:100%">
                <thead>
                <th></th>
                </thead>
            </table>
        </div>
        <div class="tab-pane fade" role="tabpanel" id="invitados_tab" aria-labelledby="profile-tab">
            <table id="listado_invitados" class="display nowrap mt-5" style="width:100%">
                <thead>
                <th></th>
                <th></th>
                </thead>
            </table>
        </div>
        <div class="tab-pane fade" id="menues_tab" role="tabpanel" aria-labelledby="contact-tab">
            <table id="listado_menues" class="display nowrap mt-5" style="width:100%">
                <thead>
                <th></th>
                <th></th>
                </thead>
            </table>
        </div>
        <div class="tab-pane fade" id="deudores_tab" role="tabpanel" aria-labelledby="contact-tab">
            <table id="listado_deudores" class="display nowrap mt-5" style="width:100%">
                <thead>
                <th></th>
                <th></th>
                </thead>
            </table>
        </div>
    </div>

@stop

@section('css')
@stop

@section('js')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>


        $(document).ready(function () {

            let url = '{{route('list.students.table', $event->id)}}'
            let table = $('#listado_egresados').DataTable();
            table.destroy();
            $('#listado_egresados').empty();


            $('#listado_egresados').DataTable({

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
                        dom: 'Bfrtip',
                        className: 'btn btn-info',
                        customize: function (win) {
                            $(win.document.body)
                                .css('font-size', '10pt')
                                .prepend(
                                    '<h1>EGRESADOS</h1>'
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
                ],
            })


            /**
             * INVITADOS
             */

            $('#listado_invitados').DataTable().destroy();
            $('#listado_invitados').empty();


            $('#listado_invitados').DataTable({

                deferRender: true,
                "autoWidth": true,
                "paging": true,
                stateSave: true,
                "processing": true,
                "ajax": '{{route('list.guests.table', $event->id)}}',
                dom: 'Bfrtilp',
                columnDefs: [{
                    "defaultContent": "-",
                    "targets": "_all"
                }],
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
                        dom: 'Bfrtip',
                        className: 'btn btn-info',
                        customize: function (win) {
                            $(win.document.body)
                                .css('font-size', '10pt')
                                .prepend(
                                    '<h1>INVITADOS</h1>'
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
                        title: "TELEFONO",
                        data: 'telefono'
                    },
                ],
            })


            /**
             * MENUS ESPECIALES
             */

            $('#listado_menues').DataTable().destroy();
            $('#listado_menues').empty();


            $('#listado_menues').DataTable({

                deferRender: true,
                "autoWidth": true,
                "paging": true,
                stateSave: true,
                "processing": true,
                "ajax": '{{route('list.menus.table', $event->id)}}',
                dom: 'Bfrtilp',
                columnDefs: [{
                    "defaultContent": "-",
                    "targets": "_all"
                }],
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
                        dom: 'Bfrtip',
                        className: 'btn btn-info',
                        customize: function (win) {
                            $(win.document.body)
                                .css('font-size', '10pt')
                                .prepend(
                                    '<h1>MENUS ESPECIALES</h1>'
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
                        title: "MENU",
                        data: 'menu'
                    },
                ],
            })

            /**
             * DEUDORES
             */

            $('#listado_deudores').DataTable().destroy();
            $('#listado_deudores').empty();


            $('#listado_deudores').DataTable({

                deferRender: true,
                "autoWidth": true,
                "paging": true,
                stateSave: true,
                "processing": true,
                "ajax": '{{route('list.debtors.table', $event->id)}}',
                dom: 'Bfrtilp',
                columnDefs: [{
                    "defaultContent": "-",
                    "targets": "_all"
                }],
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
                        dom: 'Bfrtip',
                        className: 'btn btn-info',
                        customize: function (win) {
                            $(win.document.body)
                                .css('font-size', '10pt')
                                .prepend(
                                    '<h1>DEUDORES</h1>'
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
                        title: "FECHA ESTIPULADA",
                        data: 'fecha_estipulada'
                    },
                ],
            })

        })

    </script>
@stop
