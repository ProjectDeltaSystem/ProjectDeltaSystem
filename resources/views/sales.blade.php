@extends('layouts.painel')

@section('head')
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('title')
    {{ $title }}
@endsection

@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="card-title">Listagem de Registros</h3>
                <a href="{{ route('sale.new') }}" class="btn btn-success rounded-circle float-right text-center" style="width: 35px;height: 35px;" title="Registrar nova venda">
                    <i class="fas fa-plus" style="margin-left: -2px"></i>
                </a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="list" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Embalado</th>
                            <th>Detalhado</th>
                            <th>Cliente</th>
                            <th>Emissão</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sales as $sale)
                            <tr onclick="window.location.href='{{ route('sale', $sale->id) }}'">
                                <td>{{ $sale->id }}</td>
                                <td>{{ $sale->packed ? 'Sim' : 'Não' }}</td>
                                <td>{{ $sale->detailed ? 'Sim' : 'Não' }}</td>
                                <td>{{ $sale->customer_name }}</td>
                                <td>{{ \Carbon\Carbon::parse($sale->created_at)->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i:s') }}</td>

                                <td>{{ number_format($sale->total, 2, ',', '.') }}</td>
                                @if ($sale->status == 1)
                                    @if ($sale->imported == 0)
                                        <td><i class="fas fa-square text-warning"></i> Aguardando Importação</td>
                                    @else
                                        <td><i class="fas fa-square text-success"></i> Importado</td>
                                    @endif
                                @else
                                    <td><i class="fas fa-square text-primary"></i> Em Edição</td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>


    <script>
        $(function() {
            $('#list').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "buttons": ["excel", "pdf"],
                "order": [
                    [0, "desc"]
                ],
                "oLanguage": {
                    "sProcessing": "@lang('datatable.processing')",
                    "sLengthMenu": "@lang('datatable.showRows')",
                    "sZeroRecords": "@lang('datatable.noResults')",
                    "sEmptyTable": "@lang('datatable.noDataAvailable')",
                    "sInfo": "@lang('datatable.showFrom')",
                    "sInfoEmpty": "@lang('datatable.showEmpty')",
                    "sInfoFiltered": "@lang('datatable.filterTotal')",
                    "sInfoPostFix": "",
                    "sSearch": "@lang('datatable.search')",
                    "sUrl": "",
                    "sInfoThousands": "@lang('datatable.thousandsSeparator')",
                    "sLoadingRecords": "@lang('datatable.loading')",
                    "oPaginate": {
                        "sFirst": "@lang('datatable.first')",
                        "sLast": "@lang('datatable.last')",
                        "sNext": "@lang('datatable.next')",
                        "sPrevious": "@lang('datatable.previous')"
                    }
                }
            }).buttons().container().appendTo('#list_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection
