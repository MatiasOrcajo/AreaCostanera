@extends('appAdmin')
@extends('adminlte::page')

@section('title', 'Grupos de días')

@section('content_header')
    <h1>Interés</h1>
@stop

@section('content')

    <div class="mt-3">
        <table id="menus" class="display nowrap mt-5" style="width:100%">
            <thead>
            <th></th>
            <th></th>
            </thead>
        </table>
    </div>

        <!-- Modal -->
        <div class="modal modal-center fade" id="editSpecialMenus{{$interes->id}}" tabindex="-1"
             aria-labelledby="editSpecialMenus{{$interes->id}}Label"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="editSpecialMenus{{$interes->id}}Label">Editar interés</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="create_graduate_party" action="{{route('edit.interes')}}"
                              method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="interes" class="form-label">Interes:</label>
                                <input value="{{$interes->interes}}" type="number" class="form-control" id="interes"
                                       name="interes">
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

@stop

@section('css')
@stop

@section('js')

    <script>

        function openEditModal(id)
        {
            $("#editSpecialMenus"+id).modal('show');
        }


        $(document).ready(function(){
            let url = '{{route('listInteres')}}'
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
                    { title: "INTERES",
                        data: 'interes'
                    },
                    {
                        title: "OPCION",
                        width: "10%",
                        sortable: false,
                        "render": function ( data, type, full, meta ) {
                            let id = full.id;
                            return `<a title="Editar interes" onclick="openEditModal(${id})"> <i class="fa-solid
                            fa-pen-to-square"></i> </a>`;
                        }
                    },
                ]
            })
        })


    </script>

@stop
