@extends('app')
@extends('adminlte::page')

@section('title', 'Escuelas')

@section('content_header')
    <h1>Escuelas</h1>
@stop

@section('content')
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createGraduateParty">
        Crear nueva escuela
    </button>

    <!-- Modal -->
    <div class="modal modal-center fade" id="createGraduateParty" tabindex="-1"
         aria-labelledby="createGraduatePartyLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="createGraduatePartyLabel">Crear Escuela</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="create_graduate_party" action="{{route('store.school')}}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
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


    <!-- table section -->

   <div class="mt-3">
       <table id="schools" class="display nowrap mt-5" style="width:100%">
           <thead>
           <tr>
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
    $(document).ready(function(){
        let url = '{{route('list.schools')}}'
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
                "defaultContent": "-asda",
                "targets": "_all"
            }],
            "columns": [
                { title: "NOMBRE",
                  data: 'nombre'
                },
                {
                    title: "OPCION",
                    width: "10%",
                    sortable: false,
                    "render": function ( data, type, full, meta ) {
                        let id = full.id;
                        console.log(full);
                        return '<a title="Seleccione" onclick="selec_equipmen('+id+');"> <i class="fa fa-bullseye"></i> </a>';
                    }
                },
            ]
        })
    })


</script>



@stop
