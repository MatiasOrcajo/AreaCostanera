@extends('appAdmin')
@extends('adminlte::page')

@section('title', 'Grupos de días')

@section('content_header')
    <h1>Menús</h1>
@stop

@section('content')
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createDayGroup">
        Crear nuevo menú
    </button>

    <!-- Modal -->
    <div class="modal modal-center fade" id="createDayGroup" tabindex="-1"
         aria-labelledby="createDayGroupLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="createDayGroupLabel">Crear menú</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="create_graduate_party" action="{{route('store.menu')}}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del menú:</label>
                            <small>(Por ejemplo: "Clásico")</small>
                            <input type="text" class="form-control" id="nombre" name="nombre">
                        </div>
                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio:</label>
                            <small></small>
                            <input type="number" class="form-control" id="precio" name="precio">
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

    @foreach($menus as $menu)
        <!-- Modal -->
        <div class="modal modal-center fade" id="editMenu{{$menu->id}}" tabindex="-1"
             aria-labelledby="editMenu{{$menu->id}}Label"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="editMenu{{$menu->id}}Label">Editar menú</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="create_graduate_party" action="{{route('edit.menu', $menu->id)}}" method="POST">
                            @method('PUT')
                            @csrf
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre del menú:</label>
                                <small>(Por ejemplo: "Clásico")</small>
                                <input type="text" class="form-control" value="{{$menu->nombre}}" id="nombre"
                                       name="nombre">
                            </div>
                            <div class="mb-3">
                                <label for="precio" class="form-label">Precio:</label>
                                <small></small>
                                <input value="{{$menu->precio}}" type="number" class="form-control" id="precio"
                                       name="precio">
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




        <!-- Modal HISTORIAL -->
        <div class="modal modal-center fade" id="showHistory{{$menu->id}}" tabindex="-1"
             aria-labelledby="showHistory{{$menu->id}}Label"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="width: 150%">
                    <div class="modal-body">
                        <table id="menuHistory{{$menu->id}}" class="display nowrap mt-5" style="width:100%">
                            <thead>
                            <th></th>
                            <th></th>
                            </thead>
                        </table>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>

                    </div>

                </div>
            </div>
        </div>
    @endforeach

    <div class="mt-3">
        <table id="menus" class="display nowrap mt-5" style="width:100%">
            <thead>
            <th></th>
            <th></th>
            </thead>
        </table>
    </div>
@stop

@section('css')
@stop

@section('js')

    <script>

        function openEditModal(id) {
            $("#editMenu" + id).modal('show');
        }

        function openHistoryModal(id) {
            $("#showHistory" + id).modal('show');

            let url = 'get/history-records/menu/' + id
            let table = $(`#menuHistory${id}`).DataTable();
            table.destroy();
            $(`#menuHistory${id}`).empty();


            $(`#menuHistory${id}`).DataTable({
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
                        title: "FECHA DE CAMBIO",
                        data: 'fecha'
                    },
                    {
                        title: "PRECIO ANTERIOR",
                        data: 'precio_anterior'
                    },
                    {
                        title: "PRECIO NUEVO",
                        data: 'precio_nuevo'
                    },
                ]
            })

        }

        $(document).ready(function () {
            let url = '{{route('list.menus')}}'
            let table = $('#menus').DataTable();
            table.destroy();
            $('#menus').empty();


            $('#menus').DataTable({
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
                        title: "MENU",
                        data: 'nombre'
                    },
                    {
                        title: "PRECIO",
                        data: 'precio'
                    },
                    {
                        title: "OPCION",
                        width: "10%",
                        sortable: false,
                        "render": function (data, type, full, meta) {
                            let id = full.id;
                            return `<a title="Editar menú" onclick="openEditModal(${id})" style="cursor: pointer"> <i class="fa-solid fa-pen-to-square"></i> </a>` + `<i title="Historial" onclick="openHistoryModal(${id})" style="cursor: pointer" class="fa fa-history" aria-hidden="true"></i>`;
                        }
                    },
                ]
            })
        })


        //list.menu.historyRecords


    </script>

@stop
