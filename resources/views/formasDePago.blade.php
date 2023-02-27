@extends('appAdmin')
@extends('adminlte::page')

@section('title', 'Grupos de días')

@section('content_header')
    <h1>Formas de Pago</h1>
@stop

@section('content')
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createDayGroup">
        Crear nueva forma de pago
    </button>

    <!-- Modal -->
    <div class="modal modal-center fade" id="createDayGroup" tabindex="-1"
         aria-labelledby="createDayGroupLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="createDayGroupLabel">Crear forma de pago</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="create_graduate_party" action="{{route('store.formaPago')}}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Forma:</label>
                            <small>(Por ejemplo: "3 cuotas")</small>
                            <input type="text" class="form-control" id="nombre" name="nombre">
                            <label for="interes" class="form-label">Interés:</label>
                            <small>(solo números)</small>
                            <input type="number" class="form-control" id="interes" name="interes">
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


    @foreach($paymentTypes as $type)
        <!-- Modal -->
        <div class="modal modal-center fade" id="editPaymentType{{$type->id}}" tabindex="-1"
             aria-labelledby="editPaymentType{{$type->id}}Label"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="editPaymentType{{$type->id}}Label">Editar forma de pago</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="create_graduate_party" action="{{route('edit.payment', $type->id)}}" method="POST">
                            @method('PUT')
                            @csrf
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Forma:</label>
                                <small>(Por ejemplo: "3 cuotas")</small>
                                <input value="{{$type->nombre}}" type="text" class="form-control" id="nombre" name="nombre">
                                <label for="interes" class="form-label">Interés:</label>
                                <small>(solo números)</small>
                                <input value="{{$type->interes}}" type="number" class="form-control" id="interes" name="interes">
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

    <div class="mt-3">
        <table id="payment_type" class="display nowrap mt-5" style="width:100%">
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
        function openEditModal(id)
        {
            $("#editPaymentType"+id).modal('show');
        }

        $(document).ready(function(){
            let url = '{{route('list.formasPago')}}'
            let table = $('#payment_type').DataTable();
            table.destroy();
            $('#payment_type').empty();


            $('#payment_type').DataTable({
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
                    { title: "FORMA DE PAGO",
                        data: 'nombre'
                    },
                    { title: "INTERÉS",
                        data: 'interes'
                    },
                    {
                        title: "OPCION",
                        width: "10%",
                        sortable: false,
                        "render": function ( data, type, full, meta ) {
                            let id = full.id;
                            return `<a title="Editar forma de pago" style="cursor: pointer" onclick="openEditModal(${id})"> <i class="fa-solid fa-eye"></i> </a>`;
                        }
                    },
                ]
            })
        })


    </script>

@stop
