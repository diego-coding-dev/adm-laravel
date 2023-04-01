@extends('layout.adm-page')

@section('title')
{{$title}}
@endsection

@section('dashboard')
{{$dashboard}}
@endsection

@section('content')

<div class="col-sm-12">
    <div class="home-tab">

        <p class="card-description">Você deseja realmente cancelar o pedido?</p>

        <div class="col-sm-5 col-md-12 mb-3 mb-sm-0">
            <a href="{{route('order.show-cart', $orderId)}}" class="btn btn-secondary btn-sm ml-3">Não</a>
            <a href="{{route('order.cancel-order', $orderId)}}" class="btn btn-danger btn-sm ml-3">Sim, cancelar pedido</a>
        </div>
    </div>
</div>

@endsection