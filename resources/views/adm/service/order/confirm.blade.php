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

        <p class="card-description">Você confirma o pedido?</p>

        <div class="col-sm-5 col-md-12 mb-3 mb-sm-0">
            <a href="{{route('order.show-cart', $orderId)}}" class="btn btn-secondary btn-sm ml-3">Não</a>
            <a href="{{route('order.finish', $orderId)}}" class="btn btn-success btn-sm ml-3">Sim, finalizar pedido</a>
        </div>
    </div>
</div>

@endsection