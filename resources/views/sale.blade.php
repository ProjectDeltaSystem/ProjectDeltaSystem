@extends('layouts.painel')

@section('head')
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('title')
    {{ $title }}
@endsection

@section('content')
    <div class="col-12">

        <form method="POST" action="{{ route('sale.save') }}">
            @csrf
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title" id="reference_sale">Pedido Nº # {{ $sale->id }}</h3>
                    <h3 class="card-title" style="float: right" id="create_at_sale">Emitido em: {{ \Carbon\Carbon::parse($sale->created_at)->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i:s') }}</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="container">
                        <div class="row">
                            <div class="form-group col-lg-3">
                                <label for="packed">Embalado</label><br>
                                <input {{ $readonly }} type="checkbox" name="packed" id="packed" {{ $sale->packed ? 'checked' : '' }} data-label-text="" data-bootstrap-switch data-on-text="Sim" data-off-text="Não" data-off-color="danger"
                                       data-on-color="success">
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="detailed">Detalhado</label><br>
                                <input {{ $readonly }} type="checkbox" name="detailed" id="detailed" {{ $sale->detailed ? 'checked' : '' }} data-label-text="" data-bootstrap-switch data-on-text="Sim" data-off-text="Não" data-off-color="danger"
                                       data-on-color="success">
                            </div>
                            <div class="form-group col-lg-6" id="details" style="margin-top:-5px">
                                <label for="customer_name">Nome do Cliente</label>
                                <input {{ $readonly }} type="text" class="form-control" id="customer_name" value="{{ $sale->customer_name }} ">
                                <div id="customer_name_error"></div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">

                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <div class="row d-flex align-items-center">
                                                <div class="col-lg-6">
                                                    <h4>Itens da Venda</h3>
                                                </div>
                                                <div class="col-lg-6">
                                                    <button {{ $readonly }} class="btn btn-success rounded-circle float-right text-center" style="width: 35px;height: 35px;" type="button" title="Incluir item" onclick="saleProduct()">
                                                        <i class="fas fa-plus" style="margin-left: -2px"></i>
                                                    </button>
                                                </div>

                                            </div>
                                            <table id="list" class="table table-bordered table-hover">
                                                <thead class="bg-secondary">
                                                    <tr>
                                                        <th style="width:10%">Código</th>
                                                        <th style="width:40%">Descrição</th>
                                                        <th style="width:10%">Unidade</th>
                                                        <th style="width:10%">Quantidade</th>
                                                        <th style="width:10%">Valor</th>
                                                        <th style="width:10%">Total</th>
                                                        <th style="width:10%">IPI</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="sale_list">

                                                </tbody>
                                                <tfoot class="bg-light">
                                                    <tr id="total_ipi_footer">
                                                        <th class="text-right" colspan="5">Total dos Produtos</th>
                                                        <th class="text-left" colspan="2" id="total_products">R$ 0,00</th>
                                                    </tr>

                                                    <tr id="total_products_footer">
                                                        <th class="text-right" colspan="5">Valor IPI</th>
                                                        <th class="text-left" colspan="2" id="total_ipi">R$ 0,00</th>
                                                    </tr>

                                                    <tr>
                                                        <th class="text-right" colspan="5">Valor Total</th>
                                                        <th class="text-left" colspan="2" id="total_list">R$ 0,00</th>
                                                    </tr>

                                                </tfoot>
                                            </table>
                                        </div>
                                        <!-- /.card-body -->

                                    </div>
                                    <!-- /.card -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">

                        <a href="{{ route('sales') }}" class="btn btn-lg btn-default">Cancelar</a>
                        @if ($readonly == '')
                            <button type="button" class="btn btn-lg btn-success float-right" id="finish_sale">Concluir Pedido</button>
                            <button type="button" class="btn btn-lg btn-primary float-right mx-2" id="save_sale">Salvar</button>
                            <button type="button" class="btn btn-lg btn-danger float-right " id="sale_delete">Excluir</button>
                        @else
                            <button type="button" class="btn btn-lg btn-primary float-right mx-2" id="download">
                                <i class="fas fa-file-export"></i> Exportar para Arquivo
                            </button>
                        @endif



                    </div>
                </div>
            </div>
            <!-- /.card -->
        </form>
    </div>

    <!-- /.modal -->
    <div class="modal fade" id="delete_sale_modal" style="z-index: 1051; margin-top:10vh;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h4 class="modal-title">Exclusão da Venda</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="$('#delete_sale_modal').modal('hide');">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>
                <div class="modal-body">
                    <p>
                        Tem certeza que deseja excluir a venda? essa operação não poderá ser revertida.
                    </p>
                </div>
                <div class="modal-footer justify-content-between ">
                    <button type="button" class="btn btn-default" onclick="$('#delete_sale_modal').modal('hide');">
                        Cancelar
                    </button>
                    <button type="button" class="btn btn-danger" id="delete_sale">
                        Excluir
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- /.modal -->
    <div class="modal fade" id="delete_modal" style="z-index: 1050; margin-top:10vh;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h4 class="modal-title">Exclusão de Item</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="$('#delete_modal').modal('hide');">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>
                        Tem certeza que deseja excluir o item da venda?
                    </p>
                </div>
                <div class="modal-footer justify-content-between ">
                    <button type="button" class="btn btn-default" onclick="$('#delete_modal').modal('hide');">
                        Cancelar
                    </button>
                    <button type="button" class="btn btn-danger" id="delete">
                        Excluir
                    </button>

                </div>
            </div>
        </div>
    </div>

    <!--modal -->
    <div class="modal fade" id="modal-default" style="z-index: 1049;">
        <div class="modal-dialog">
            <div class="modal-content">


                <div class="modal-header">
                    <h4 class="modal-title">

                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="$('#modal-default').modal('hide');">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" id="sale-product" action="{{ route('sale.product.save') }}">
                    <div class="modal-body">
                        <div class="row">
                            @csrf
                            <input type="hidden" id="sale_id" name="sale_id" value="{{ $sale->id }}">
                            <input type="hidden" id="sale_product_id" name="sale_product_id" value="">
                            <div class="form-group col-lg-12">
                                <label>Produtos</label>
                                <select {{ $readonly }} class="form-control select2bs4" id="product" name="product" style="width: 100%;">
                                    <option {{ $readonly }} value="" selected></option>
                                    @foreach ($products as $product)
                                        <option {{ $readonly }} value="{{ $product->id }}">{{ $product->reference }} - {{ $product->description }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="quantity" id="quantity_label">Quantidade</label>
                                <input {{ $readonly }} type="number" class="form-control" id="quantity" name="quantity" value="0" min="0">
                            </div>

                            <div class="form-group col-lg-4">
                                <label for="price">Valor Unit.</label>
                                <label class="form-control" id="price">
                                    R$ 0,00
                                </label>
                            </div>

                            <div class="form-group col-lg-4">
                                <label for="total">Valor Total</label>
                                <label class="form-control" id="total">
                                    R$ 0,00
                                </label>
                            </div>


                            <div class="form-group col-lg-4" id="kg_sale_details" style="display:none;">
                                <label for="kg_quantity">KG por Caixa</label>
                                <label class="form-control" id="kg_quantity">
                                    0,00
                                </label>
                            </div>


                            <div class="form-group col-lg-4" id="box_sale_details" style="display:none;">
                                <label for="box_price">Valor Caixa</label>
                                <label class="form-control" id="box_price">
                                    0,00
                                </label>
                            </div>

                            <div class="form-group col-lg-4" id="total_kg_details" style="display:none;">
                                <label for="total_kg">Quant. Total (KG)</label>
                                <label class="form-control" id="total_kg">
                                    0,00
                                </label>
                            </div>

                        </div>
                        <div class="modal-footer justify-content-center">
                            <div class="w-100 p-2">
                                <button type="button" class="btn btn-default float-left" data-dismiss="modal" onclick="$('#modal-default').modal('hide');">Cancelar</button>

                                <button type="submit" {{ $readonly }} class="btn btn-primary float-right ml-2">Salvar</button>
                                <button type="button" {{ $readonly }} id="sale_product_delete" class="btn btn-danger float-right mr-2">Excluir</button>
                            </div>
                        </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection


@section('scripts')
    <!-- Bootstrap Switch -->
    <script src="{{ asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/js/select2.full.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap4-duallis/jquery.bootstrap-duallistbox.min.js') }}"></script>
    <!-- Datatable -->
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
    <!-- InputMask -->
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/inputmask/jquery.inputmask.min.js') }}"></script>

    <script>
        $(function() {
            if ($('#detailed').prop('checked')) {
                $("#details").show();
            } else {
                $("#details").hide();
            }

            saleList();

            //Datemask dd/mm/yyyy
            $('.date').inputmask('dd/mm/yyyy', {
                'placeholder': 'dd/mm/yyyy'
            })

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4',
                dropdownParent: $('#modal-default'),
                language: {
                    noResults: function() {
                        return "Nenhum resultado encontrado.";
                    }
                },
            })

            //Switch bootstrap
            $("input[data-bootstrap-switch]").each(function() {
                $(this).bootstrapSwitch('state', $(this).prop('checked'));
            })

            //Datatable
            $('#list').DataTable({
                "paging": false,
                "lengthChange": false,
                "searching": false,
                "ordering": false,
                "info": false,
                "autoWidth": false,
                "responsive": true,
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
        $('#detailed').on('change.bootstrapSwitch', function(e) {
            if (e.target.checked) {
                $("#details").show();
            } else {
                $("#details").hide();
            }
            saleList();
        });

        $("#product").change(function() {
            let id = $(this).val();
            let detailed = false;
            if ($('#detailed').prop('checked')) {
                detailed = true;
            }

            $.post("{{ route('sale.product.getPrice') }}", {
                id: id,
                detailed: detailed,
                _token: '{{ csrf_token() }}'
            }, function(response) {
                if (response.erro) {
                    $("#price").html(
                        formatCurrency(0)
                    );
                } else {

                    if (response.box) {

                        //
                        $("#quantity_label").html('Quantidade Caixas');

                        $("#kg_sale_details").show();
                        $("#box_sale_details").show();
                        $("#total_kg_details").show();

                        $("#kg_quantity").html(formatNumber(response.box_weight));
                        $("#box_price").html(formatCurrency(response.box_price));

                    } else {

                        //
                        $("#quantity_label").html('Quantidade');
                        $("#kg_sale_details").hide();
                        $("#box_sale_details").hide();
                        $("#total_kg_details").hide();

                        $("#kg_quantity").html('');
                        $("#box_price").html('');
                    }

                    $("#price").html(
                        formatCurrency(response.price)
                    );
                }
                calc();
            });
        });


        $("#sale_product_delete").click(function() {
            //$("#modal-default").modal('hide');
            $("#delete_modal").modal('show');

        });
        $("#sale_delete").click(function() {
            //$("#modal-default").modal('hide');
            $("#delete_sale_modal").modal('show');

        });

        $("#delete").click(function() {

            $.post("{{ route('sale.product.delete') }}", {
                id: $('#sale_product_id').val(),
                _token: '{{ csrf_token() }}',
            }, function(response) {
                if (response.erro) {} else {
                    saleList();
                    $("#modal-default").modal('hide');
                    $("#delete_modal").modal('hide');
                }

            });
        });

        $("#delete_sale").click(function() {
            $("#delete_sale").prop("disabled", true);
            $("#delete_sale").html('<i class="fas fa-circle-notch fa-spin"></i> Excluindo...');

            $.post("{{ route('sale.delete') }}", {
                id: $('#sale_id').val(),
                _token: '{{ csrf_token() }}',
            }, function(response) {
                if (response.erro) {} else {
                    saleList();

                    window.location.href = "{{ route('sales') }}"

                }

            });
        });

        $("#quantity").change(function() {
            calc();
        });

        $("#save_sale").click(function() {
            let total = formatToNumber($("#total_list").html());

            if (total > 0) {
                let detailed = false;
                if ($('#detailed').prop('checked')) {
                    detailed = true;
                }

                let packed = false;
                if ($('#packed').prop('checked')) {
                    packed = true;
                }

                $.post("{{ route('sale.save') }}", {
                    id: $('#sale_id').val(),
                    _token: '{{ csrf_token() }}',
                    packed: packed,
                    detailed: detailed,
                    customer_name: $('#customer_name').val(),
                }, function(response) {
                    if (response.erro) {
                        return;
                    }
                    window.location.href = "/sales";
                });
                return true
            }
            alert('Não é possivel salvar uma venda sem valor.');
            return false
        });

        $("#finish_sale").click(function() {

            $("#customer_name").removeClass('is-invalid');
            $("#customer_name_error").html(``);

            let total = formatToNumber($("#total_list").html());


            if (total > 0) {
                let detailed = false;
                if ($('#detailed').prop('checked')) {
                    detailed = true;
                }

                let packed = false;
                if ($('#packed').prop('checked')) {
                    packed = true;
                }

                if (detailed == true && $("#customer_name").val().trim() == "") {

                    $("#customer_name").addClass('is-invalid');
                    $("#customer_name_error").html(`<span class="text-danger">
                                            <strong>Campo Obrigatório</strong>
                                        </span>`);
                    return;
                } else {

                    $.post("{{ route('sale.save') }}", {
                        id: $('#sale_id').val(),
                        _token: '{{ csrf_token() }}',
                        packed: packed,
                        detailed: detailed,
                        customer_name: $('#customer_name').val(),
                        status: 1
                    }, function(response) {
                        if (response.erro) {
                            return;
                        }
                        window.location.href = "/sales";
                    });
                    return true
                }

            } else {
                alert('Não é possivel concluir uma venda sem valor.');
                return false
            }
        });

        $("#sale-product").submit(function(e) {
            e.preventDefault();
            let detailed = false;
            if ($('#detailed').prop('checked')) {
                detailed = true;
            }

            let packed = false;
            if ($('#packed').prop('checked')) {
                packed = true;
            }

            $.ajax({
                type: "POST",
                url: "{{ route('sale.product.save') }}",
                data: {
                    _token: '{{ csrf_token() }}',
                    sale_product_id: $('#sale_product_id').val(),
                    sale_id: $('#sale_id').val(),
                    product: $('#product').val(),
                    quantity: $('#quantity').val(),
                    packed: packed,
                    detailed: detailed,
                    customer_name: $('#customer_name').val(),
                },
                success: function(response) {
                    if (response.erro) {

                    } else {
                        $("#sale_id").val(response.sale_id);
                        $("#reference_sale").html('Pedido Nº # ' + response.sale.id);
                        let sale_created_at = new Date(response.sale.created_at);

                        $("#created_at_sale").html('Emitido em: ' + formatDate(sale_created_at));
                        saleList();
                        $('#modal-default').modal('hide');
                    }
                }
            });
        });

        function saleProduct(id = "") {
            if (id != "") {
                $("#sale_product_delete").show();
                $.post("{{ route('sale.product.edit') }}", {
                    id: id,
                    _token: '{{ csrf_token() }}'
                }, function(response) {

                    $("#sale_product_id").val(response.result.id);
                    $("#product").val(response.result.product_id).change();
                    $("#quantity").val(response.result.quantity);
                    $("#price").val(response.result.price);
                    calc();
                    $('#modal-default').modal('show');
                });

            } else {
                $("#sale_product_delete").hide();
                $("#sale_product_id").val("");
                $("#product").val("").change();
                $("#quantity").val(0);
                $("#price").html(0);
                calc();
                $('#modal-default').modal('show');
            }

        }

        function calc() {

            let quantity = formatToNumber($('#quantity').val());
            let price = formatToNumber($("#price").html());


            let kg = formatToNumber($("#kg_quantity").html());

            console.log(quantity);
            console.log(kg);

            const total = quantity * price;
            const total_kg = quantity * kg;
            const total_box = kg * price;

            $("#total").html(formatCurrency(total));
            $("#box_price").html(formatCurrency(total_box));
            $("#total_kg").html(formatNumber(total_kg));

        }

        function saleList() {

            $.post("{{ route('sale.list') }}", {
                id: $("#sale_id").val(),
                _token: '{{ csrf_token() }}'
            }, function(response) {
                if (response.erro) {} else {
                    let detailed = false;

                    if ($('#detailed').prop('checked')) {
                        detailed = true;
                    }

                    let items = response.items;
                    let html = '';
                    let total = 0;
                    let total_ipi = 0;

                    if (items.length > 0) {
                        $.each(items, function(i, item) {
                            if (!detailed) {

                                if (item.product.reference.substring(0, 3) == "TBC" || item.product.reference.substring(0, 3) == "DBC") {
                                    html = html + `<tr onclick="saleProduct('` + item.id + `')">
                                        <td>` + item.product.reference + `</td>
                                        <td>` + item.product.description + `</td>
                                        <td>` + item.product.unity + `</td>
                                        <td class="text-right">` + formatNumber(item.quantity * item.product.box_weight) + ` KG</td>
                                        <td class="text-right">` + formatCurrency(item.product.promotional_price) + `</td>
                                        <td class="text-right">` + formatCurrency(item.quantity * item.product.promotional_price) + `</td>
                                        <td class="text-right">` + item.product.ipi + `%</td>
                                    </tr>`;
                                } else {
                                    html = html + `<tr onclick="saleProduct('` + item.id + `')">
                                        <td>` + item.product.reference + `</td>
                                        <td>` + item.product.description + `</td>
                                        <td>` + item.product.unity + `</td>
                                        <td class="text-right">` + item.quantity + `</td>
                                        <td class="text-right">` + formatCurrency(item.product.promotional_price) + `</td>
                                        <td class="text-right">` + formatCurrency(item.quantity * item.product.promotional_price) + `</td>
                                        <td class="text-right">` + item.product.ipi + `%</td>
                                    </tr>`;
                                }
                                total += (item.quantity * item.product.promotional_price);

                            } else {
                                if (item.product.reference.substring(0, 3) == "TBC" || item.product.reference.substring(0, 3) == "DBC") {
                                    html = html + `<tr onclick="saleProduct('` + item.id + `')">
                                        <td>` + item.product.reference + `</td>
                                        <td>` + item.product.description + `</td>
                                        <td>` + item.product.unity + `</td>
                                        <td class="text-right">` + formatNumber(item.quantity * item.product.box_weight) + ` KG</td>
                                        <td class="text-right">` + formatCurrency(item.product.promotional_price) + `</td>
                                        <td class="text-right">` + formatCurrency(item.quantity * item.product.promotional_price) + `</td>
                                        <td class="text-right">` + item.product.ipi + `%</td>
                                    </tr>`;
                                } else {
                                    html = html + `<tr onclick="saleProduct('` + item.id + `')">
                                    <td>` + item.product.reference + `</td>
                                    <td>` + item.product.description + `</td>
                                    <td>` + item.product.unity + `</td>
                                    <td class="text-right">` + item.quantity + `</td>
                                    <td class="text-right">` + formatCurrency(item.product.price) + `</td>
                                    <td class="text-right">` + formatCurrency(item.quantity * item.product.price) + `</td>
                                    <td class="text-right">` + item.product.ipi + `%</td>
                                </tr>`;
                                }
                                total += (item.quantity * item.product.price);
                                total_ipi += (((item.quantity * item.product.price) * item.product.ipi) / 100);
                            }
                        });
                        if (detailed) {
                            $("#total_products").html(formatCurrency(total));
                            $("#total_ipi").html(formatCurrency(total_ipi));

                            $("#total_products_footer").show();
                            $("#total_ipi_footer").show();

                            total = (total + total_ipi);
                        } else {

                            $("#total_products_footer").hide();
                            $("#total_ipi_footer").hide();
                        }

                        $("#sale_list").html(html);
                        $("#total_list").html(formatCurrency(total));
                    }
                }

            });
        }
    </script>
@endsection
