@extends('layouts.painel')

@section('head')
@endsection

@section('title')
    {{ $title }}
@endsection
@section('parent')
    Produtos
@endsection
@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="card-title"># {{ $product->id }}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-lg-3">
                        <label for="reference">Referência</label>
                        <input type="text" class="form-control" id="reference" value="{{ $product->reference }}">
                    </div>

                    <div class="form-group">
                        <label for="description">Descrição</label>
                        <input type="text" class="form-control" id="description" value="{{ $product->description }}">
                    </div>

                    <div class="form-group col-lg-3">
                        <label for="unity">Unid. Medida</label>
                        <input type="text" class="form-control" id="unity" value="{{ $product->unity }}">
                    </div>


                    <div class="form-group col-lg-3">
                        <label for="price">Valor</label>
                        <input type="text" class="form-control" id="price" value="{{ number_format($product->price, 2, ',', '.') }}">
                    </div>

                    <div class="form-group col-lg-3">
                        <label for="ipi">I.P.I</label>
                        <input type="text" class="form-control" id="ipi" value="{{ number_format($product->ipi, 2, ',', '.') }}">
                    </div>

                    <div class="form-group col-lg-3">
                        <label for="status">Status</label>
                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                            <input type="checkbox" class="custom-control-input" id="status" {{ $product->status ? 'checked' : '' }}>
                            <label class="custom-control-label" for="status">{{ $product->status ? 'Disponível' : 'Indisponível' }}</label>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
@endsection

@section('scripts')
@endsection
