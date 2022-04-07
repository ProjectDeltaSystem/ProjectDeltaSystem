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
                <button type="button" class="btn btn-success rounded-circle float-right text-center" onclick="$('#modal-default').modal('show');" style="width: 35px;height: 35px;" title="Registrar nova venda">
                    <i class="fas fa-plus" style="margin-left: -2px"></i>
                </button>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="list" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Descrição</th>
                            <th>Und.</th>
                            <th>Valor</th>
                            <th>IPI</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr onclick="window.location.href='{{ route('product', $product->id) }}'">
                                <td>{{ $product->reference }}</td>
                                <td>{{ $product->description }}</td>
                                <td>{{ $product->unity }}</td>
                                <td>{{ number_format($product->price, 2, ',', '.') }}</td>
                                <td>{{ number_format($product->ipi, 2, ',', '.') }}%</td>
                                <td>{{ $product->status ? 'Disponível' : 'Indisponível' }}</td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>

    <!--modal -->
    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
            <div class="modal-content">


                <div class="modal-header bg-primary">
                    <h4 class="modal-title" id="modal-title">
                        Importar arquivo de produtos
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="$('#modal-default').modal('hide');">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" id="products-new" enctype="multipart/form-data" action="{{ route('products.new') }}">
                    <div class="modal-body">
                        <div class="row">
                            @csrf
                            <div class="form-group col-lg-12">
                                <label>Arquivo a ser importado</label>
                                <input type="file" class="form-control" id="file" name="file" accept=".txt">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="import">Salvar</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
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
        $("#import").click(function() {
            $('#import').prop('disabled', true);
            $('#import').html('<i class="fas fa-cog fa-spin"></i> Importando...');
            $('#products-new').submit()
        });
    </script>
@endsection
