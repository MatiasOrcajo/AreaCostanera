@extends('appAdmin')
@extends('adminlte::page')

@section('title', 'Descuentos comensales')

@section('content_header')
    <h1>Descuentos por cantidad de comensales en evento social</h1>
@stop

@section('content')

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session()->has('Errors'))
        <div class="alert alert-danger">
            {{session()->get('Errors')}}
        </div>
    @endif

    @if(session()->has('Success'))
        <div class="alert alert-success">
            {{ session()->get('Success') }}
        </div>
    @endif

    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createDiscount">
        Crear descuento
    </button>


    <!-- Modal -->
    <div class="modal modal-center fade" id="createDiscount" tabindex="-1"
         aria-labelledby="createDayGroupLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="createDayGroupLabel">Crear descuento por grupo de días</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="create_graduate_party" action="{{route('social.event.create.diners.quantity.discount')}}"
                          method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="from" class="form-label">Desde:</label>
                            <small>(Por ejemplo: 20)</small>
                            <input type="number" class="form-control" id="from" name="from">
                        </div>

                        <div class="mb-3">
                            <label for="to" class="form-label">Hasta:</label>
                            <small>(Por ejemplo: 40) </small>
                            <input type="number" class="form-control" id="to" name="to">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción:</label>
                            <small>(Título identificatorio)</small>
                            <input type="text" class="form-control" id="description" name="description">
                        </div>

                        <div class="mb-3">
                            <label for="discount" class="form-label">Descuento:</label>
                            <input type="number" class="form-control" id="discount" name="discount">
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

    <!-- Modal -->
    @foreach($discounts as $discount)

        <div class="modal modal-center fade" id="editDay{{$discount->id}}" tabindex="-1"
             aria-labelledby="editDay{{$discount->id}}Label"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="editDay{{$discount->id}}Label">Editar día</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="create_graduate_party" action="{{route('social.event.edit.diners.quantity.discount', $discount->id)}}" method="POST">
                            @method('PUT')
                            @csrf
                            <h4>Descuentos</h4>

                            <div class="mb-3">
                                <label for="discount{{$discount->from}}" class="form-label">Desde:</label>
                                <input value="{{$discount->from}}" type="number" class="form-control"
                                       id="discount{{$discount->from}}"
                                       name="from">
                            </div>

                            <div class="mb-3">
                                <label for="discount{{$discount->to}}" class="form-label">Hasta:</label>
                                <input value="{{$discount->to}}" type="number" class="form-control"
                                       id="discount{{$discount->to}}"
                                       name="to">
                            </div>

                            <div class="mb-3">
                                <label for="discount{{$discount->description}}" class="form-label">Descripción:</label>
                                <input value="{{$discount->description}}" type="text" class="form-control"
                                       id="discount{{$discount->description}}"
                                       name="description">
                            </div>

                            <div class="mb-3">
                                <label for="discount{{$discount->id}}" class="form-label">Descuento:</label>
                                <input value="{{$discount->discount}}" type="number" class="form-control"
                                       id="discount{{$discount->id}}"
                                       name="discount">
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

        function openEditModal(id) {
            $("#editDay" + id).modal('show');
        }

        $(document).ready(function () {
            let url = '{{route('list.social.event.dashboard.diners.quantity.discount')}}'
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
                    {
                        title: "Descripción",
                        data: 'description'
                    },
                    {
                        title: "Desde",
                        data: 'from'
                    },
                    {
                        title: "Hasta",
                        data: 'to'
                    },
                    {
                        title: "Descuento",
                        data: 'discount'
                    },
                    {
                        title: "OPCION",
                        width: "10%",
                        sortable: false,
                        "render": function (data, type, full, meta) {
                            let id = full.id;
                            console.log(full);
                            return `<a title="Editar descuento" onclick="openEditModal(${id})"> <i class="fa-solid
                            fa-pen-to-square"></i> </a>`;
                        }
                    },
                ]
            })
        })


    </script>

@stop
