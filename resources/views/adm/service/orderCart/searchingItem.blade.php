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

        <div class="mb-3">
            <a href="{{route('order.show-cart', $orderId)}}" class="btn btn-danger btn-sm">Voltar</a>
        </div>

        <p class="card-description">Buscando o produto...</p>

        <form class="user" method="get" action="{{route('order.searching-item', $orderId)}}">
            @csrf
            <div class="form-group row">
                <div class="col-sm-5 col-md-4 mb-3 mb-sm-0">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-2 small" name="description" value="{{ (isset($description) ? $description : '') }}" placeholder="Buscar produto..." aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                    @if ($errors->has('description'))
                    <h6 class="mt-1 text-danger">&nbsp;*&nbsp;{{$errors->first('description')}}</h6>
                    @endif
                </div>
            </div>
        </form>

        @if ($fromStorage)

        @include('adm.service.orderCart.searchingItem.fromStorage')

        @else

        @include('adm.service.orderCart.searchingItem.fromProduct')

        @endif

    </div>
</div>

@endsection