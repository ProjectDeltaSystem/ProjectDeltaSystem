@extends('layouts.painel')


@section('title')
    {{ $title }}
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="error-page">
            <h2 class="headline text-warning"> {{ $code }}</h2>

            <div class="error-content">
                <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Tivemos um erro inesperado.</h3>

                <p>
                    {{ $message }}
                </p>

                <button class="btn btn-lg btn-primary w-100" onclick="window.location.href='{{ route('home') }}'"><i class="fas fa-home"></i> Voltar para Home</button>
            </div>
            <!-- /.error-content -->
        </div>
        <!-- /.error-page -->
    </section>
    <!-- /.content -->
@endsection
