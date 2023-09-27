@extends('appAdmin')
@extends('adminlte::page')

@section('title', 'Cobros por día')

@section('content_header')
    <h1>Cobros por día</h1>
@stop

@section('content')

    <div class="row">

        <div class="col-12 mt-3 mb-4">
            {{--            <h3>Pagos en efectivo:</h3>--}}
            <form>
                <div class="form-group">
                    <label for="date">Fecha:</label>
                    <input class="form-input" type="date" name="date" id="date">
                </div>
                <button type="button" onclick="search()" class="btn btn-primary">
                    Buscar
                </button>
            </form>

        </div>

        <div class="col-6 mt-3 mb-4">
            <h4 id="cash_payments_text"></h4>
            <h4 id="electronic_payments_text"></h4>
            <h4 id="total_text"></h4>

        </div>
    </div>
    <!-- Button trigger modal -->
    {{--    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createGraduateParty">--}}
    {{--        Volver--}}
    {{--    </button>--}}

    <table id="listado_eventos" class="display nowrap mt-5" style="width:100%">
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
        function search()
        {
            let date = $('#date').val();

            $.ajax({
                type: "get",
                url: `{{route('search.chargesPerDay')}}?date=${date}`,
                cache: false,
                processData: false,  // tell jQuery not to process the data
                contentType: false,   // tell jQuery not to set contentType
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (data) {

                    parsed = $.parseJSON(data);

                    $('#cash_payments_text').text(`Pagos en efectivo: $${parsed.cashPayments}`)
                    $('#electronic_payments_text').text(`Pagos en medios electronicos: $${parsed.electronicsPayments}`)
                    $('#total_text').text(`Total: $${parsed.cashPayments + parsed.electronicsPayments}`)

                    let url = `{{route('list.payments')}}?date=${date}`
                    let table = $('#listado_eventos').DataTable();
                    table.destroy();
                    $('#listado_eventos').empty();


                    $('#listado_eventos').DataTable({

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
                                title: "MONTO",
                                data: 'monto'
                            },
                            {
                                title: "TIPO",
                                data: 'tipo'
                            },
                            {
                                title: "MEDIO",
                                data: 'medio'
                            },
                            {
                                title: "FECHA",
                                data: 'fecha'
                            },
                        ],
                    })


                },
                error: function () {
                    // toastr.error("Error con el servidor");
                }
            });
        }

    </script>
@stop
